<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginOtp;
use App\Models\User;
use App\Services\EmailOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class EmailOtpController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('pending_otp_id')) {
            return redirect()->route('login');
        }

        return view('auth.verify-otp');
    }

    public function store(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $otpId = $request->session()->get('pending_otp_id');
        $userId = $request->session()->get('pending_otp_user_id');

        if (!$otpId || !$userId) {
            return redirect()->route('login')
                ->withErrors(['code' => 'Your verification session has expired. Please log in again.']);
        }

        $key = 'otp-verify:' . $userId . ':' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return back()->withErrors([
                'code' => 'Too many verification attempts. Please try again later.',
            ]);
        }

        $otp = LoginOtp::whereKey($otpId)
            ->where('user_id', $userId)
            ->first();

        if (!$otp) {
            return redirect()->route('login');
        }

        if (!$otpService->verify($otp, $request->input('code'))) {
            RateLimiter::hit($key, 300);

            return back()->withErrors([
                'code' => 'The verification code is invalid, expired, or has already been used.',
            ]);
        }

        RateLimiter::clear($key);

        $user = User::findOrFail($userId);

        // The OTP proves control of the email address.
        if (!$user->email_verified_at) {
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }

        $remember = $request->session()->pull('pending_otp_remember', false);

        Auth::login($user, $remember);

        // Remove temporary authentication state.
        $request->session()->forget([
            'pending_otp_id',
            'pending_otp_user_id',
            'pending_otp_remember',
        ]);

        $request->session()->regenerate();

        $fallback = $request->session()->pull('pending_otp_fallback')
            ?? ($user->role === 'organizer'
                ? route('organizer.create')
                : route('events.index'));

        return redirect()->intended($fallback)
            ->with('status', 'Your email has been verified. Welcome to ShikaTicket!');
    }

    public function resend(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {
        $userId = $request->session()->get('pending_otp_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        $key = 'otp-resend:' . $user->id . ':' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors([
                'code' => 'You have requested too many codes. Please wait before requesting another.',
            ]);
        }

        RateLimiter::hit($key, 300);

        $otp = $otpService->send($user);

        $request->session()->put('pending_otp_id', $otp->id);

        return back()->with(
            'status',
            'A new verification code has been sent to your email address.'
        );
    }
}
