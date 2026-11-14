<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    
    public function store(
        Request $request,
        \App\Services\EmailOtpService $otpService
    ): RedirectResponse {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        $key = 'login:' . strtolower($credentials['email']) . ':' . $request->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again later.',
            ])->onlyInput('email');
        }

        if (!Auth::validate($credentials)) {
            \Illuminate\Support\Facades\RateLimiter::hit($key, 60);

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        \Illuminate\Support\Facades\RateLimiter::clear($key);

        $user = \App\Models\User::where('email', $credentials['email'])->firstOrFail();

        $otp = $otpService->send($user);

        $request->session()->put([
            'pending_otp_id' => $otp->id,
            'pending_otp_user_id' => $user->id,
            'pending_otp_remember' => $remember,
            'pending_otp_fallback' => $user->role === 'organizer'
                ? route('organizer.dashboard')
                : route('events.index'),
        ]);

        return redirect()->route('otp.create')
            ->with('status', 'We have sent a verification code to your email address.');
    }


    /**
     * Destroy an authenticated session (Logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}