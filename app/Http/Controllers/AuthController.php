<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login() {
        return view('login');
    }


    public function do_login(Request $request) {
        $request->validate([
            "login" => "required",
            "password" => "required"
        ]);

        $login = $request->input('login');
        $password = $request->input('password');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where('email', $request->login)->orWhere('phone', $request->phone)->first();
      
        if(!$user) {
            return redirect()->back()->withErrors(['login' => 'لا يوجد مستخدم بهذه البيانات']);
        }

        if (Auth::attempt([$fieldType => $login, 'password' => $password])) {
            $user = Auth::user();


            $token = $user->createToken('authToken')->plainTextToken;

            $cookie = cookie('ast', $token, config('auth.token_expiration_minutes'));

            return redirect('/home')->withCookie($cookie);
        } else {
            return back()->withInput()->withErrors(['login' => 'هناك خطأ في البريد الإلكتروني أو كلمة المرور']);
        }
    }


    public function customer_logout(Request $request) {
        Auth::guard('customer')->logout();

        // Only regenerate CSRF token, don't invalidate entire session
        // This prevents logging out admin if they're logged in
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function logout(Request $request) {
        Auth::guard('web')->logout();

        // Only regenerate CSRF token, don't invalidate entire session
        // This prevents logging out customer if they're logged in
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Unable to login with Facebook. Please try again.');
        }

        // Check if the customer already exists in the database
        $customer = Customer::where('email', $facebookUser->getEmail())->first();

        if (!$customer) {
            // Create a new customer if one does not exist
            $customer = Customer::create([
                'name' => $facebookUser->getName(),
                'email' => $facebookUser->getEmail(),
                'password' => Hash::make(rand(100000, 999999)),
                // Ensure you handle other required fields such as 'city_id'
                'city_id' => $defaultCityId ?? null,
            ]);
        }

        // Log the customer in
        Auth::guard('customer')->login($customer);

        return redirect('/')->with('success', 'Successfully logged in with Facebook.');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'لا يمكن الدخول في الوقت الحالي');
        }
    
        // Find or create a new customer
        $customer = Customer::where('email', $googleUser->getEmail())->first();
    
        if (!$customer) {
            $customer = Customer::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(rand(100000, 999999)),
                'city_id' => $defaultCityId ?? null,
            ]);
        }
    
        // Log the customer in
        Auth::guard('customer')->login($customer);
    
        // Create a token for the user
        $token = $customer->createToken('authToken')->plainTextToken;
    
        // Create a cookie for the token
        $cookie = cookie('ast', $token, config('auth.token_expiration_minutes'));
    
        return redirect('/')->with('success', 'تم تسجيل الدخول بنجاح')->withCookie($cookie);
    }
    


    protected function registerOrLoginUser($data)
    {
        $user = User::where('email', $data->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => bcrypt('123456dummy'), // You can create a random password here
            ]);
        }

        Auth::login($user);
    }

    
}
