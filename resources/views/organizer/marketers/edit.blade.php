@extends('layouts.organizer')

@section('content')
<div class="space-y-8 max-w-3xl mx-auto">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <a href="{{ route('organizer.marketers.show', $marketer) }}" class="inline-flex items-center gap-1 text-xs font-bold text-muted hover:text-ink transition">
                ← Back to Marketer Profile
            </a>
            <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-ink leading-tight">
                Edit Marketer Details
            </h1>
            <p class="text-xs text-muted font-medium">
                Update account information and referral status for {{ $marketer->name }}.
            </p>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white border border-line rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">

        <form action="{{ route('organizer.marketers.update', $marketer) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Referral Code Display (Read-only) -->
            <div class="p-4 rounded-2xl bg-paper border border-line space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-muted block">
                    Assigned Referral Code
                </span>
                <div class="flex items-center gap-2">
                    <code class="px-3 py-1 rounded-lg bg-white border border-line text-sm font-extrabold text-accent">
                        {{ $marketer->referral_code }}
                    </code>
                    <span class="text-[11px] text-muted">Unique tracking identifier (Generated automatically)</span>
                </div>
            </div>

            <!-- Full Name -->
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-bold text-ink">
                    Full Name <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $marketer->name) }}" 
                    class="w-full px-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                    placeholder="e.g. John Doe"
                    required
                >
                @error('name')
                    <p class="text-[11px] font-bold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Email Address -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-bold text-ink">
                    Email Address
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email', $marketer->email) }}" 
                    class="w-full px-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                    placeholder="marketer@example.com"
                >
                @error('email')
                    <p class="text-[11px] font-bold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Number -->
            <div class="space-y-1.5">
                <label for="phone" class="block text-xs font-bold text-ink">
                    Phone Number
                </label>
                <input 
                    type="text" 
                    name="phone" 
                    id="phone" 
                    value="{{ old('phone', $marketer->phone) }}" 
                    class="w-full px-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                    placeholder="e.g. +254 700 000 000"
                >
                @error('phone')
                    <p class="text-[11px] font-bold text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Commission Settings Section -->
        <div 
            class="space-y-5 pt-6 mt-6 border-t border-line" 
            x-data="{ commissionType: '{{ old('commission_type', $marketer->commission_type ?? 'fixed') }}' }"
        >
            <div>
                <h2 class="text-sm font-extrabold text-ink">
                    Commission Settings
                </h2>
                <p class="text-xs text-muted mt-0.5">
                    Choose how this marketer earns commission from ticket sales.
                </p>
            </div>

            <!-- Commission Type Selection -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                <!-- Fixed Commission Radio Card -->
                <label class="relative cursor-pointer select-none">
                    <input
                        type="radio"
                        name="commission_type"
                        value="fixed"
                        x-model="commissionType"
                        class="peer sr-only"
                    >
                    <div class="rounded-2xl border border-line bg-paper p-4 transition peer-checked:border-accent peer-checked:bg-accent/5 hover:border-line-dark">
                        <div class="flex items-start gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white border border-line text-accent font-extrabold text-xs shadow-sm">
                                KES
                            </div>
                            <div>
                                <p class="text-xs font-extrabold text-ink">
                                    Fixed Commission
                                </p>
                                <p class="text-[11px] text-muted mt-0.5">
                                    A flat amount for each ticket sold.
                                </p>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Percentage Commission Radio Card -->
                <label class="relative cursor-pointer select-none">
                    <input
                        type="radio"
                        name="commission_type"
                        value="percent"
                        x-model="commissionType"
                        class="peer sr-only"
                    >
                    <div class="rounded-2xl border border-line bg-paper p-4 transition peer-checked:border-accent peer-checked:bg-accent/5 hover:border-line-dark">
                        <div class="flex items-start gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white border border-line text-accent font-extrabold text-base shadow-sm">
                                %
                            </div>
                            <div>
                                <p class="text-xs font-extrabold text-ink">
                                    Percentage Commission
                                </p>
                                <p class="text-[11px] text-muted mt-0.5">
                                    A percentage of each ticket sale.
                                </p>
                            </div>
                        </div>
                    </div>
                </label>

            </div>

            @error('commission_type')
                <p class="text-[11px] font-bold text-rose-600">
                    {{ $message }}
                </p>
            @enderror

            <!-- Conditional Dynamic Fields -->
            <div class="pt-1">
                <!-- Fixed Commission Input -->
                <div x-show="commissionType === 'fixed'" x-cloak class="space-y-1.5">
                    <label for="fixed_commission" class="block text-xs font-bold text-ink">
                        Fixed Amount (per ticket)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-extrabold text-muted">
                            KES
                        </span>
                        <input
                            type="number"
                            name="fixed_commission"
                            id="fixed_commission"
                            value="{{ old('fixed_commission', $marketer->fixed_commission) }}"
                            min="0"
                            step="0.01"
                            class="w-full pl-14 pr-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                            placeholder="200.00"
                        >
                    </div>
                    <p class="text-[11px] text-muted">
                        Direct monetary value credited per unit order.
                    </p>
                    @error('fixed_commission')
                        <p class="text-[11px] font-bold text-rose-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Percentage Commission Input -->
                <div x-show="commissionType === 'percent'" x-cloak class="space-y-1.5">
                    <label for="commission_percent" class="block text-xs font-bold text-ink">
                        Commission Rate (%)
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            name="commission_percent"
                            id="commission_percent"
                            value="{{ old('commission_percent', $marketer->commission_percent) }}"
                            min="0"
                            max="100"
                            step="0.01"
                            class="w-full pl-14 pr-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                            placeholder="10.00"
                        >
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-extrabold text-muted">
                            %
                        </span>
                    </div>
                    <p class="text-[11px] text-muted">
                        Percentage share calculated from gross sales.
                    </p>
                    @error('commission_percent')
                        <p class="text-[11px] font-bold text-rose-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

            <!-- Active Status Toggle -->
            <div class="space-y-1.5 pt-2 border-t border-line">
                <label for="is_active" class="block text-xs font-bold text-ink">
                    Account Status
                </label>
                <select 
                    name="is_active" 
                    id="is_active" 
                    class="w-full px-4 py-3 rounded-xl border border-line bg-paper focus:border-accent focus:ring-2 focus:ring-accent/10 outline-none"
                >
                    <option value="1" {{ old('is_active', $marketer->is_active) ? 'selected' : '' }}>
                        Active (Can track new referral sales)
                    </option>
                    <option value="0" {{ !old('is_active', $marketer->is_active) ? 'selected' : '' }}>
                        Inactive (Disable referral tracking)
                    </option>
                </select>
                @error('is_active')
                    <p class="text-[11px] font-bold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-line">
                <a 
                    href="{{ route('organizer.marketers.show', $marketer) }}" 
                    class="px-5 py-2.5 rounded-xl border border-line bg-white hover:bg-soft text-ink text-xs font-bold transition"
                >
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 rounded-xl bg-accent hover:bg-accent-dark text-white text-xs font-bold transition shadow-sm"
                >
                    Save Changes
                </button>
            </div>

        </form>

    </div>

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