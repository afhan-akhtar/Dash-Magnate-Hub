<?php

namespace App\Http\Middleware;

use App\Models\ProfessionalQuestion;
use App\Models\ProfessionalQuestionAnswer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfessionalQuestionsAnswered
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip the onboarding route itself to prevent redirect loop
        if ($request->routeIs('professional.onboarding') || $request->routeIs('professional.onboarding.submit')) {
            return $next($request);
        }

        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Count required questions for the user's role
        $requiredCount = ProfessionalQuestion::where('role', $user->role)
            ->where('status', 1)
            ->where('is_required', true)
            ->count();

        if ($requiredCount === 0) {
            // No required questions for this role
            return $next($request);
        }

        // Count how many required questions the user has answered
        $answeredCount = ProfessionalQuestionAnswer::where('user_id', $user->id)
            ->whereHas('question', fn ($q) => $q->where('role', $user->role)->where('status', 1)->where('is_required', true))
            ->count();

        if ($answeredCount < $requiredCount) {
            return redirect()->route('professional.onboarding');
        }

        return $next($request);
    }
}
