
@extends('layouts.marketer')

@section('title', 'My Events')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="font-display text-2xl font-extrabold">
            My Events
        </h1>

        <p class="text-xs text-muted mt-2">
            Events assigned to your marketer account.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse($events as $event)

            @php
                $marketer = $event->marketer_profile;

                $isActive = $event->status === 'published'
                    && $event->end_date
                    && $event->end_date->isFuture();

                $referralUrl = $marketer
                    ? route('events.show', [
                        'event' => $event->slug,
                        'ref' => $marketer->referral_code,
                    ])
                    : null;
            @endphp

            <div class="bg-white border border-line rounded-2xl overflow-hidden">

                @if($event->banner_url)
                    <img src="{{ $event->banner_url }}"
                         class="w-full h-40 object-cover"
                         alt="{{ $event->title }}">
                @else
                    <div class="h-40 bg-soft"></div>
                @endif

                <div class="p-5 space-y-4">

                    <div class="flex items-start justify-between gap-2">

                        <div>
                            <h2 class="text-sm font-bold">
                                {{ $event->title }}
                            </h2>

                            <p class="text-[11px] text-muted mt-1">
                                {{ $event->venue_name }}
                            </p>
                        </div>

                        <span class="text-[9px] font-bold px-2 py-1 rounded-lg
                            {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $isActive ? 'Active' : ucfirst($event->status) }}
                        </span>

                    </div>

                    <p class="text-[11px] text-muted">
                        {{ $event->start_date?->format('M d, Y · h:i A') }}
                    </p>

                    <div class="grid grid-cols-3 gap-2 border-y border-line py-4">

                        <div>
                            <p class="text-[10px] text-muted">Tickets</p>
                            <p class="text-sm font-extrabold">
                                {{ $event->tickets_sold }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[10px] text-muted">Revenue</p>
                            <p class="text-sm font-extrabold">
                                {{ number_format($event->revenue, 0) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[10px] text-muted">Earnings</p>
                            <p class="text-sm font-extrabold text-emerald-600">
                                {{ number_format($event->commission_earned, 0) }}
                            </p>
                        </div>

                    </div>

                    @if($referralUrl)

                        <div class="space-y-2">

                            <p class="text-[10px] font-bold text-muted uppercase">
                                Your Referral Link
                            </p>

                            <div class="flex gap-2">

                                <input readonly
                                       value="{{ $referralUrl }}"
                                       class="min-w-0 w-full px-3 py-2 bg-paper border border-line rounded-lg text-[10px]">

                                <button type="button"
                                        onclick="navigator.clipboard.writeText(@js($referralUrl)).then(() => { this.innerText = 'Copied'; setTimeout(() => this.innerText = 'Copy', 1500) })"
                                        class="px-3 py-2 rounded-lg bg-accent text-white text-[10px] font-bold">
                                    Copy
                                </button>

                            </div>

                        </div>

                    @endif

                    <a href="{{ route('marketer.events.show', $event) }}"
                       class="block text-center py-2.5 rounded-xl bg-soft hover:bg-line text-xs font-bold">
                        View Performance →
                    </a>

                </div>

            </div>

        @empty

            <div class="col-span-full bg-white border border-line rounded-2xl p-12 text-center">

                <h3 class="font-bold text-sm">
                    No assigned events
                </h3>

                <p class="text-xs text-muted mt-2">
                    Your approved event assignments will appear here.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection
