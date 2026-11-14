
@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-slate-50">
    <div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 text-2xl mb-5">
                <i class="fa-solid fa-envelope"></i>
            </div>

            <h1 class="text-2xl font-bold text-slate-900">
                Verify your email
            </h1>

            <p class="text-sm text-slate-500 mt-3 leading-6">
                We've sent a six-digit verification code to your email address.
                Enter it below to continue to your ShikaTicket account.
            </p>
        </div>

        @if(session('status'))
            <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-3">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <label for="code" class="block text-sm font-semibold text-slate-700 mb-2">
                Verification code
            </label>

            <input
                id="code"
                name="code"
                type="text"
                inputmode="numeric"
                autocomplete="one-time-code"
                maxlength="6"
                pattern="[0-9]{6}"
                required
                autofocus
                placeholder="000000"
                class="w-full text-center text-2xl tracking-[0.6em] font-bold rounded-xl border border-slate-300 px-4 py-4 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
            >

            <button
                type="submit"
                class="w-full mt-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3.5 transition"
            >
                Verify and Continue
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-slate-500 mb-3">
                Didn't receive your code?
            </p>

            <form method="POST" action="{{ route('otp.resend') }}">
                @csrf

                <button type="submit"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Resend verification code
                </button>
            </form>
        </div>

        <div class="border-t border-slate-100 mt-7 pt-5 text-center">
            <a href="{{ route('login') }}"
               class="text-sm text-slate-500 hover:text-slate-900">
                Return to login
            </a>
        </div>

    </div>
</div>
@endsection
