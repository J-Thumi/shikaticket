@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ selectedType: null, quantity: 1 }">
        
        <!-- Breadcrumb / Back Link -->
        <div class="mb-6">
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-muted hover:text-ink transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to all events
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-start">
            
            <!-- Left: Event Meta & Banner Details -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Banner Image Container -->
                <div class="rounded-3xl overflow-hidden border border-line bg-slate-900 shadow-xl shadow-black/5 relative group">
                    <img src="{{ $event->banner_url ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&q=80' }}" 
                         alt="{{ $event->title }}"
                         class="w-full h-auto max-h-[500px] object-contain mx-auto block">
                </div>

                <!-- Event Details Header -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full bg-accent/10 border border-accent/20 text-accent text-[11px] font-extrabold uppercase tracking-wider">
                            {{ $event->organizer->name }}
                        </span>
                        <span class="text-xs font-semibold text-muted">• Organized Event</span>
                    </div>

                    <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-ink tracking-tight leading-tight">
                        {{ $event->title }}
                    </h1>
                    
                    <!-- Quick Info Badges -->
                    <div class="pt-2 flex flex-wrap gap-3 text-xs sm:text-sm font-semibold text-ink">
                        <div class="flex items-center gap-2.5 bg-white border border-line px-4 py-3 rounded-2xl shadow-sm">
                            <svg class="w-4 h-4 text-accent shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $event->start_date->format('D, M d, Y • h:i A') }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2.5 bg-white border border-line px-4 py-3 rounded-2xl shadow-sm">
                            <svg class="w-4 h-4 text-accent shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span>{{ $event->venue_name }}, {{$event->venue_address }}</span>
                        </div>
                    </div>
                </div>

                <!-- Event Description -->
                <div class="border-t border-line pt-8 space-y-3">
                    <h2 class="font-display text-xl font-extrabold text-ink">About this event</h2>
                    <div class="prose prose-sm text-muted max-w-none leading-relaxed whitespace-pre-line font-medium">
                        {{ $event->description }}
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Ticket Selector Box -->
            <div class="bg-white border border-line rounded-3xl p-6 lg:p-8 sticky top-24 shadow-xl shadow-black/5 space-y-6">
                <div>
                    <h2 class="font-display text-xl font-extrabold text-ink">Select Tickets</h2>
                    <p class="text-xs text-muted mt-1">Choose a ticket tier and select your quantity.</p>
                </div>
                
                <form action="{{ route('events.reserve', $event->id) }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ session()->getId() }}">
                    
                    <div class="space-y-3">
                        @foreach($event->ticketTypes as $type)
                            @php 
                                $available =$type->total_quantity - ($type->sold_quantity +$type->reserved_quantity);
                            @endphp
                            <label class="block cursor-pointer group">
                                <input type="radio" name="ticket_type_id" value="{{ $type->id }}" 
                                       @disabled($available <= 0)
                                       x-on:change="selectedType = {{ json_encode($type) }}; quantity = {{$type->min_per_order ?? 1 }}"
                                       class="peer sr-only">
                                
                                <div class="p-4 rounded-2xl border-2 border-line bg-paper peer-checked:border-accent peer-checked:bg-accent/5 peer-disabled:opacity-40 peer-disabled:cursor-not-allowed transition duration-200 hover:border-accent/40 relative">
                                    <div class="flex justify-between items-center gap-3">
                                        <div class="space-y-1">
                                            <div class="font-bold text-sm text-ink group-hover:text-accent transition">
                                                {{ $type->name }}
                                            </div>
                                            <div class="text-[11px] font-semibold">
                                                @if($available > 0)
                                                    <span class="text-emerald-600 font-bold flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        {{ $available }} available
                                                    </span>
                                                @else
                                                    <span class="text-rose-600 font-bold">Sold Out</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <div class="font-display text-base font-extrabold text-ink">
                                                @if($type->price == 0)
                                                    Free
                                                @else
                                                    KES {{ number_format($type->price, 0) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Quantity & Checkout Summary Container -->
                    <div class="pt-5 border-t border-line space-y-5" x-show="selectedType" x-cloak x-transition>
                        
                        <!-- Quantity Controls -->
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-ink block">Quantity</span>
                                <span class="text-[10px] text-muted block" x-text="`Min: ${selectedType.min_per_order ?? 1} | Max: ${selectedType.max_per_order ?? 10}`"></span>
                            </div>

                            <div class="flex items-center space-x-3 bg-paper rounded-xl p-1.5 border border-line">
                                <button type="button" 
                                        x-on:click="if(quantity > (selectedType.min_per_order ?? 1)) quantity--" 
                                        :class="{'opacity-40 cursor-not-allowed': quantity <= (selectedType.min_per_order ?? 1)}"
                                        class="w-8 h-8 flex items-center justify-center font-bold text-ink bg-white hover:bg-soft rounded-lg shadow-sm border border-line transition">
                                    -
                                </button>

                                <span class="w-6 text-center font-extrabold text-sm text-ink" x-text="quantity"></span>

                                <button type="button" 
                                        x-on:click="if(quantity < (selectedType.max_per_order ?? 10)) quantity++" 
                                        :class="{'opacity-40 cursor-not-allowed': quantity >= (selectedType.max_per_order ?? 10)}"
                                        class="w-8 h-8 flex items-center justify-center font-bold text-ink bg-white hover:bg-soft rounded-lg shadow-sm border border-line transition">
                                    +
                                </button>
                            </div>
                        </div>

                        <input type="hidden" name="quantity" :value="quantity">

                        <!-- Subtotal Calculation Preview -->
                        <div class="bg-paper p-4 rounded-2xl border border-line flex items-center justify-between text-xs">
                            <span class="font-bold text-muted">Total Amount</span>
                            <span class="font-display font-extrabold text-lg text-ink">
                                KES <span x-text="numberFormat(selectedType ? selectedType.price * quantity : 0)"></span>
                            </span>
                        </div>

                        <button type="submit" 
                                class="w-full py-4 bg-accent hover:bg-accent-dark text-white font-bold text-sm rounded-2xl transition duration-150 shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>Reserve & Checkout</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function numberFormat(number) {
            return new Intl.NumberFormat().format(number);
        }
    </script>
@endsection