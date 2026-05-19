<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfProfessionalAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user instanceof User && $user->isProfessional()) {

            if ($this->shouldReturnJson($request)) {
                $token = $user->createToken('auth_token', ['role:' . $user->role])->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Already logged in',
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->full_name ?: $user->name,
                        'email' => $user->email,
                        'verified' => (bool) $user->verified,
                    ],
                    'redirect' => route('professional.dashboard'),
                ]);
            }

            return redirect()->route('professional.dashboard');
        }

        return $next($request);
    }

    private function shouldReturnJson(Request $request): bool
    {
        return $request->expectsJson() || $request->wantsJson() || $request->ajax();
    }
}
