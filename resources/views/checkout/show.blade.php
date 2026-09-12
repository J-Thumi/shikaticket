@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4" x-data="checkoutHandler()">
    <!-- Event Summary Card -->
    <div class="bg-white border border-line rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex items-start justify-between border-b border-line pb-6">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-muted">Checkout Summary</span>
                <h1 class="font-display text-xl sm:text-2xl font-extrabold text-ink mt-0.5">
                    {{ $reservation->ticketType->event->title }}
                </h1>
                <p class="text-xs font-medium text-muted mt-1">
                    📍 {{ $reservation->ticketType->event->venue_name }}
                </p>
            </div>
            <div class="text-right">
                <span class="text-xs font-bold text-muted block">Total Due</span>
                <span class="font-display text-2xl font-extrabold text-ink">
                    KES {{ number_format($reservation->ticketType->price * $reservation->quantity, 0) }}
                </span>
            </div>
        </div>

        <div class="bg-paper p-4 rounded-xl border border-line flex items-center justify-between text-xs">
            <div>
                <p class="font-bold text-ink">{{ $reservation->quantity }}x {{ $reservation->ticketType->name }}</p>
                <p class="text-muted text-[11px]">KES {{ number_format($reservation->ticketType->price, 0) }} per ticket</p>
            </div>
            <span class="font-bold px-2.5 py-1 rounded-lg bg-white border border-line text-ink">
                Reserved
            </span>
        </div>

        <!-- Checkout Form -->
        <form @submit.prevent="submitCheckout" class="space-y-4">
            @csrf

            <div>
                <label for="customer_name" class="block text-xs font-bold text-ink mb-1">Full Name</label>
                <input type="text" id="customer_name" x-model="form.customer_name" required placeholder="John Doe"
                    class="block w-full bg-paper border border-line rounded-xl px-3.5 py-2.5 text-xs font-medium text-ink focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
            </div>

            <div>
                <label for="customer_email" class="block text-xs font-bold text-ink mb-1">Email Address</label>
                <input type="email" id="customer_email" x-model="form.customer_email" required placeholder="john@example.com"
                    class="block w-full bg-paper border border-line rounded-xl px-3.5 py-2.5 text-xs font-medium text-ink focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
            </div>

            <div>
                <label for="customer_phone" class="block text-xs font-bold text-ink mb-1">M-Pesa Phone Number</label>
                <input type="tel" id="customer_phone" x-model="form.customer_phone" required placeholder="0712345678 or 254712345678"
                    class="block w-full bg-paper border border-line rounded-xl px-3.5 py-2.5 text-xs font-medium text-ink focus:bg-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
            </div>

            <template x-if="errorMessage">
                <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-bold" x-text="errorMessage"></div>
            </template>

            <button type="submit" :disabled="loading" class="w-full py-3 px-4 rounded-xl bg-accent hover:bg-accent-dark text-white font-bold text-xs transition shadow-sm hover:-translate-y-0.5 disabled:opacity-50">
                <span x-show="!loading">Pay KES {{ number_format($reservation->ticketType->price * $reservation->quantity, 0) }} via M-Pesa</span>
                <span x-show="loading">Initiating STK Push...</span>
            </button>
        </form>
    </div>

    <!-- STK Push Processing Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink/60 backdrop-blur-sm">
        <div class="bg-white border border-line rounded-2xl p-6 sm:p-8 max-w-sm w-full text-center space-y-5 shadow-2xl">
            <div class="relative w-16 h-16 mx-auto flex items-center justify-center">
                <div class="absolute inset-0 rounded-full bg-emerald-500/20 animate-ping"></div>
                <div class="relative w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xl font-bold">
                    📱
                </div>
            </div>

            <div>
                <h3 class="font-display text-lg font-extrabold text-ink">Check Your Phone</h3>
                <p class="text-xs text-muted mt-1">An M-Pesa prompt has been sent to your phone. Enter your PIN to complete ticket purchase.</p>
            </div>

            <div class="bg-paper p-3 rounded-xl border border-line">
                <span class="text-[10px] uppercase font-bold text-muted block">Order Reference</span>
                <span class="font-mono text-xs font-bold text-ink" x-text="orderNumber"></span>
            </div>

            <div class="flex items-center justify-center gap-2 text-xs text-muted font-semibold">
                <svg class="w-4 h-4 animate-spin text-accent" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Awaiting payment confirmation...</span>
            </div>
        </div>
    </div>
</div>

<script>
function checkoutHandler() {
    return {
        loading: false,
        showModal: false,
        errorMessage: null,
        orderNumber: null,
        pollInterval: null,
        form: {
            customer_name: '{{ auth()->user()->name ?? "" }}',
            customer_email: '{{ auth()->user()->email ?? "" }}',
            customer_phone: '',
        },
        async submitCheckout() {
            this.loading = true;
            this.errorMessage = null;

            try {
                const res = await fetch('{{ route("checkout.process", $reservation->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await res.json();

                if (!res.ok || !data.success) {
                    this.errorMessage = data.message || 'Payment initiation failed.';
                    this.loading = false;
                    return;
                }

                this.orderNumber = data.order_number;
                this.showModal = true;
                this.pollStatus();

            } catch (err) {
                this.errorMessage = 'An unexpected error occurred. Please try again.';
                this.loading = false;
            }
        },
        pollStatus() {
            this.pollInterval = setInterval(async () => {
                try {
                    const res = await fetch(`/checkout/status/${this.orderNumber}`);
                    const data = await res.json();

                    if (data.is_paid) {
                        clearInterval(this.pollInterval);
                        window.location.href = `/checkout/success/${this.orderNumber}`;
                    } else if (data.status === 'failed') {
                        clearInterval(this.pollInterval);
                        this.showModal = false;
                        this.loading = false;
                        this.errorMessage = 'Payment failed or canceled on phone. Please try again.';
                    }
                } catch (e) {
                    console.error('Polling error', e);
                }
            }, 3000);
        }
    }
}
</script>
@endsection