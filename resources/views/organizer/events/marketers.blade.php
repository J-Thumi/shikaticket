@extends('layouts.organizer')

@section('content')
<div class="space-y-6" x-data="{ copiedCode: null }">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-muted hover:text-ink transition">
                ← Back to Event Catalog
            </a>
            <div class="flex items-center gap-3">
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-ink leading-tight">
                    Marketers for {{ $event->title }}
                </h1>
                <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200">
                    {{ ucfirst($event->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Status Alert -->
    @if (session('status'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-800 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Attached Marketers List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-line rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-line">
                    <div>
                        <h2 class="font-display text-base font-extrabold text-ink">Assigned Marketers</h2>
                        <p class="text-xs text-muted font-medium">Marketers actively promoting this event and tracking conversions.</p>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-paper border border-line text-ink">
                        {{ $event->marketers->count() }} Total
                    </span>
                </div>

                @if ($event->marketers->isEmpty())
                    <div class="text-center py-12 bg-paper rounded-xl border border-line border-dashed space-y-2">
                        <svg class="w-10 h-10 mx-auto text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-xs font-bold text-ink">No Marketers Assigned</p>
                        <p class="text-[11px] text-muted max-w-xs mx-auto">Assign a marketer from your organization pool to generate custom referral links.</p>
                    </div>
                @else
                    <div class="divide-y divide-line">
                        @foreach ($event->marketers as $marketer)
                            @php
                                $referralUrl = $marketer->getReferralUrl($event);
                            @endphp
                            <div class="py-4 first:pt-0 last:pb-0 space-y-3">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h3 class="text-sm font-bold text-ink">{{ $marketer->name }}</h3>
                                        <p class="text-xs text-muted font-medium">{{ $marketer->email }} • {{ $marketer->phone ?? 'No phone' }}</p>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <div class="text-right">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-muted block">Orders</span>
                                            <span class="text-xs font-extrabold text-ink">{{ number_format($marketer->orders_count ?? 0) }}</span>
                                        </div>

                                        <form action="{{ route('organizer.events.marketers.destroy', ['event' => $event->id, 'marketer' => $marketer->id]) }}" method="POST" onsubmit="return confirm('Remove this marketer from the event?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-muted hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Remove Marketer">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Referral Code & Copy Box -->
                                <div class="flex items-center gap-2 p-2 rounded-xl bg-paper border border-line text-xs">
                                    <span class="font-mono font-bold text-accent px-2 py-0.5 rounded bg-white border border-line shrink-0">
                                        {{ $marketer->referral_code }}
                                    </span>
                                    <input type="text" readonly value="{{ $referralUrl }}" class="flex-1 bg-transparent border-0 text-[11px] text-muted font-mono focus:ring-0 truncate p-0">
                                    
                                    <button 
                                        type="button" 
                                        @click="navigator.clipboard.writeText('{{ $referralUrl }}'); copiedCode = '{{ $marketer->id }}'; setTimeout(() => copiedCode = null, 2000)"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold rounded-lg border border-line bg-white hover:bg-soft text-ink transition shrink-0"
                                    >
                                        <template x-if="copiedCode === '{{ $marketer->id }}'">
                                            <span class="text-emerald-700">Copied!</span>
                                        </template>
                                        <template x-if="copiedCode !== '{{ $marketer->id }}'">
                                            <span>Copy Link</span>
                                        </template>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Assign Marketer Form Panel -->
        <div class="space-y-6">
            <div class="bg-white border border-line rounded-2xl p-6 shadow-sm space-y-4">
                <div class="space-y-1">
                    <h3 class="font-display text-base font-extrabold text-ink">Add Marketer</h3>
                    <p class="text-xs text-muted font-medium">Select an existing marketer from your organization roster to link to this event.</p>
                </div>

                @if ($availableMarketers->isEmpty())
                    <div class="p-4 rounded-xl bg-paper border border-line text-xs text-muted text-center">
                        All active marketers in your organization are already attached to this event.
                    </div>
                @else
                    <form action="{{ route('organizer.events.marketers.store', $event->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="space-y-1.5">
                            <label for="marketer_id" class="block text-xs font-bold text-ink">Select Marketer</label>
                            <select 
                                name="marketer_id" 
                                id="marketer_id"
                                class="w-full px-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                                required
                            >
                                <option value="" disabled selected>Select from active pool...</option>
                                @foreach ($availableMarketers as $available)
                                    <option value="{{ $available->id }}">
                                        {{ $available->name }} ({{ $available->referral_code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('marketer_id')
                                <p class="text-[11px] font-bold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button 
                            type="submit" 
                            class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-sm"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Attach Marketer</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection