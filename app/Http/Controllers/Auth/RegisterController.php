<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    
    public function store(
        Request $request,
        \App\Services\EmailOtpService $otpService
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(['customer', 'organizer', 'marketer'])],
        ]);

        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        $otp = $otpService->send($user);

        $request->session()->put([
            'pending_otp_id' => $otp->id,
            'pending_otp_user_id' => $user->id,
            'pending_otp_remember' => false,
            'pending_otp_fallback' => $user->role === 'organizer'
                ? route('organizer.create')
                : route('events.index'),
        ]);

        return redirect()->route('otp.create')
            ->with('status', 'Your account has been created. Check your email for your verification code.');
    }

}