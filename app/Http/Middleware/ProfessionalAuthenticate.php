<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfessionalAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user instanceof User || !$user->isProfessional()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('professional.login');
        }

        return $next($request);
    }
}
