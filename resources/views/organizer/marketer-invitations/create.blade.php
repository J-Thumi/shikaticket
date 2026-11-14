@extends('layouts.organizer')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ url()->previous() }}" class="text-sm text-muted">← Back</a>
        <h1 class="text-2xl font-bold mt-2">Create marketer invitation</h1>
        <p class="text-sm text-muted mt-1">One link can be shared with many people. Each person applies separately and waits for your approval.</p>
        <p class="font-semibold mt-3">{{ $event->title }}</p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl bg-red-50 text-red-700 p-4">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('organizer.events.marketer-invitations.store', $event) }}" class="bg-white border rounded-2xl p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-2">Commission type</label>
            <select name="commission_type" id="commission_type" class="w-full border rounded-lg p-3" required>
                <option value="percent" @selected(old('commission_type', 'percent') === 'percent')>Percentage</option>
                <option value="fixed" @selected(old('commission_type') === 'fixed')>Fixed amount per qualifying sale/ticket (align this with your commission calculation)</option>
            </select>
        </div>

        <div id="percent_field">
            <label class="block text-sm font-semibold mb-2">Commission percentage (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="commission_percent" value="{{ old('commission_percent') }}" class="w-full border rounded-lg p-3">
        </div>

        <div id="fixed_field" class="hidden">
            <label class="block text-sm font-semibold mb-2">Fixed commission (KES)</label>
            <input type="number" step="0.01" min="0" name="fixed_commission" value="{{ old('fixed_commission') }}" class="w-full border rounded-lg p-3">
        </div>

        <div class="border-t pt-5 space-y-3">
            <label class="flex items-center gap-2 font-semibold">
                <input type="checkbox" name="agreement_enabled" value="1" id="agreement_enabled" @checked(old('agreement_enabled'))>
                Include an optional text agreement
            </label>
            <div id="agreement_fields" class="hidden space-y-3">
                <div>
                    <label class="block text-sm font-semibold mb-1">Agreement title</label>
                    <input name="agreement_title" value="{{ old('agreement_title', 'Marketer Agreement') }}" class="w-full border rounded-lg p-3">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Agreement text</label>
                    <textarea name="agreement_text" rows="14" maxlength="30000" class="w-full border rounded-lg p-3" placeholder="Paste or write the terms the marketer will review...">{{ old('agreement_text') }}</textarea>
                    <p class="text-xs text-muted mt-1">The text is displayed on the invitation page and saved as a snapshot when an applicant consents.</p>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Link expiry (optional)</label>
            <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full border rounded-lg p-3">
        </div>

        <button class="rounded-xl bg-black text-white px-5 py-3 font-semibold">Generate invitation link</button>
    </form>
</div>

<script>
(() => {
    const type = document.getElementById('commission_type');
    const percent = document.getElementById('percent_field');
    const fixed = document.getElementById('fixed_field');
    const enabled = document.getElementById('agreement_enabled');
    const agreement = document.getElementById('agreement_fields');
    function update() {
        percent.classList.toggle('hidden', type.value !== 'percent');
        fixed.classList.toggle('hidden', type.value !== 'fixed');
        agreement.classList.toggle('hidden', !enabled.checked);
    }
    type.addEventListener('change', update);
    enabled.addEventListener('change', update);
    update();
})();
</script>
@endsection
