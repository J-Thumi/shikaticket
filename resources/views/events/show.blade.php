@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ selectedType: null, quantity: 1 }">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- Left: Event Meta & Banner -->
            <div class="lg:col-span-2 space-y-8">
                <div class="rounded-2xl overflow-hidden border border-line bg-soft h-80 sm:h-96 shadow-sm">
                    <img src="{{ $event->banner_url ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&q=80' }}" class="w-full h-full object-cover">
                </div>

                <div>
                    <span class="text-xs font-extrabold text-accent uppercase tracking-wider">{{ $event->organizer->name }}</span>
                    <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-ink mt-1 tracking-tight leading-tight">{{ $event->title }}</h1>
                    
                    <div class="mt-5 flex flex-wrap gap-3 text-xs sm:text-sm font-semibold text-ink">
                        <div class="flex items-center gap-2 bg-white border border-line px-4 py-2 rounded-xl">
                            <span class="text-accent">◷</span>
                            <span>{{ $event->start_date->format('D, M d, Y @ h:i A') }}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white border border-line px-4 py-2 rounded-xl">
                            <span class="text-accent">⌖</span>
                            <span>{{ $event->venue_name }}, {{ $event->venue_address }}</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-line pt-6">
                    <h3 class="font-display text-xl font-bold text-ink mb-3">About event</h3>
                    <p class="text-sm text-muted whitespace-pre-line leading-relaxed">{{ $event->description }}</p>
                </div>
            </div>

            <!-- Right: Interactive Ticket Selector Card -->
            <div class="bg-white border border-line rounded-2xl p-6 h-fit sticky top-24 shadow-lg shadow-black/5">
                <h2 class="font-display text-xl font-bold text-ink mb-1">Select tickets</h2>
                <p class="text-xs text-muted mb-5">Choose your ticket tier and quantity below.</p>
                
                <form action="{{ route('events.reserve', $event->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ session()->getId() }}">
                    
                    <div class="space-y-3">
                        @foreach($event->ticketTypes as $type)
                            @php 
                                $available = $type->total_quantity - ($type->sold_quantity + $type->reserved_quantity);
                            @endphp
                            <label class="block cursor-pointer group">
                                <input type="radio" name="ticket_type_id" value="{{ $type->id }}" 
                                       @disabled($available <= 0)
                                       x-on:change="selectedType = {{ json_encode($type) }}; quantity = {{ $type->min_per_order ?? 1 }}"
                                       class="peer sr-only">
                                
                                <div class="p-4 rounded-xl border border-line bg-paper peer-checked:border-accent peer-checked:bg-accent/5 peer-disabled:opacity-40 transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-bold text-sm text-ink group-hover:text-accent transition">{{ $type->name }}</div>
                                            <div class="text-[11px] mt-1">
                                                @if($available > 0)
                                                    <span class="text-emerald-600 font-bold">{{ $available }} available</span>
                                                @else
                                                    <span class="text-rose-600 font-bold">Sold Out</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="font-display text-base font-extrabold text-ink">
                                            KES {{ number_format($type->price, 0) }}
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Quantity & Reserve Button Trigger -->
                    <div class="pt-4 border-t border-line space-y-4" x-show="selectedType" x-cloak>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-ink">Quantity</span>
                            <div class="flex items-center space-x-2 bg-soft rounded-lg p-1 border border-line">
                                <button type="button" x-on:click="if(quantity > selectedType.min_per_order) quantity--" class="w-7 h-7 flex items-center justify-center font-bold text-ink hover:bg-line rounded-md transition">-</button>
                                <span class="w-6 text-center font-bold text-xs text-ink" x-text="quantity"></span>
                                <button type="button" x-on:click="if(quantity < selectedType.max_per_order) quantity++" class="w-7 h-7 flex items-center justify-center font-bold text-ink hover:bg-line rounded-md transition">+</button>
                            </div>
                        </div>
                        <input type="hidden" name="quantity" :value="quantity">

                        <button type="submit" class="w-full py-3.5 bg-accent hover:bg-accent-dark text-white font-bold text-sm rounded-xl transition duration-150 shadow-sm hover:-translate-y-0.5">
                            Reserve & Checkout →
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection