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
    public function store(Request $request): RedirectResponse
    {
        Log::info('Registration request received from IP: ' . $request->ip());
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(['customer', 'organizer'])],
        ]);

        Log::info('New user registration attempt: ' . $validated['email'] . ' with role: ' . $validated['role']);

        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Auto-login user upon successful registration
        Auth::login($user);

        // Redirect based on selected role
        if ($user->role === 'organizer') {
            Log::info('New organizer registered: ' . $user->email);
            return redirect()->route('organizer.create')
                ->with('status', 'Account created! Please complete your organizer profile to continue.');
        }

        Log::info('New customer registered: ' . $user->email);

        return redirect()->route('events.index')
            ->with('status', 'Welcome to ShikaTicket! Your account has been created.');
    }
}