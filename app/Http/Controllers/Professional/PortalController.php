<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Project;
use App\Models\User;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function __construct(private SubscriptionService $subscription) {}

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user instanceof User && $user->isProfessional()) {
                return redirect()->route('professional.dashboard');
            }

            if ($user instanceof User && $user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('professional.auth.login');
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse|RedirectResponse
    {
        $shouldReturnJson = $this->shouldReturnJson($request);

        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !$user->isProfessional()) {
            if ($shouldReturnJson) {
                return response()->json(['message' => 'This login is only for professional accounts.', 'errors' => ['email' => ['This login is only for professional accounts.']]], 422);
            }
            return back()->withErrors(['email' => 'This login is only for professional accounts.'])->withInput();
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($shouldReturnJson) {
                return response()->json(['message' => 'Invalid credentials.', 'errors' => ['email' => ['Invalid credentials.']]], 422);
            }
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user instanceof User || !$user->isProfessional()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($shouldReturnJson) {
                return response()->json(['message' => 'This login is only for professional accounts.', 'errors' => ['email' => ['This login is only for professional accounts.']]], 422);
            }
            return back()->withErrors(['email' => 'This login is only for professional accounts.'])->withInput();
        }

        if (!$user->verified) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($shouldReturnJson) {
                return response()->json(['message' => 'Please verify your account first.', 'errors' => ['email' => ['Please verify your account first.']]], 403);
            }
            return back()->withErrors(['email' => 'Please verify your account first.'])->withInput();
        }

        $this->syncLegacySession($request, $user);

        if ($shouldReturnJson) {
            $token = $user->createToken('auth_token', ['role:' . $user->role])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->full_name ?: $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'verified' => (bool) $user->verified,
                ],
                'redirect' => route('professional.dashboard')
            ]);
        }

        return redirect()->route('professional.dashboard');
    }

    private function shouldReturnJson(Request $request): bool
    {
        return $request->expectsJson() || $request->wantsJson() || $request->ajax();
    }

    public function dashboard(Request $request): View
    {
        /** @var User $professional */
        $professional = Auth::user();

        $this->syncLegacySession($request, $professional);

        if ($professional->isBuyer()) {
            // Same conversation identity as ChatController::userChat (project + seller).
            $buyerChatCount = (int) DB::query()
                ->fromSub(
                    Chat::query()
                        ->where('user_id', $professional->id)
                        ->select('project_id', 'professional_id')
                        ->groupBy('project_id', 'professional_id'),
                    'buyer_conversations'
                )
                ->count();

            return view('professional.dashboard.index', [
                'buyerChatCount' => $buyerChatCount,
            ]);
        }

        $projects = Project::where('professional_id', $professional->id);

        $requestedStart = $request->query('start_date');
        $requestedEnd = $request->query('end_date');
        $today = now()->endOfDay();
        $rangeEnd = $today->copy();
        $rangeStart = $today->copy()->subDays(13)->startOfDay();

        try {
            if (!empty($requestedStart)) {
                $rangeStart = Carbon::parse($requestedStart)->startOfDay();
            }
            if (!empty($requestedEnd)) {
                $rangeEnd = Carbon::parse($requestedEnd)->endOfDay();
            }
        } catch (\Throwable) {
            $rangeEnd = $today->copy();
            $rangeStart = $today->copy()->subDays(13)->startOfDay();
        }

        if ($rangeStart->greaterThan($rangeEnd)) {
            [$rangeStart, $rangeEnd] = [$rangeEnd->copy()->startOfDay(), $rangeStart->copy()->endOfDay()];
        }

        $days = max(1, $rangeStart->diffInDays($rangeEnd) + 1);
        $chartDays = min($days, 60);
        $chartStartDate = $rangeEnd->copy()->subDays($chartDays - 1)->startOfDay();
        $filteredProjects = (clone $projects)->whereBetween('created_at', [$rangeStart, $rangeEnd]);

        $buildDailySeries = function ($query) use ($chartStartDate, $chartDays) {
            $rows = (clone $query)
                ->where('created_at', '>=', $chartStartDate)
                ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
                ->groupBy('day')
                ->pluck('total', 'day');

            $series = [];
            for ($i = 0; $i < $chartDays; $i++) {
                $date = $chartStartDate->copy()->addDays($i);
                $key = $date->toDateString();
                $series[] = [
                    'date'  => $date->format('j/n/y'),
                    'full'  => $date->format('j/n/y'),
                    'count' => (int) ($rows[$key] ?? 0),
                ];
            }
            return $series;
        };

        $totalQuery     = (clone $filteredProjects)->notDeleted();
        $inactiveQuery  = (clone $filteredProjects)->notDeleted()->where('active', 0);
        $activeQuery    = (clone $filteredProjects)->notDeleted()->where('active', 1);
        $premiumQuery   = (clone $filteredProjects)->notDeleted()->where('premium', 1);

        $months = 12;
        $monthStart = now()->startOfMonth()->subMonths($months - 1);

        $buildMonthlySeries = function ($query) use ($monthStart, $months) {
            $rows = (clone $query)
                ->where('created_at', '>=', $monthStart)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total")
                ->groupBy('ym')
                ->pluck('total', 'ym');

            $series = [];
            for ($i = 0; $i < $months; $i++) {
                $date = $monthStart->copy()->addMonths($i);
                $key = $date->format('Y-m');
                $series[] = [
                    'month' => $date->format('M'),
                    'full'  => $date->format('M Y'),
                    'count' => (int) ($rows[$key] ?? 0),
                ];
            }
            return $series;
        };

        $monthlyTotal   = $buildMonthlySeries($totalQuery);
        $monthlyPremium = $buildMonthlySeries($premiumQuery);
        $monthlyActive  = $buildMonthlySeries($activeQuery);

        return view('professional.dashboard.index', [
            'Total'     => (clone $filteredProjects)->notDeleted()->count(),
            'Normal'    => (clone $filteredProjects)->notDeleted()->where('premium', 0)->count(),
            'Premium'   => (clone $filteredProjects)->notDeleted()->where('premium', 1)->count(),
            'Active'    => (clone $filteredProjects)->notDeleted()->where('active', 1)->count(),
            'De_Active' => (clone $filteredProjects)->notDeleted()->where('active', 0)->count(),
            'Deleted'   => (clone $filteredProjects)->where(function ($q) {
                $q->where('status', 1)->orWhereNotNull('deleted_at');
            })->count(),
            'Block'     => (clone $filteredProjects)->notDeleted()->where('block', 1)->count(),
            'Top_Projects' => (clone $filteredProjects)
                ->notDeleted()
                ->withCount([
                    'chats as chats_count' => function ($chatQuery) use ($professional, $rangeStart, $rangeEnd) {
                        $chatQuery
                            ->select(DB::raw('COUNT(DISTINCT user_id)'))
                            ->where('professional_id', $professional->id)
                            ->whereBetween('created_at', [$rangeStart, $rangeEnd]);
                    },
                ])
                ->orderByDesc('chats_count')
                ->limit(10)
                ->get(['id', 'name', 'premium', 'created_at']),
            'DailyTotal'    => $buildDailySeries($totalQuery),
            'DailyInactive' => $buildDailySeries($inactiveQuery),
            'DailyActive'   => $buildDailySeries($activeQuery),
            'DailyPremium'  => $buildDailySeries($premiumQuery),
            'MonthlyTotal'   => $monthlyTotal,
            'MonthlyPremium' => $monthlyPremium,
            'MonthlyActive'  => $monthlyActive,
            'selectedStartDate' => $rangeStart->toDateString(),
            'selectedEndDate' => $rangeEnd->toDateString(),
            'selectedRangeLabel' => $rangeStart->format('M j, Y') . ' - ' . $rangeEnd->format('M j, Y'),
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->forget([
            'raising_id',
            'name',
            'email',
            'type',
            'profile',
            'profile_url',
            'plan_type',
            'plan_expiry',
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('professional.login');
    }

    private function syncLegacySession(Request $request, User $professional): void
    {
        $activePlan = $this->subscription->getActivePlan($professional);

        $request->session()->put([
            'raising_id'  => $professional->id,
            'name'        => $professional->full_name ?: $professional->name,
            'email'       => $professional->email,
            'type'        => $this->legacyTypeFromRole($professional->role),
            'profile'     => optional($professional->documents->firstWhere('type', 'profile'))->file ?? null,
            'profile_url' => optional($professional->document('profile')->first())->url,
            'plan_type'   => $activePlan?->type ?? 0,
            'plan_expiry' => optional($activePlan?->expiry)->format('Y-m-d'),
        ]);
    }

    private function legacyTypeFromRole(string $role): int
    {
        return match ($role) {
            'buyer' => 1,
            'seller' => 2,
            'capital_raiser' => 3,
            'broker' => 4,
            default => 0,
        };
    }
}
