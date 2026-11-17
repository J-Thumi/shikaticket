@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12">

    <div class="w-full max-w-md space-y-8 bg-white p-8 sm:p-10 rounded-2xl border border-line shadow-xl shadow-black/5">
        
        <!-- Header Section -->
        <div class="text-center space-y-2">
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-ink">
                Create an Account
            </h2>
            <p class="text-xs text-muted">
                Join <span class="font-bold text-ink">ShikaTicket</span> to discover events and secure digital tickets
            </p>
        </div>

        <form class="mt-8 space-y-5" action="{{ route('register') }}" method="POST">
            @csrf

            <!-- Role Selector (Segmented Radio Tiles) -->
            <div class="space-y-1.5" x-data="{ selectedRole: '{{ old('role', 'customer') }}' }">
                <label class="block text-xs font-bold text-ink">I want to register as</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex flex-col items-center justify-center p-3.5 border rounded-xl cursor-pointer transition-all duration-200 select-none"
                        :class="selectedRole === 'customer' 
                            ? 'bg-accent/5 border-accent text-accent shadow-sm' 
                            : 'bg-paper border-line text-muted hover:bg-soft hover:text-ink'">
                        <input type="radio" name="role" value="customer" class="sr-only" x-model="selectedRole">
                        <svg class="w-5 h-5 mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5z" />
                        </svg>
                        <span class="text-xs font-bold">Ticket Buyer</span>
                    </label>

                    <label class="relative flex flex-col items-center justify-center p-3.5 border rounded-xl cursor-pointer transition-all duration-200 select-none"
                        :class="selectedRole === 'organizer' 
                            ? 'bg-accent/5 border-accent text-accent shadow-sm' 
                            : 'bg-paper border-line text-muted hover:bg-soft hover:text-ink'">
                        <input type="radio" name="role" value="organizer" class="sr-only" x-model="selectedRole">
                        <svg class="w-5 h-5 mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span class="text-xs font-bold">Event Organizer</span>
                    </label>

                    <label class="relative flex flex-col items-center justify-center p-3.5 border rounded-xl cursor-pointer transition-all duration-200 select-none"
                        :class="selectedRole === 'marketer' 
                            ? 'bg-accent/5 border-accent text-accent shadow-sm' 
                            : 'bg-paper border-line text-muted hover:bg-soft hover:text-ink'">
                        <input type="radio" name="role" value="marketer" class="sr-only" x-model="selectedRole">
                        <svg class="w-5 h-5 mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span class="text-xs font-bold">Marketer</span>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Full Name -->
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-bold text-ink">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input id="name" name="name" type="text" autocomplete="name" required
                        value="{{ old('name') }}" placeholder="e.g. Jane Wambui"
                        class="block w-full pl-10 pr-3 py-2.5 bg-paper border border-line rounded-xl text-ink placeholder-muted text-xs font-medium transition-all duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent @error('name') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                </div>
                @error('name')
                    <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-bold text-ink">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        value="{{ old('email') }}" placeholder="jane@example.com"
                        class="block w-full pl-10 pr-3 py-2.5 bg-paper border border-line rounded-xl text-ink placeholder-muted text-xs font-medium transition-all duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent @error('email') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                </div>
                @error('email')
                    <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Phone Number (M-Pesa Prefix UI) -->
            <div class="space-y-1.5">
                <label for="phone" class="block text-xs font-bold text-ink">Phone Number (M-Pesa)</label>
                <div class="relative flex rounded-xl shadow-sm">
                    <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-line bg-soft text-muted text-xs font-bold">
                        +254
                    </span>
                    <input id="phone" name="phone" type="tel" autocomplete="tel" required
                        value="{{ old('phone') }}" placeholder="712345678"
                        class="block w-full pr-3 pl-3 py-2.5 bg-paper border border-line rounded-r-xl text-ink placeholder-muted text-xs font-medium transition-all duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent @error('phone') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                </div>
                @error('phone')
                    <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Passwords Row Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{ show: false }">
                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-bold text-ink">Password</label>
                    <div class="relative">
                        <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="new-password" required
                            placeholder="••••••••"
                            class="block w-full px-3 py-2.5 bg-paper border border-line rounded-xl text-ink placeholder-muted text-xs font-medium transition-all duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent @error('password') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                    </div>
                    @error('password')
                        <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-xs font-bold text-ink">Confirm Password</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" autocomplete="new-password" required
                            placeholder="••••••••"
                            class="block w-full pl-3 pr-10 py-2.5 bg-paper border border-line rounded-xl text-ink placeholder-muted text-xs font-medium transition-all duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
                        
                        <!-- Toggle Password Visibility -->
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-muted hover:text-ink focus:outline-none">
                            <svg class="h-4 w-4" x-show="!show" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="h-4 w-4" x-show="show" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.98 8.98 0 013.682-.787c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m-0.469 0a8.997 8.997 0 01-4.94 1.589" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Terms & Privacy Consent Checkbox -->
            <div class="space-y-1.5 pt-1">
                <div class="flex items-start gap-2.5">
                    <input
                        id="terms"
                        name="terms"
                        type="checkbox"
                        required
                        value="1"
                        {{ old('terms') ? 'checked' : '' }}
                        class="mt-0.5 h-4 w-4 rounded border-line text-accent focus:ring-accent">
                    
                    <label for="terms" class="text-xs text-muted leading-relaxed">
                        I agree to the <a href="{{ route('legal.terms') }}" target="_blank" class="text-accent font-bold hover:underline">Terms of Service</a> and <a href="{{ route('legal.privacy') }}" target="_blank" class="text-accent font-bold hover:underline">Privacy Policy</a>, and consent to the collection and processing of my data in accordance with Kenyan data protection laws.
                    </label>
                </div>

                @error('terms')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button type="submit"
                    class="w-full flex justify-center items-center py-3.5 px-4 text-xs font-bold rounded-xl text-white bg-accent hover:bg-accent-dark shadow-sm hover:-translate-y-0.5 transition duration-150">
                    Create Account →
                </button>
            </div>
        </form>


        <!-- Google Registration -->
        <div class="relative flex items-center">
            <div class="flex-grow border-t border-line"></div>
            <span class="px-3 text-xs text-muted">OR</span>
            <div class="flex-grow border-t border-line"></div>
        </div>

        <a href="{{ route('google.redirect', ['intent' => 'register']) }}"
        class="w-full flex items-center justify-center gap-3 py-3 px-4 bg-white border border-line rounded-xl text-xs font-bold text-ink hover:bg-paper hover:border-accent transition">

            <svg class="w-5 h-5" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 3.07 13.22l7.98 6.19C12.73 13.72 17.87 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.77 7.18l7.73 6C44.4 37.88 46.98 31.7 46.98 24.55z"/>
                <path fill="#FBBC05" d="M10.05 28.59A14.4 14.4 0 0 1 9.27 24c0-1.59.28-3.13.78-4.59l-7.98-6.19A23.92 23.92 0 0 0 0 24c0 3.86.92 7.52 2.55 10.78l7.5-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.9-5.8l-7.73-6c-2.15 1.45-4.9 2.3-8.17 2.3-6.13 0-11.27-4.22-12.95-9.91l-7.5 6.19C7.01 43.3 14.91 48 24 48z"/>
            </svg>

            Sign up with Google
        </a>


        <!-- Footer -->
        <p class="text-center text-xs text-muted">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-ink hover:text-accent underline transition-colors">
                Sign in here
            </a>
        </p>
    </div>
</div>
@endsection