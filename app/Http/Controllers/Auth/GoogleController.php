<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailOtpService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Redirect the user to Google.
     */
    public function redirect(Request $request)
    {
        $intent = $request->query('intent', 'login');

        if (!in_array($intent, ['login', 'register'], true)) {
            abort(400);
        }

        $request->session()->put('google_auth_intent', $intent);

        return Socialite::driver('google')
            ->scopes(['openid', 'email', 'profile'])
            ->redirect();
    }

    /**
     * Handle Google's callback.
     */
    public function callback(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {

        if ($request->has('error')) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Google authentication was cancelled or denied.',
                ]);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Unable to authenticate with Google. Please try again.',
                ]);
        }

        // Only accept verified Google email addresses.
        if (
            empty($googleUser->email) ||
            !filter_var($googleUser->email, FILTER_VALIDATE_EMAIL) ||
            !($googleUser->user['email_verified'] ?? false)
        ) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Google must provide a verified email address.',
                ]);
        }

        $email = strtolower($googleUser->email);

        // First check whether this Google account is already linked.
        $user = User::where('google_id', $googleUser->getId())
            ->first();

        if ($user) {

            // Do not silently change an existing account's email.
            if (strtolower($user->email) !== $email) {
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Your Google account email does not match your linked account.',
                    ]);
            }

        } else {

            // Check whether an ordinary ShikaTicket account
            // already exists with this verified email.
            $user = User::where('email', $email)->first();

            if ($user) {

                // Do not overwrite another Google association.
                if ($user->google_id !== null) {
                    return redirect()->route('login')
                        ->withErrors([
                            'email' => 'This account is already linked to another Google account.',
                        ]);
                }

                // Link Google to the existing account.
                $user->forceFill([
                    'google_id' => $googleUser->getId(),
                    'google_avatar' => $googleUser->getAvatar(),
                ])->save();

            } else {

                // New Google user: collect missing registration details.
                $request->session()->put('google_registration', [
                    'google_id' => $googleUser->getId(),
                    'name' => $googleUser->getName() ?: 'Google User',
                    'email' => $email,
                    'avatar' => $googleUser->getAvatar(),
                ]);

                return redirect()->route('google.complete');
            }
        }

        // Use the same OTP verification flow as normal login.
        return $this->sendOtp(
            $request,
            $user,
            $otpService,
            false
        );
    }

    /**
     * Show the remaining registration details form.
     */
    public function complete(Request $request)
    {
        $googleData = $request->session()->get('google_registration');

        if (!$googleData) {
            return redirect()->route('register');
        }

        return view('auth.google-complete', [
            'googleData' => $googleData,
        ]);
    }

    /**
     * Complete Google registration.
     */
    public function store(
        Request $request,
        EmailOtpService $otpService
    ): RedirectResponse {

        $googleData = $request->session()->get('google_registration');

        if (!$googleData) {
            return redirect()->route('register');
        }

        $validated = $request->validate([
            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone',
            ],

            'role' => [
                'required',
                Rule::in(['customer', 'organizer', 'marketer']),
            ],
        ]);

        // Check for a concurrent account creation.
        if (
            User::where('email', $googleData['email'])->exists() ||
            User::where('google_id', $googleData['google_id'])->exists()
        ) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'An account with this Google email already exists. Please sign in.',
                ]);
        }

        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => $googleData['name'],
            'email' => $googleData['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make(Str::random(64)),
            'role' => $validated['role'],
            'google_id' => $googleData['google_id'],
            'google_avatar' => $googleData['avatar'],
        ]);

        $request->session()->forget('google_registration');

        return $this->sendOtp(
            $request,
            $user,
            $otpService,
            true
        );
    }

    /**
     * Start the existing OTP verification process.
     */
    private function sendOtp(
        Request $request,
        User $user,
        EmailOtpService $otpService,
        bool $registration
    ): RedirectResponse {

        $otp = $otpService->send($user);

        $fallback = $registration
            ? ($user->role === 'organizer'
                ? route('organizer.create')
                : route('events.index'))
            : ($user->role === 'organizer'
                ? route('organizer.dashboard')
                : route('events.index'));

        $request->session()->put([
            'pending_otp_id' => $otp->id,
            'pending_otp_user_id' => $user->id,
            'pending_otp_remember' => false,
            'pending_otp_fallback' => $fallback,
        ]);

        return redirect()->route('otp.create')
            ->with(
                'status',
                'A verification code has been sent to your email address.'
            );
    }
}
