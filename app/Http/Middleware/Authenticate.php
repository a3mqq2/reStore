<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Check if the request is for admin routes (starts with /admin, /home, or dashboard-related paths)
        if ($request->is('admin/*') || $request->is('home') || $request->is('users/*') ||
            $request->is('reports/*') || $request->is('cities/*') || $request->is('content') ||
            $request->is('banners/*') || $request->is('redemptions/*') || $request->is('variant-redemptions') ||
            $request->is('payment-methods/*') || $request->is('customers/*') || $request->is('cards/*') ||
            $request->is('faqs/*') || $request->is('products/*') || $request->is('product_categories/*') ||
            $request->is('coupons/*') || $request->is('cashbacks/*') || $request->is('orders/*') ||
            $request->is('discounts/*') || $request->is('messages/*') || $request->is('account-categories/*') ||
            $request->is('accounts/*')) {
            return route('login'); // Admin login
        }

        // For all other routes (customer/website routes), redirect to customer login
        return route('website.login');
    }
}
