<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keep buyers out of seller-only areas (listings, pricing). They use chat, dashboard, profile, onboarding.
 * Must not redirect when the buyer is already on chat (or any allowed route) — that caused ERR_TOO_MANY_REDIRECTS.
 */
class RedirectProfessionalBuyerFromSellerWorkspace
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! ($user instanceof User) || ! $user->isBuyer()) {
            return $next($request);
        }

        if (! $this->buyerIsAccessingSellerOnlyWorkspace($request)) {
            return $next($request);
        }

        return redirect()->route('professional.chat');
    }

    private function buyerIsAccessingSellerOnlyWorkspace(Request $request): bool
    {
        return $request->is('professionals/dashboard/listings', 'professionals/dashboard/listings/*')
            || $request->is('professionals/dashboard/plan', 'professionals/dashboard/plan/*')
            || $request->is('professionals/dashboard/project', 'professionals/dashboard/project/*');
    }
}
