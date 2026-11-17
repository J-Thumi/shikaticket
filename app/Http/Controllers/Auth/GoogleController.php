<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
    public function redirect(Request $request): RedirectResponse
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
    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            $request->session()->forget('google_auth_intent');

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

        // Ensure Google provides a verified email.
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

        // 1. Find an account already linked to this Google account.
        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {

            // Ensure the linked account email matches.
            if (strtolower($user->email) !== $email) {
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Your Google account email does not match your linked account.',
                    ]);
            }

        } else {

            // 2. Check whether an ordinary account already exists.
            $user = User::where('email', $email)->first();

            if ($user) {

                // Do not link a Google account already associated
                // with a different Google identity.
                if ($user->google_id !== null) {
                    return redirect()->route('login')
                        ->withErrors([
                            'email' => 'This account is already linked to another Google account.',
                        ]);
                }

                // Link the verified Google identity.
                $user->forceFill([
                    'google_id' => $googleUser->getId(),
                    'google_avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();

            } else {

                // 3. New Google user: collect missing registration details.
                $request->session()->put('google_registration', [
                    'google_id' => $googleUser->getId(),
                    'name' => $googleUser->getName() ?: 'Google User',
                    'email' => $email,
                    'avatar' => $googleUser->getAvatar(),
                ]);

                return redirect()->route('google.complete');
            }
        }

        // 4. Google has verified ownership of the email.
        $user->forceFill([
            'email_verified_at' => $user->email_verified_at ?? now(),
            'google_avatar' => $googleUser->getAvatar(),
        ])->save();

        // 5. Authenticate immediately. No OTP required.
        return $this->authenticateUser($request, $user, false);
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
    public function store(Request $request): RedirectResponse
    {
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
            'terms' => ['required', 'accepted'],
        ], [
            'terms.accepted' => 'You must accept the terms of service and privacy policy to continue.',
        ]);

        // Prevent duplicate accounts.
        if (
            User::where('email', $googleData['email'])->exists() ||
            User::where('google_id', $googleData['google_id'])->exists()
        ) {
            $request->session()->forget('google_registration');

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'An account with this Google email already exists. Please sign in.',
                ]);
        }

        // Create a fully verified Google account with consent timestamp.
        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => $googleData['name'],
            'email' => $googleData['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make(Str::random(64)),
            'role' => $validated['role'],
            'google_id' => $googleData['google_id'],
            'google_avatar' => $googleData['avatar'],
            'email_verified_at' => now(),
            'terms_accepted_at' => now(),
        ]);

        $request->session()->forget('google_registration');

        // Authenticate immediately. No OTP required.
        return $this->authenticateUser($request, $user, true);
    }

    /**
     * Authenticate the Google user and redirect according to role.
     */
    private function authenticateUser(
        Request $request,
        User $user,
        bool $registration
    ): RedirectResponse {

        // Remove any unfinished normal-login OTP state.
        $request->session()->forget([
            'pending_otp_id',
            'pending_otp_user_id',
            'pending_otp_remember',
            'pending_otp_fallback',
            'google_auth_intent',
            'google_registration',
        ]);

        Auth::login($user, false);

        // Regenerate session ID after authentication.
        $request->session()->regenerate();

        // Preserve role-based navigation.
        if ($user->role === 'organizer') {
            $destination = $registration
                ? route('organizer.create')
                : route('organizer.dashboard');
        } else {
            $destination = route('events.index');
        }

        return redirect()->to($destination)
            ->with(
                'status',
                $registration
                    ? 'Your ShikaTicket account has been created successfully!'
                    : 'You have successfully signed in with Google.'
            );
    }
}
