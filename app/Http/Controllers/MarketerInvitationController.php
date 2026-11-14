<?php

namespace App\Http\Controllers;

use App\Models\MarketerApplication;
use App\Models\MarketerInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class MarketerInvitationController extends Controller
{
    public function show(Request $request, string $token)
    {
        $invitation = $this->findInvitation($token);

        abort_unless($invitation->isAvailable(), 410, 'This invitation link has expired or been revoked.');

        $invitation->load(['event', 'organizer']);

        $application = Auth::check()
            ? $invitation->applications()->where('user_id', Auth::id())->first()
            : null;

        return view('marketer-invitations.show', compact('invitation', 'token', 'application'));
    }

    public function apply(Request $request, string $token)
    {
        $invitation = $this->findInvitation($token);

        abort_unless($invitation->isAvailable(), 410, 'This invitation link has expired or been revoked.');

        $user = Auth::user();

        $key = 'marketer-application:' . $invitation->id . ':' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429, 'Too many attempts. Please try again later.');
        }
        RateLimiter::hit($key, 60);

        $existing = $invitation->applications()
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()->route('marketer-invitations.show', $token)
                ->with('status', 'You have already submitted an application for this invitation.');
        }

        $rules = [];
        if ($invitation->agreement_enabled) {
            $rules['agreement_accepted'] = ['required', 'accepted'];
            $rules['signature_name'] = ['required', 'string', 'max:180'];
        }
        $request->validate($rules);

        MarketerApplication::create([
            'marketer_invitation_id' => $invitation->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'agreement_title' => $invitation->agreement_enabled ? $invitation->agreement_title : null,
            'agreement_snapshot' => $invitation->agreement_enabled ? $invitation->agreement_text : null,
            'agreement_accepted' => $invitation->agreement_enabled ? true : false,
            'signature_name' => $invitation->agreement_enabled ? $request->input('signature_name') : null,
            'agreement_accepted_at' => $invitation->agreement_enabled ? now() : null,
            'signer_ip' => $invitation->agreement_enabled ? $request->ip() : null,
            'signer_user_agent' => $invitation->agreement_enabled ? substr((string) $request->userAgent(), 0, 2000) : null,
        ]);

        return redirect()->route('marketer-invitations.show', $token)
            ->with('status', 'Your application has been sent to the organizer for approval. You are not a marketer yet.');
    }

    private function findInvitation(string $token): MarketerInvitation
    {
        return MarketerInvitation::where('token_hash', hash('sha256', $token))->firstOrFail();
    }
}
