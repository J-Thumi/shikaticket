
@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4 py-12">
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-sm p-8">

        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 text-2xl mb-5">
                <i class="fa-solid fa-lock"></i>
            </div>

            <h1 class="text-2xl font-bold text-slate-900">Create a new password</h1>

            <p class="text-sm text-slate-500 mt-3 leading-6">
                Choose a strong password for your ShikaTicket account.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                    Email address
                </label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $email) }}"
                    required
                    autocomplete="email"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none"
                >
            </div>

            <div class="mb-5">
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                    New password
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none"
                >
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                    Confirm new password
                </label>

                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none"
                >
            </div>

            <button type="submit"
                class="w-full mt-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3.5 transition">
                Reset Password
            </button>
        </form>

    </div>
</div>
@endsection
