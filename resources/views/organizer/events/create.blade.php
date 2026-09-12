@extends('layouts.organizer')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-ink">Create New Event</h1>
        <p class="text-xs text-muted mt-1">Configure event venue, timing schedules, and ticket pricing tiers.</p>
    </div>

    <form action="{{ route('organizer.events.store') }}" method="POST" class="bg-white border border-line p-6 sm:p-10 rounded-2xl space-y-6 shadow-xl shadow-black/5">
        @csrf

        <!-- Event Title -->
        <div class="space-y-1.5">
            <label for="title" class="block text-xs font-bold text-ink">Event Title</label>
            <input type="text" name="title" id="title" required value="{{ old('title') }}" placeholder="e.g. Summer Music Festival 2026"
                class="block w-full bg-paper border border-line rounded-xl px-3.5 py-2.5 text-ink text-xs font-medium placeholder-muted transition duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent @error('title') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 @enderror">
            @error('title') 
                <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $message }}
                </p> 
            @enderror
        </div>

        <!-- Venue Name & Address -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label for="venue_name" class="block text-xs font-bold text-ink">Venue Name</label>
                <input type="text" name="venue_name" id="venue_name" required value="{{ old('venue_name') }}" placeholder="e.g. Sarit Expo Centre"
                    class="block w-full bg-paper border border-line rounded-xl px-3.5 py-2.5 text-ink text-xs font-medium placeholder-muted transition duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
            </div>
            <div class="space-y-1.5">
                <label for="venue_address" class="block text-xs font-bold text-ink">Venue Address</label>
                <input type="text" name="venue_address" id="venue_address" required value="{{ old('venue_address') }}" placeholder="e.g. Westlands, Nairobi"
                    class="block w-full bg-paper border border-line rounded-xl px-3.5 py-2.5 text-ink text-xs font-medium placeholder-muted transition duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
            </div>
        </div>

        <!-- Schedule Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label for="start_date" class="block text-xs font-bold text-ink">Start Date & Time</label>
                <input type="datetime-local" name="start_date" id="start_date" required value="{{ old('start_date') }}"
                    class="block w-full bg-paper border border-line rounded-xl px-3.5 py-2.5 text-ink text-xs font-medium placeholder-muted transition duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
            </div>
            <div class="space-y-1.5">
                <label for="end_date" class="block text-xs font-bold text-ink">End Date & Time</label>
                <input type="datetime-local" name="end_date" id="end_date" required value="{{ old('end_date') }}"
                    class="block w-full bg-paper border border-line rounded-xl px-3.5 py-2.5 text-ink text-xs font-medium placeholder-muted transition duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
            </div>
        </div>

        <!-- Banner Image URL -->
        <div class="space-y-1.5">
            <label for="banner_url" class="block text-xs font-bold text-ink">Banner Image URL <span class="text-muted font-normal">(Optional)</span></label>
            <input type="url" name="banner_url" id="banner_url" value="{{ old('banner_url') }}" placeholder="https://images.unsplash.com/photo-1505373877841-8d25f7d46678"
                class="block w-full bg-paper border border-line rounded-xl px-3.5 py-2.5 text-ink text-xs font-medium placeholder-muted transition duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
        </div>

        <!-- Description -->
        <div class="space-y-1.5">
            <label for="description" class="block text-xs font-bold text-ink">Description</label>
            <textarea name="description" id="description" rows="4" required placeholder="Provide an overview of the event..."
                class="block w-full bg-paper border border-line rounded-xl px-3.5 py-2.5 text-ink text-xs font-medium placeholder-muted transition duration-200 focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">{{ old('description') }}</textarea>
        </div>

        <!-- Ticket Types Section -->
        <div class="border-t border-line pt-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-display text-sm font-extrabold text-ink">Ticket Categories</h3>
                    <p class="text-xs text-muted">Configure ticket tiers and maximum capacity.</p>
                </div>
                <button type="button" id="add-ticket" class="px-3 py-1.5 rounded-xl bg-accent/10 text-accent hover:bg-accent/20 text-xs font-bold border border-accent/20 transition">
                    + Add Ticket Tier
                </button>
            </div>

            @error('tickets')
                <p class="text-xs font-bold text-rose-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror

            <div id="tickets-wrapper" class="space-y-3">
                <div class="ticket-row grid grid-cols-1 md:grid-cols-12 gap-3 items-end bg-paper p-4 rounded-xl border border-line">
                    <div class="md:col-span-5 space-y-1">
                        <label class="block text-xs font-bold text-ink">Ticket Name</label>
                        <input type="text" name="tickets[0][name]" required placeholder="e.g. Early Bird / VIP"
                            class="block w-full bg-white border border-line rounded-lg px-3 py-2 text-xs font-medium text-ink placeholder-muted focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                    </div>
                    <div class="md:col-span-3 space-y-1">
                        <label class="block text-xs font-bold text-ink">Price (KES)</label>
                        <input type="number" name="tickets[0][price]" required min="0" step="0.01" placeholder="1000"
                            class="block w-full bg-white border border-line rounded-lg px-3 py-2 text-xs font-medium text-ink placeholder-muted focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                    </div>
                    <div class="md:col-span-3 space-y-1">
                        <label class="block text-xs font-bold text-ink">Total Quantity</label>
                        <input type="number" name="tickets[0][total_quantity]" required min="1" placeholder="100"
                            class="block w-full bg-white border border-line rounded-lg px-3 py-2 text-xs font-medium text-ink placeholder-muted focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                    </div>
                    <div class="md:col-span-1 flex justify-center pb-1">
                        <button type="button" class="remove-ticket text-rose-600 hover:text-rose-800 hidden text-base font-bold transition">&times;</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-line">
            <a href="{{ route('organizer.events.index') }}" class="px-4 py-3 rounded-xl border border-line text-muted font-bold text-xs hover:bg-soft hover:text-ink transition">
                Cancel
            </a>
            <button type="submit" class="px-5 py-3 rounded-xl bg-accent hover:bg-accent-dark text-white font-bold text-xs transition shadow-sm hover:-translate-y-0.5">
                Publish Event & Tickets →
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let ticketIndex = 1;
        const wrapper = document.getElementById('tickets-wrapper');
        const addButton = document.getElementById('add-ticket');

        addButton.addEventListener('click', function () {
            const newRow = document.createElement('div');
            newRow.className = 'ticket-row grid grid-cols-1 md:grid-cols-12 gap-3 items-end bg-paper p-4 rounded-xl border border-line';
            newRow.innerHTML = `
                <div class="md:col-span-5 space-y-1">
                    <label class="block text-xs font-bold text-ink">Ticket Name</label>
                    <input type="text" name="tickets[${ticketIndex}][name]" required placeholder="e.g. VIP"
                        class="block w-full bg-white border border-line rounded-lg px-3 py-2 text-xs font-medium text-ink placeholder-muted focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                </div>
                <div class="md:col-span-3 space-y-1">
                    <label class="block text-xs font-bold text-ink">Price (KES)</label>
                    <input type="number" name="tickets[${ticketIndex}][price]" required min="0" step="0.01" placeholder="2500"
                        class="block w-full bg-white border border-line rounded-lg px-3 py-2 text-xs font-medium text-ink placeholder-muted focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                </div>
                <div class="md:col-span-3 space-y-1">
                    <label class="block text-xs font-bold text-ink">Total Quantity</label>
                    <input type="number" name="tickets[${ticketIndex}][total_quantity]" required min="1" placeholder="50"
                        class="block w-full bg-white border border-line rounded-lg px-3 py-2 text-xs font-medium text-ink placeholder-muted focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                </div>
                <div class="md:col-span-1 flex justify-center pb-1">
                    <button type="button" class="remove-ticket text-rose-600 hover:text-rose-800 text-base font-bold transition">&times;</button>
                </div>
            `;
            wrapper.appendChild(newRow);
            ticketIndex++;
            updateRemoveButtons();
        });

        wrapper.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-ticket')) {
                e.target.closest('.ticket-row').remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            const rows = wrapper.querySelectorAll('.ticket-row');
            rows.forEach((row) => {
                const removeBtn = row.querySelector('.remove-ticket');
                if (rows.length > 1) {
                    removeBtn.classList.remove('hidden');
                } else {
                    removeBtn.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection