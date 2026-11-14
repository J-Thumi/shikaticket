<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MarketerApplication;
use App\Models\MarketerInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class MarketerInvitationController extends Controller
{

    public function index(Event $event)
    {
        abort_unless(
            $event->organizer_id === auth()->user()->organizer->id,
            403
        );

        $invitations = $event->marketerInvitations()
            ->withCount('applications')
            ->with([
                'applications' => function ($query) {
                    $query->latest();
                }
            ])
            ->latest()
            ->paginate(15);

        return view(
            'organizer.marketer-invitations.index',
            compact('event', 'invitations')
        );
    }
    public function create(Event $event)
    {
        $organizer = Auth::user()->organizer;
        abort_unless($organizer && $event->organizer_id === $organizer->id, 403);

        return view('organizer.marketer-invitations.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $organizer = Auth::user()->organizer;
        abort_unless($organizer && $event->organizer_id === $organizer->id, 403);

        $validated = $request->validate([
            'commission_type' => ['required', 'in:fixed,percent'],
            'fixed_commission' => ['nullable', 'required_if:commission_type,fixed', 'numeric', 'min:0'],
            'commission_percent' => ['nullable', 'required_if:commission_type,percent', 'numeric', 'min:0', 'max:100'],
            'agreement_enabled' => ['nullable', 'boolean'],
            'agreement_title' => ['nullable', 'required_if:agreement_enabled,1', 'string', 'max:180'],
            'agreement_text' => ['nullable', 'required_if:agreement_enabled,1', 'string', 'max:30000'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        if ($validated['commission_type'] === 'fixed') {
            $validated['commission_percent'] = null;
        } else {
            $validated['fixed_commission'] = null;
        }

        $enabled = $request->boolean('agreement_enabled');

        $rawToken = Str::random(64);

        $invitation = MarketerInvitation::create([
            'organizer_id' => $organizer->id,
            'event_id' => $event->id,
            'created_by' => Auth::id(),

            // Hash for secure invitation lookup.
            'token_hash' => hash('sha256', $rawToken),

            // Encrypted copy for recovering the original invitation URL.
            'token_encrypted' => Crypt::encryptString($rawToken),

            'commission_type' => $validated['commission_type'],
            'fixed_commission' => $validated['fixed_commission'] ?? null,
            'commission_percent' => $validated['commission_percent'] ?? null,

            'agreement_enabled' => $enabled,
            'agreement_title' => $enabled
                ? ($validated['agreement_title'] ?? 'Marketer Agreement')
                : null,
            'agreement_text' => $enabled
                ? ($validated['agreement_text'] ?? null)
                : null,

            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => true,
        ]);

        return redirect()
            ->route('organizer.marketer-invitations.show', $invitation)
            ->with('invite_url', $invitation->share_url)
            ->with('status', 'Invitation link created. Copy and share this link.');
    }

    public function show(MarketerInvitation $invitation)
    {
        $this->authorizeInvitation($invitation);

        $invitation->load('event');

        $applications = $invitation->applications()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('organizer.marketer-invitations.show', compact('invitation', 'applications'));
    }

    public function applications(MarketerInvitation $invitation)
    {
        $this->authorizeInvitation($invitation);

        $applications = $invitation->applications()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('organizer.marketer-invitations.applications', compact('invitation', 'applications'));
    }

    
    public function approve(
        Request $request,
        MarketerInvitation $invitation,
        MarketerApplication $application
    ) {
        $this->authorizeInvitation($invitation);

        abort_unless(
            $application->marketer_invitation_id === $invitation->id,
            404
        );

        $validated = $request->validate([
            'organizer_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $result = DB::transaction(function () use (
            $invitation,
            $application,
            $validated
        ) {
            $locked = MarketerApplication::query()
                ->whereKey($application->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $locked->status === 'pending',
                422,
                'This application has already been reviewed.'
            );

            $user = $locked->user;
            $organizer = $invitation->organizer;

            // Reuse an existing marketer profile for this organizer.
            $marketer = $organizer->marketers()
                ->where('user_id', $user->id)
                ->first();

            if (!$marketer) {
                $marketer = $organizer->marketers()->create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? null,
                    'commission_type' => $invitation->commission_type,
                    'fixed_commission' => $invitation->fixed_commission,
                    'commission_percent' => $invitation->commission_percent,
                    'referral_code' => $this->generateReferralCode(),
                    'is_active' => true,
                ]);
            } else {
                $marketer->update(['is_active' => true]);
            }

            // Assign the marketer to the event.
            $marketer->events()->syncWithoutDetaching([
                $invitation->event_id,
            ]);

            $locked->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'organizer_note' => $validated['organizer_note'] ?? null,
                'marketer_id' => $marketer->id,
            ]);

            return [
                'user' => $user,
                'marketer' => $marketer,
                'event' => $invitation->event,
            ];
        });

        // Send only after the approval transaction has committed.
        \Illuminate\Support\Facades\Mail::to($result['user']->email)
            ->queue(new \App\Mail\MarketerApprovedMail(
                user: $result['user'],
                marketer: $result['marketer'],
                event: $result['event'],
                invitation: $invitation,
                organizerNote: $validated['organizer_note'] ?? null,
            ));

        return back()->with(
            'status',
            'Applicant approved. Their marketer profile and referral code are now active.'
        );
    }


    public function reject(Request $request, MarketerInvitation $invitation, MarketerApplication $application)
    {
        $this->authorizeInvitation($invitation);
        abort_unless($application->marketer_invitation_id === $invitation->id, 404);

        $request->validate([
            'organizer_note' => ['nullable', 'string', 'max:2000'],
        ]);

        abort_unless($application->status === 'pending', 422, 'This application has already been reviewed.');

        $application->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'organizer_note' => $request->input('organizer_note'),
        ]);

        return back()->with('status', 'Application rejected.');
    }

    public function revoke(MarketerInvitation $invitation)
    {
        $this->authorizeInvitation($invitation);

        $invitation->update(['is_active' => false]);

        return back()->with('status', 'Invitation link revoked. Existing applications remain available for review.');
    }

    private function authorizeInvitation(MarketerInvitation $invitation): void
    {
        $organizer = Auth::user()->organizer;
        abort_unless($organizer && $invitation->organizer_id === $organizer->id, 403);
    }

    private function generateReferralCode(): string
    {
        do {
            $code = strtoupper('ST-' . Str::random(7));
        } while (\App\Models\Marketer::where('referral_code', $code)->exists());

        return $code;
    }
}
