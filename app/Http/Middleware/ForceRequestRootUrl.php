<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Align URL generation with the incoming request host/scheme.
 *
 * When APP_URL differs from the dashboard host (e.g. localhost vs dash.*),
 * route()-based redirects can point at the wrong origin and break the session
 * cookie, which surfaces as ERR_TOO_MANY_REDIRECTS between login and workspace.
 */
class ForceRequestRootUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $root = $request->getSchemeAndHttpHost();
        if ($root !== '') {
            URL::forceRootUrl($root);
        }

        if ($request->secure()) {
            URL::forceScheme('https');
        }

        return $next($request);
    }
}
