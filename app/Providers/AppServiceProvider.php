<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();


        if (!auth()->check()) {
            // التأكد من أن الجلسة قد بدأت
            if (!Session::isStarted()) {
                Session::start();
            }
    
            // إذا لم يكن هناك كوكي session_id موجود، قم بإنشاء واحد
            if (!Cookie::has('guest_session_id')) {
                $sessionId = Session::getId();
                Cookie::queue('guest_session_id', $sessionId, 60 * 24 * 30); // حفظ session_id في الكوكي لمدة 30 يومًا
            }
        }


        View::composer('*', function ($view) {
            // Check if a customer is authenticated
            if (Auth::guard('customer')->check()) {
                // Customer is logged in, set the 'customerId' cookie
                $customerId = Auth::guard('customer')->id();
                Cookie::queue('customerId', $customerId, 60 * 24 * 30); // 30 days
            } else {
                // No customer logged in, remove the 'customerId' cookie
                Cookie::queue(Cookie::forget('customerId'));
            }
        });
        
    }
}
