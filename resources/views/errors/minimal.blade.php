@extends('layouts.app')

@section('title', $title ?? 'An Error Occurred - ShikaTicket')

@section('content')
<div class="min-h-[calc(100vh-20rem)] flex items-center justify-center px-4 py-16 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center space-y-6">
        
        <!-- Big Status Badge -->
        <div class="inline-flex items-center justify-center">
            <span class="font-display font-black text-7xl sm:text-8xl tracking-tight text-ink/10 select-none relative">
                @yield('code')
                <span class="absolute inset-0 flex items-center justify-center font-display font-extrabold text-4xl sm:text-5xl text-accent">
                    @yield('code')
                </span>
            </span>
        </div>

        <!-- Main Copy Block -->
        <div class="space-y-2">
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-ink tracking-tight">
                @yield('title')
            </h1>
            <p class="text-xs sm:text-sm font-medium text-muted max-w-sm mx-auto leading-relaxed">
                @yield('message')
            </p>
        </div>

        <!-- Action CTA Buttons -->
        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('events.index') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-line bg-white hover:bg-soft text-ink text-xs font-bold transition shadow-sm">
                <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
            </a>

            <a href="{{ route('events.index') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-accent text-white text-xs font-bold hover:bg-accent-dark transition shadow-sm hover:-translate-y-0.5">
                Back to Homepage
            </a>
        </div>

        <!-- Additional Assistance Note -->
        <div class="pt-6 border-t border-line/60">
            <p class="text-[11px] font-bold text-muted">
                Need help? <a href="#" class="text-accent hover:underline">Contact ShikaTicket Support</a>
            </p>
        </div>

    </div>
</div>
@endsection