<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If customer is logged in but trying to access admin, logout customer
        if (auth('customer')->check() && !auth('web')->check()) {
            auth('customer')->logout();
            return redirect()->route('login')->withErrors(['error' => 'يجب تسجيل الدخول كمسؤول للوصول إلى هذه الصفحة']);
        }

        return $next($request);
    }
}
