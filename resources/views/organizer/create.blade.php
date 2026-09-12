@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="w-full max-w-lg space-y-8 bg-white p-8 sm:p-10 rounded-2xl border border-line shadow-xl shadow-black/5">
        
        <!-- Header Section -->
        <div class="text-center space-y-2">
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-ink">
                Setup Organizer Profile
            </h2>
            <p class="text-xs text-muted">
                Provide your brand details and payout preferences to start hosting events on <span class="font-bold text-ink">ShikaTicket</span>.
            </p>
        </div>

        <!-- Session Status / Flash Message -->
        @if(session('status'))
            <div class="flex items-center gap-3 p-4 text-xs font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl">
                <svg class="w-4 h-4 flex-shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form class="mt-8 space-y-5" action="{{ route('organizer.store') }}" method="POST">
            @csrf

            <!-- Organization / Brand Name -->
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-bold text-ink">Organization / Brand Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V10a2 2 0 012-2h2a2 2 0 012 2v11" />
                        </svg>
                    </div>
                    <input id="name" name="name" type="text" required
                        value="{{ old('name', auth()->user()->name) }}" placeholder="e.g. Acme Events Ltd"
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

            <!-- Logo URL -->
            <div class="space-y-1.5">
                <label for="logo_url" class="block text-xs font-bold text-ink">Logo Image URL <span class="text-muted font-normal">(Optional)</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input id="logo_url" name="logo_url" type="url"
                        value="{{ old('logo_url') }}" placeholder="https://example.com/logo.png"
                        class="block w-full pl-10 pr-3 py-2.5 bg-paper border border-line rounded-xl text-ink placeholder-muted text-xs font-medium transition-all duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent @error('logo_url') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                </div>
                @error('logo_url')
                    <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Payout Details Section -->
            <div class="border-t border-line pt-5 mt-6 space-y-4">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-xs font-bold text-ink uppercase tracking-wider">Payout Details</h3>
                </div>

                <!-- M-Pesa Payout Number -->
                <div class="space-y-1.5">
                    <label for="mpesa_number" class="block text-xs font-bold text-ink">M-Pesa Payout Number</label>
                    <div class="relative flex rounded-xl shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-line bg-soft text-muted text-xs font-bold">
                            +254
                        </span>
                        <input id="mpesa_number" name="mpesa_number" type="text"
                            value="{{ old('mpesa_number', auth()->user()->phone) }}" placeholder="712345678"
                            class="block w-full pr-3 pl-3 py-2.5 bg-paper border border-line rounded-r-xl text-ink placeholder-muted text-xs font-medium transition-all duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent @error('mpesa_number') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                    </div>
                    @error('mpesa_number')
                        <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Bank Account Details -->
                <div class="space-y-1.5">
                    <label for="bank_account" class="block text-xs font-bold text-ink">Bank Account Details <span class="text-muted font-normal">(Optional)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                        </div>
                        <input id="bank_account" name="bank_account" type="text"
                            value="{{ old('bank_account') }}" placeholder="e.g. NCBA Bank - 1234567890"
                            class="block w-full pl-10 pr-3 py-2.5 bg-paper border border-line rounded-xl text-ink placeholder-muted text-xs font-medium transition-all duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent @error('bank_account') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
                    </div>
                    @error('bank_account')
                        <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button type="submit"
                    class="w-full flex justify-center items-center py-3.5 px-4 text-xs font-bold rounded-xl text-white bg-accent hover:bg-accent-dark shadow-sm hover:-translate-y-0.5 transition duration-150">
                    Save & Complete Setup →
                </button>
            </div>
        </form>
    </div>
</div>
@endsection