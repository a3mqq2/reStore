<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Content;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // IMPORTANT: Check if user is authenticated FIRST
        // Any logged-in user (admin or customer) should bypass maintenance mode
        if (auth()->guard('web')->check() || auth()->guard('customer')->check()) {
            return $next($request);
        }

        // List of routes that should bypass maintenance mode (for login/logout)
        $excludedRoutes = [
            'login',
            'admin/login',
            'logout',
            'admin/logout',
            'do-login',
        ];

        // Check if current route matches any excluded route
        foreach ($excludedRoutes as $route) {
            if ($request->is($route)) {
                return $next($request);
            }
        }

        // Get maintenance mode status from content
        $content = Content::first();

        if ($content && $content->maintenance_mode) {
            // Show maintenance page
            return response()->view('maintenance', [
                'message' => $content->maintenance_message ?? 'الموقع تحت الصيانة حالياً. سنعود قريباً!'
            ], 503);
        }

        return $next($request);
    }
}
