@extends('layouts.organizer')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="mb-8">
        <a
            href="{{ route('organizer.marketers.index') }}"
            class="inline-flex items-center gap-2 text-xs font-bold text-muted hover:text-ink mb-4"
        >
            ← Back to Marketers
        </a>

        <h1 class="font-display text-3xl font-extrabold">
            Add Marketer
        </h1>

        <p class="text-sm text-muted mt-1">
            Create a marketer who can promote your events using a
            unique referral link.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('organizer.marketers.store') }}"
        class="bg-white border border-line rounded-3xl p-6 sm:p-8 shadow-sm"
    >
        @csrf

        <div class="space-y-6">

            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-muted mb-2">
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                    placeholder="e.g. John Mwangi"
                >

                @error('name')
                    <p class="mt-1 text-xs text-rose-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-5">

                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-muted mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                        placeholder="john@example.com"
                    >
                </div>

                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-muted mb-2">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="w-full px-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                        placeholder="0712 345 678"
                    >
                </div>
                <div class="space-y-4">

                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-muted mb-2">
                        Commission Type
                    </label>

                    <div class="grid grid-cols-2 gap-3">

                        <label class="relative cursor-pointer">
                            <input
                                type="radio"
                                name="commission_type"
                                value="fixed"
                                class="peer sr-only"
                                {{ old('commission_type', 'fixed') === 'fixed' ? 'checked' : '' }}
                            >

                            <div class="rounded-xl border border-line bg-paper p-4 transition
                                        peer-checked:border-accent
                                        peer-checked:bg-accent/5
                                        hover:border-gray-300">

                                <div class="font-bold text-sm text-ink">
                                    Fixed amount
                                </div>

                                <div class="text-xs text-muted mt-1">
                                    e.g. KES 200 per ticket
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input
                                type="radio"
                                name="commission_type"
                                value="percent"
                                class="peer sr-only"
                                {{ old('commission_type') === 'percent' ? 'checked' : '' }}
                            >

                            <div class="rounded-xl border border-line bg-paper p-4 transition
                                        peer-checked:border-accent
                                        peer-checked:bg-accent/5
                                        hover:border-gray-300">

                                <div class="font-bold text-sm text-ink">
                                    Percentage
                                </div>

                                <div class="text-xs text-muted mt-1">
                                    e.g. 10% of each sale
                                </div>
                            </div>
                        </label>

                    </div>

                    @error('commission_type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Fixed commission --}}
                <div id="fixed-commission-field">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-muted mb-2">
                        Fixed Commission
                    </label>

                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-muted">
                            KES
                        </span>

                        <input
                            type="number"
                            name="fixed_commission"
                            value="{{ old('fixed_commission') }}"
                            min="0"
                            step="0.01"
                            class="w-full pl-14 pr-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                            placeholder="200"
                        >
                    </div>

                    @error('fixed_commission')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-1.5 text-xs text-muted">
                        Amount paid to the marketer for each ticket sold.
                    </p>
                </div>


                {{-- Percentage commission --}}
                <div id="percent-commission-field" class="hidden">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-muted mb-2">
                        Commission Percentage
                    </label>

                    <div class="relative">
                        <input
                            type="number"
                            name="commission_percent"
                            value="{{ old('commission_percent') }}"
                            min="0"
                            max="100"
                            step="0.01"
                            class="w-full px-4 pr-12 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                            placeholder="10"
                        >

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-muted">
                            %
                        </span>
                    </div>

                    @error('commission_percent')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-1.5 text-xs text-muted">
                        Percentage of the ticket sale paid to the marketer.
                    </p>
                </div>

            </div>

            </div>

            <div class="p-4 rounded-2xl bg-soft border border-line">

                <div class="flex gap-3">

                    <div class="text-accent text-lg">
                        ↗
                    </div>

                    <div>
                        <p class="text-sm font-bold text-ink">
                            Referral link
                        </p>

                        <p class="text-xs text-muted mt-1 leading-relaxed">
                            ShikaTicket will automatically generate a
                            unique referral code for this marketer.
                            You can assign the marketer to events and
                            generate their event-specific sales links.
                        </p>
                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-line">

                <a
                    href="{{ route('organizer.marketers.index') }}"
                    class="px-5 py-3 rounded-xl text-sm font-bold text-muted hover:bg-soft"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-5 py-3 rounded-xl bg-accent text-white text-sm font-bold hover:bg-accent-dark transition"
                >
                    Create Marketer
                </button>

            </div>

        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fixedField = document.getElementById('fixed-commission-field');
        const percentField = document.getElementById('percent-commission-field');
        const commissionTypes = document.querySelectorAll(
            'input[name="commission_type"]'
        );

        function updateCommissionFields() {
            const selected = document.querySelector(
                'input[name="commission_type"]:checked'
            )?.value;

            if (selected === 'percent') {
                fixedField.classList.add('hidden');
                percentField.classList.remove('hidden');
            } else {
                fixedField.classList.remove('hidden');
                percentField.classList.add('hidden');
            }
        }

        commissionTypes.forEach(input => {
            input.addEventListener('change', updateCommissionFields);
        });

        updateCommissionFields();
    });
</script>

@endsection