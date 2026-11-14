<div class="bg-white border rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr><th class="p-3">Applicant</th><th class="p-3">Applied</th><th class="p-3">Agreement</th><th class="p-3">Status</th><th class="p-3">Review</th></tr>
            </thead>
            <tbody>
            @forelse($applications as $application)
                <tr class="border-t align-top">
                    <td class="p-3">
                        <div class="font-semibold">{{ $application->user->name }}</div>
                        <div class="text-gray-500">{{ $application->user->email }}</div>
                    </td>
                    <td class="p-3">{{ $application->created_at->format('d M Y H:i') }}</td>
                    <td class="p-3">
                        @if($application->agreement_accepted)
                            Accepted by {{ $application->signature_name }}<br>
                            <span class="text-gray-500">{{ optional($application->agreement_accepted_at)->format('d M Y H:i') }}</span>
                        @else
                            No agreement attached
                        @endif
                    </td>
                    <td class="p-3">{{ ucfirst($application->status) }}</td>
                    <td class="p-3">
                        @if($application->status === 'pending')
                            <form method="POST" action="{{ route('organizer.marketer-invitations.applications.approve', [$invitation, $application]) }}" class="space-y-2 mb-2">
                                @csrf
                                <input name="organizer_note" placeholder="Optional note" class="border rounded p-2 w-full">
                                <button class="bg-green-700 text-white rounded px-3 py-2">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('organizer.marketer-invitations.applications.reject', [$invitation, $application]) }}" class="space-y-2">
                                @csrf
                                <input name="organizer_note" placeholder="Optional reason" class="border rounded p-2 w-full">
                                <button class="border border-red-300 text-red-700 rounded px-3 py-2">Reject</button>
                            </form>
                        @elseif($application->agreement_snapshot)
                            <details>
                                <summary class="cursor-pointer underline">View signed agreement</summary>
                                <p class="whitespace-pre-line text-xs mt-2">{{ $application->agreement_snapshot }}</p>
                                <p class="text-xs mt-2">Typed name: {{ $application->signature_name }}</p>
                                <p class="text-xs">Accepted: {{ optional($application->agreement_accepted_at)->format('d M Y H:i:s') }}</p>
                            </details>
                        @else
                            <span class="text-gray-500">Reviewed</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-5 text-center text-gray-500">No applications yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $applications->links() }}</div>
</div>
