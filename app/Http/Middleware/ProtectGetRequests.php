<?php

namespace App\Http\Middleware;

use Closure;

class ProtectGetRequests
{
    public function handle($request, Closure $next)
    {

        if ($request->method() === 'GET') {
            // Handle the GET request, for example, redirecting
            return redirect()->route('login'); // Redirect to login route
        }

        return $next($request);
    }
}
