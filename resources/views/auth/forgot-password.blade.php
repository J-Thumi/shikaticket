
@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4 py-12">
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-sm p-8">

        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 text-2xl mb-5">
                <i class="fa-solid fa-key"></i>
            </div>

            <h1 class="text-2xl font-bold text-slate-900">Forgot your password?</h1>

            <p class="text-sm text-slate-500 mt-3 leading-6">
                Enter the email address associated with your ShikaTicket account.
                We'll send you a link to reset your password.
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

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                Email address
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                placeholder="you@example.com"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
            >

            <button type="submit"
                class="w-full mt-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3.5 transition">
                Send Password Reset Link
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}"
               class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                Back to login
            </a>
        </div>

    </div>
</div>
@endsection
