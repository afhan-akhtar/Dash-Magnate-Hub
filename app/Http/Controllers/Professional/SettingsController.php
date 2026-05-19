<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FileUploadService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        private FileUploadService $fileUpload,
        private SubscriptionService $subscription
    ) {}

    public function index(Request $request): View
    {
        /** @var User $professional */
        $professional = Auth::user()->load('documents');
        $this->syncLegacySession($request, $professional);

        return view('professional.dashboard.profile', [
            'professional' => $professional,
            'profilePhoto' => optional($professional->document('profile')->first())->url,
            'companyLogo'  => optional($professional->document('company_logo')->first())->url,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $professional */
        $professional = Auth::user();
        $isBroker = $professional->role === 'broker';

        $validated = $request->validate([
            'first_name'   => 'nullable|string|max:255',
            'last_name'    => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:50',
            'nationality'  => 'nullable|string|max:255',
            'gender'       => 'nullable|integer',
            'company_name' => 'nullable|string|max:255',
        ]);

        if (!$isBroker) {
            unset($validated['company_name']);
        }

        $professional->update(array_merge($validated, [
            'name' => trim(($validated['first_name'] ?? $professional->first_name ?? '') . ' ' . ($validated['last_name'] ?? $professional->last_name ?? '')),
        ]));

        $this->syncLegacySession($request, $professional->fresh()->load('documents'));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $professional */
        $professional = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $professional->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $professional->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        /** @var User $professional */
        $professional = Auth::user();

        $request->validate([
            'avatar' => 'required|image|max:5120',
        ]);

        $this->fileUpload->replaceDocument($request->file('avatar'), $professional, 'profile', 'uploads/professionals');
        $this->syncLegacySession($request, $professional->fresh()->load('documents'));

        return back()->with('success', 'Profile photo updated successfully.');
    }

    public function updateCompanyLogo(Request $request): RedirectResponse
    {
        /** @var User $professional */
        $professional = Auth::user();

        if ($professional->role !== 'broker') {
            abort(403);
        }

        $request->validate([
            'logo' => 'required|image|max:5120',
        ]);

        $this->fileUpload->replaceDocument($request->file('logo'), $professional, 'company_logo', 'uploads/professionals');
        $this->syncLegacySession($request, $professional->fresh()->load('documents'));

        return back()->with('success', 'Company logo updated successfully.');
    }

    private function syncLegacySession(Request $request, User $professional): void
    {
        $activePlan = $this->subscription->getActivePlan($professional);

        $request->session()->put([
            'raising_id'  => $professional->id,
            'name'        => $professional->full_name ?: $professional->name,
            'email'       => $professional->email,
            'type'        => match ($professional->role) {
                'buyer' => 1,
                'seller' => 2,
                'capital_raiser' => 3,
                'broker' => 4,
                default => 0,
            },
            'profile'     => basename((string) optional($professional->document('profile')->first())->path),
            'profile_url' => optional($professional->document('profile')->first())->url,
            'plan_type'   => $activePlan?->type ?? 0,
            'plan_expiry' => optional($activePlan?->expiry)->format('Y-m-d'),
        ]);
    }
}


