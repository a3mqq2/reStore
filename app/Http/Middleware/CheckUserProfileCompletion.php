<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('customer')->user();

        // Check if the user's city_id or phone_number is missing
        if (!$user->city_id || !$user->phone_number) {
            // Redirect to the profile route with the query parameter
            return redirect('/profile?my=1');
        }

        return $next($request);
    }
}
