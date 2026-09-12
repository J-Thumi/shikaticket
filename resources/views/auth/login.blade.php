@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12">

    <div class="w-full max-w-md space-y-8 bg-white p-8 sm:p-10 rounded-2xl border border-line shadow-xl shadow-black/5">
        
        <!-- Header Section -->
        <div class="text-center space-y-2">
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-ink">
                Welcome back
            </h2>
            <p class="text-xs text-muted">
                Sign in to manage your tickets and event orders
            </p>
        </div>

        <!-- Session Status / Flash Message -->
        @if (session('status'))
            <div class="flex items-center gap-3 p-4 text-xs font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl">
                <svg class="w-4 h-4 flex-shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form class="mt-6 space-y-5" action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Email Field -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-bold text-ink">Email address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
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

            <!-- Password Field -->
            <div class="space-y-1.5" x-data="{ show: false }">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-xs font-bold text-ink">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-accent hover:underline transition-colors">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="current-password" required
                        placeholder="••••••••"
                        class="block w-full pl-10 pr-10 py-2.5 bg-paper border border-line rounded-xl text-ink placeholder-muted text-xs font-medium transition-all duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent @error('password') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                    
                    <!-- Password Visibility Toggle (Alpine.js) -->
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
                @error('password')
                    <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox"
                    class="h-4 w-4 rounded border-line text-accent focus:ring-accent accent-accent cursor-pointer">
                <label for="remember" class="ml-2.5 block text-xs font-medium text-muted cursor-pointer select-none">
                    Remember me on this device
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full flex justify-center items-center py-3.5 px-4 text-xs font-bold rounded-xl text-white bg-accent hover:bg-accent-dark shadow-sm hover:-translate-y-0.5 transition duration-150">
                    Sign in →
                </button>
            </div>
        </form>

        <!-- Footer Callout -->
        <p class="text-center text-xs text-muted">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-bold text-ink hover:text-accent underline transition-colors">
                Sign up here
            </a>
        </p>
    </div>
</div>
@endsection