<?php

namespace App\Http\Middleware;

use Closure;

class HandleCors
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            return response()->json('OK', 200)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'PUT, POST, DELETE, GET, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type');
        }

        // For all other requests
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'PUT, POST, DELETE, GET, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type');
    }
}
