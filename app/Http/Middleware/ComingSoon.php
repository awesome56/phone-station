<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ComingSoon
{
    /**
     * Show the coming-soon page to guests while the site is under construction.
     * Users with dashboard access (admins and staff) may preview the store.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('app.coming_soon')) {
            return $next($request);
        }

        $user = $request->user();

        if ($user && $user->hasPermission('dashboard.view')) {
            return $next($request);
        }

        if ($request->route()?->getName() === 'coming-soon') {
            return $next($request);
        }

        return redirect()->route('coming-soon');
    }
}
