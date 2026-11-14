@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 space-y-6">
    @if(session('status'))<div class="rounded-xl bg-green-50 text-green-800 p-4">{{ session('status') }}</div>@endif

    <div class="bg-white border rounded-2xl overflow-hidden">
        @if($invitation->event->banner_url)
            <img src="{{ $invitation->event->banner_url }}" alt="{{ $invitation->event->title }}" class="w-full max-h-64 object-cover">
        @endif
        <div class="p-6 space-y-4">
            <p class="text-xs uppercase tracking-wide text-gray-500">Marketer invitation</p>
            <h1 class="text-2xl font-bold">{{ $invitation->event->title }}</h1>
            <p class="whitespace-pre-line text-gray-700">{{ $invitation->event->description }}</p>
            <div class="grid sm:grid-cols-2 gap-3 text-sm">
                <div><span class="text-gray-500">Venue</span><p class="font-semibold">{{ $invitation->event->venue_name ?? 'To be announced' }}</p></div>
                <div><span class="text-gray-500">Event date</span><p class="font-semibold">{{ $invitation->event->start_date ? \Illuminate\Support\Carbon::parse($invitation->event->start_date)->format('d M Y, H:i') : 'To be announced' }}</p></div>
                <div><span class="text-gray-500">Organizer</span><p class="font-semibold">{{ $invitation->organizer->name ?? 'Event organizer' }}</p></div>
                <div><span class="text-gray-500">Commission</span><p class="font-semibold">@if($invitation->commission_type === 'percent'){{ $invitation->commission_percent }}%@else KES {{ number_format((float) $invitation->fixed_commission, 2) }}@endif</p></div>
            </div>
        </div>
    </div>

    @auth
        @if($application)
            <div class="border rounded-xl p-5">
                <h2 class="font-bold">Your application</h2>
                <p class="mt-2">Status: <strong>{{ ucfirst($application->status) }}</strong></p>
                @if($application->status === 'pending')
                    <p class="text-sm text-gray-600 mt-1">The organizer must approve your application. You are not yet an official marketer and no referral link has been generated.</p>
                @elseif($application->status === 'approved')
                    <p class="text-sm text-green-700 mt-1">You have been approved as a marketer.</p>
                @else
                    <p class="text-sm text-gray-600 mt-1">This application was not approved.</p>
                @endif
            </div>
        @else
            <form method="POST" action="{{ route('marketer-invitations.apply', $token) }}" class="bg-white border rounded-2xl p-6 space-y-4">
                @csrf
                @if($invitation->agreement_enabled)
                    <div class="space-y-3">
                        <h2 class="text-lg font-bold">{{ $invitation->agreement_title }}</h2>
                        <div class="border rounded-xl p-4 max-h-96 overflow-y-auto whitespace-pre-line text-sm leading-relaxed">{{ $invitation->agreement_text }}</div>
                        <label class="flex items-start gap-2 text-sm">
                            <input type="checkbox" name="agreement_accepted" value="1" required class="mt-1">
                            <span>I have read and agree to the agreement displayed above.</span>
                        </label>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Type your full name as your electronic acknowledgement</label>
                            <input name="signature_name" value="{{ old('signature_name', auth()->user()->name) }}" required maxlength="180" class="w-full border rounded-lg p-3">
                        </div>
                        <p class="text-xs text-gray-500">Your acknowledgement, timestamp and application details will be recorded with this application.</p>
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-50 text-red-700 rounded p-3"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <button class="rounded-xl bg-black text-white px-5 py-3 font-semibold">Submit application for approval</button>
                <p class="text-xs text-gray-500">Submitting an application does not make you a marketer. The organizer must approve you first.</p>
            </form>
        @endif
    @else
        <div class="border rounded-xl p-5 space-y-3">
            <p>You must sign in to apply. After signing in, you will return to this invitation.</p>
            <a class="inline-block rounded-lg bg-black text-white px-4 py-2" href="{{ route('login', ['redirect' => request()->fullUrl()]) }}">Log in to continue</a>
            <a class="underline text-sm" href="{{ route('register', ['redirect' => request()->fullUrl()]) }}">Create an account</a>
        </div>
    @endauth
</div>
@endsection
