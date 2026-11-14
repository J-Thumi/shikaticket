@extends('layouts.organizer')

@section('content')
<div class="max-w-5xl mx-auto space-y-5">
    <a href="{{ route('organizer.marketer-invitations.show', $invitation) }}" class="text-sm underline">← Invitation</a>
    <h1 class="text-2xl font-bold">Applicants – {{ $invitation->event->title }}</h1>
    @if(session('status'))<div class="bg-green-50 text-green-800 rounded-xl p-3">{{ session('status') }}</div>@endif
    @include('organizer.marketer-invitations.partials.application-table')
</div>
@endsection
