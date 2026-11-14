@extends('layouts.organizer')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
        <div>
            <a href="{{ route('organizer.events.index') }}" class="text-sm text-muted">← Events</a>
            <h1 class="text-2xl font-bold mt-2">Marketer invitation</h1>
            <p class="text-muted">{{ $invitation->event->title }}</p>
        </div>
        <form method="POST" action="{{ route('organizer.marketer-invitations.revoke', $invitation) }}" onsubmit="return confirm('Revoke this invitation link?')">
            @csrf
            <button class="border border-red-300 text-red-700 rounded-lg px-4 py-2">Revoke link</button>
        </form>
    </div>

    @if(session('status'))<div class="bg-green-50 text-green-800 rounded-xl p-3">{{ session('status') }}</div>@endif
    @if(session('invite_url'))
        <div class="border rounded-xl p-4 space-y-2">
            <p class="font-semibold">Share this link</p>
            <div class="flex gap-2">
                <input id="invite-url" readonly value="{{ session('invite_url') }}" class="min-w-0 flex-1 border rounded-lg p-2">
                <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('invite-url').value)" class="px-4 py-2 bg-black text-white rounded-lg">Copy</button>
            </div>
            <a class="text-green-700 underline" target="_blank" rel="noopener" href="https://wa.me/?text={{ urlencode('You are invited to apply as a marketer for '.$invitation->event->title.': '.session('invite_url')) }}">Share on WhatsApp</a>
        </div>
    @endif

    <div class="bg-white border rounded-xl p-5">
        <p><strong>Commission:</strong>
            @if($invitation->commission_type === 'percent')
                {{ $invitation->commission_percent }}%
            @else
                KES {{ number_format((float) $invitation->fixed_commission, 2) }}
            @endif
        </p>
        <p><strong>Link status:</strong> {{ $invitation->isAvailable() ? 'Active' : 'Inactive / expired' }}</p>
        <p><strong>Applications:</strong> {{ $invitation->applications()->count() }}</p>
        <a class="inline-block mt-3 underline" href="{{ route('organizer.marketer-invitations.applications', $invitation) }}">Review applications</a>
    </div>

    <h2 class="text-lg font-bold">Recent applicants</h2>
    @include('organizer.marketer-invitations.partials.application-table')
</div>
@endsection
