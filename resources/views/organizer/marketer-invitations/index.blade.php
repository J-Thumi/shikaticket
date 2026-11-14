@extends('layouts.organizer')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <a href="{{ route('organizer.events.show', $event->id) }}"
               class="text-xs font-bold text-muted hover:text-ink">
                ← Back to Event
            </a>

            <h1 class="font-display text-2xl font-extrabold text-ink">
                Invitation Links
            </h1>

            <p class="text-xs text-muted">
                Manage marketer invitations for {{ $event->title }}
            </p>
        </div>

        <a href="{{ route('organizer.events.marketer-invitations.create', $event) }}"
           class="px-4 py-2.5 rounded-xl bg-accent text-white text-xs font-bold">
            + Generate New Invitation
        </a>
    </div>

    {{-- Invitations --}}
    <div class="bg-white border border-line rounded-2xl shadow-sm overflow-hidden">

        <div class="p-5 border-b border-line flex justify-between items-center">
            <div>
                <h2 class="font-bold text-ink text-sm">Generated Invitations</h2>
                <p class="text-xs text-muted mt-1">
                    {{ $invitations->total() }} invitation links
                </p>
            </div>
        </div>

        @forelse($invitations as $invitation)
            <div class="p-5 border-b border-line last:border-0 space-y-4">

                {{-- Invitation header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-bold text-ink">
                                Invitation #{{ $invitation->id }}
                            </span>

                            @php
                                $statusStyles = [
                                    'pending' => 'bg-amber-50 text-amber-700',
                                    'accepted' => 'bg-emerald-50 text-emerald-700',
                                    'declined' => 'bg-rose-50 text-rose-700',
                                    'revoked' => 'bg-gray-100 text-gray-600',
                                    'expired' => 'bg-gray-100 text-gray-600',
                                ];

                                $status = $invitation->is_active ? 'Active' : 'Inactive';
                            @endphp

                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>

                        <p class="text-[11px] text-muted">
                            Created {{ $invitation->created_at->format('M d, Y · h:i A') }}
                        </p>

                        @if($invitation->expires_at)
                            <p class="text-[11px] text-muted">
                                Expires {{ $invitation->expires_at->format('M d, Y · h:i A') }}
                            </p>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap gap-2">

                        <a href="{{ route('organizer.marketer-invitations.show', $invitation) }}"
                           class="px-3 py-2 rounded-lg border border-line text-xs font-bold text-ink hover:bg-soft">
                            View Details
                        </a>

                        <a href="{{ route('organizer.marketer-invitations.applications', $invitation) }}"
                           class="px-3 py-2 rounded-lg border border-line text-xs font-bold text-ink hover:bg-soft">
                            Applications ({{ $invitation->applications_count }})
                        </a>

                        @if($invitation->share_url)
                            <button type="button"
                                    onclick="copyInvitationLink(@js($invitation->share_url), this)"
                                    class="px-3 py-2 rounded-lg bg-accent text-white text-xs font-bold">
                                Copy Link
                            </button>
                        @endif

                        @if($status === 'pending')
                            <form action="{{ route('organizer.marketer-invitations.revoke', $invitation) }}"
                                  method="POST"
                                  onsubmit="return confirm('Revoke this invitation?')">
                                @csrf

                                <button type="submit"
                                        class="px-3 py-2 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 text-xs font-bold">
                                    Revoke
                                </button>
                            </form>
                        @endif

                    </div>
                </div>

                {{-- Applicants --}}
                <div class="bg-paper rounded-xl p-4 space-y-3">

                    <div class="flex justify-between items-center">
                        <h3 class="text-xs font-bold text-ink">
                            People who used this invitation
                        </h3>

                        <span class="text-[10px] font-bold text-muted">
                            {{ $invitation->applications_count }} Applications
                        </span>
                    </div>

                    @forelse($invitation->applications as $application)
                        <div class="flex items-center justify-between gap-3 py-2 border-t border-line first:border-0">

                            <div class="min-w-0">
                                <p class="text-xs font-bold text-ink truncate">
                                    {{ $application->user->name ?? 'Applicant' }}
                                </p>

                                <p class="text-[11px] text-muted truncate">
                                    {{ $application->user->email ?? 'No email provided' }}
                                </p>

                                <p class="text-[10px] text-muted">
                                    Applied {{ $application->created_at->format('M d, Y') }}
                                </p>
                            </div>

                            <span class="text-[10px] font-bold px-2 py-1 rounded-lg bg-white border border-line">
                                {{ ucfirst($application->status ?? 'Submitted') }}
                            </span>

                        </div>
                    @empty
                        <p class="text-xs text-muted">
                            Nobody has applied through this invitation yet.
                        </p>
                    @endforelse

                    @if($invitation->applications_count > 0)
                        <a href="{{ route('organizer.marketer-invitations.applications', $invitation) }}"
                           class="inline-block text-xs font-bold text-accent hover:underline pt-2">
                            View all applications →
                        </a>
                    @endif

                </div>

            </div>
        @empty
            <div class="p-12 text-center space-y-3">
                <h3 class="font-bold text-ink text-sm">
                    No invitations generated yet
                </h3>

                <p class="text-xs text-muted">
                    Generate an invitation link to start recruiting marketers.
                </p>

                <a href="{{ route('organizer.events.marketer-invitations.create', $event) }}"
                   class="inline-block px-4 py-2.5 bg-accent text-white rounded-xl text-xs font-bold">
                    Generate First Invitation
                </a>
            </div>
        @endforelse

        <div class="p-5">
            {{ $invitations->links() }}
        </div>

    </div>
</div>

<script>
    function copyInvitationLink(url, button) {
        navigator.clipboard.writeText(url).then(() => {
            const original = button.innerText;

            button.innerText = 'Copied!';

            setTimeout(() => {
                button.innerText = original;
            }, 2000);
        }).catch(() => {
            alert('Unable to copy the invitation link.');
        });
    }
</script>
@endsection
