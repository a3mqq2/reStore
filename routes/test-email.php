<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Customer;

Route::get('/test-email-config', function () {
    $config = [
        'MAIL_MAILER' => config('mail.default'),
        'MAIL_HOST' => config('mail.mailers.smtp.host'),
        'MAIL_PORT' => config('mail.mailers.smtp.port'),
        'MAIL_USERNAME' => config('mail.mailers.smtp.username'),
        'MAIL_PASSWORD' => config('mail.mailers.smtp.password') ? '***SET***' : 'NOT SET',
        'MAIL_ENCRYPTION' => config('mail.mailers.smtp.encryption'),
        'MAIL_FROM_ADDRESS' => config('mail.from.address'),
        'MAIL_FROM_NAME' => config('mail.from.name'),
    ];

    Log::info('Email configuration check', $config);

    return response()->json([
        'status' => 'Email configuration',
        'config' => $config,
        'note' => 'Port 465 should use SSL, Port 587 should use TLS',
        'warning' => config('mail.mailers.smtp.port') == 465 && config('mail.mailers.smtp.encryption') == 'tls'
            ? 'INCORRECT: Port 465 with TLS (should be SSL)'
            : 'Configuration looks correct'
    ]);
});

Route::get('/test-email-send', function () {
    try {
        Log::info('Starting test email send');

        $testEmail = request('email', 'aishaaltery89@gmail.com');

        Mail::raw('This is a test email from ReStore to verify email configuration.', function ($message) use ($testEmail) {
            $message->to($testEmail)
                    ->subject('Test Email - ReStore Configuration');
        });

        Log::info('Test email sent successfully', ['to' => $testEmail]);

        return response()->json([
            'status' => 'success',
            'message' => 'Test email sent successfully to ' . $testEmail,
            'note' => 'Check your email and the logs at storage/logs/laravel.log'
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to send test email', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'note' => 'Check storage/logs/laravel.log for detailed error'
        ], 500);
    }
});

Route::get('/test-password-reset/{customerId}', function ($customerId) {
    try {
        Log::info('Testing password reset flow', ['customer_id' => $customerId]);

        $customer = Customer::findOrFail($customerId);

        Log::info('Customer found', [
            'id' => $customer->id,
            'email' => $customer->email,
            'name' => $customer->name
        ]);

        // Generate token
        $token = \Illuminate\Support\Str::random(60);
        $customer->remember_token = $token;
        $customer->save();

        Log::info('Token generated', ['token_length' => strlen($token)]);

        // Try to send notification
        $customer->notify(new \App\Notifications\SendResetLink($customer->email, $customer));

        Log::info('Reset notification sent successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset email sent to ' . $customer->email,
            'reset_url' => route('website.reset-link', ['token' => $token, 'email' => $customer->email]),
            'note' => 'Check email and logs at storage/logs/laravel.log'
        ]);

    } catch (\Exception $e) {
        Log::error('Password reset test failed', [
            'customer_id' => $customerId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'note' => 'Check storage/logs/laravel.log for detailed error'
        ], 500);
    }
});
