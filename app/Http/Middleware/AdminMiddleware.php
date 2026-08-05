<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Ensure the authenticated user may access the admin area.
     */
    public function handle(Request $request, Closure $next, string $permission = 'dashboard.view'): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        abort_if(! $user->hasPermission($permission), 403, 'You do not have permission to do that.');

        return $next($request);
    }
}
