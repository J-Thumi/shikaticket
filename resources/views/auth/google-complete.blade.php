
@extends('layouts.app')

@section('content')

<div class="min-h-[85vh] flex flex-col justify-center items-center px-4 py-12">

    <div class="w-full max-w-md space-y-8 bg-white p-8 sm:p-10 rounded-2xl border border-line shadow-xl shadow-black/5">

        <div class="text-center space-y-2">
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-ink">
                Complete your account
            </h2>

            <p class="text-xs text-muted">
                Just a few more details to get started with ShikaTicket.
            </p>
        </div>

        <div class="p-4 rounded-xl bg-paper border border-line">
            <p class="text-xs font-bold text-ink">
                {{ $googleData['name'] }}
            </p>

            <p class="text-xs text-muted mt-1">
                {{ $googleData['email'] }}
            </p>
        </div>

        <form action="{{ route('google.complete.store') }}"
              method="POST"
              class="space-y-5">

            @csrf

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-ink">
                    I want to register as
                </label>

                <select name="role" required
                    class="block w-full px-3 py-3 bg-paper border border-line rounded-xl text-xs">

                    <option value="customer" {{ old('role', 'customer') === 'customer' ? 'selected' : '' }}>
                        Ticket Buyer
                    </option>

                    <option value="organizer" {{ old('role') === 'organizer' ? 'selected' : '' }}>
                        Event Organizer
                    </option>

                    <option value="marketer" {{ old('role') === 'marketer' ? 'selected' : '' }}>
                        Marketer
                    </option>

                </select>

                @error('role')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label for="phone" class="block text-xs font-bold text-ink">
                    Phone Number (M-Pesa)
                </label>

                <div class="flex">
                    <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-line bg-soft text-xs font-bold">
                        +254
                    </span>

                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        required
                        value="{{ old('phone') }}"
                        placeholder="712345678"
                        class="block w-full px-3 py-3 bg-paper border border-line rounded-r-xl text-xs">
                </div>

                @error('phone')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full py-3.5 px-4 text-xs font-bold rounded-xl text-white bg-accent hover:bg-accent-dark transition">

                Complete Registration →
            </button>

        </form>

    </div>

</div>

@endsection
