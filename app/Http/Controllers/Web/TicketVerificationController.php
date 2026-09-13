<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketScan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketVerificationController extends Controller
{
    public function index(): View
    {
        return view('scanner.index');
    }

    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ticket_code' => 'required|string',
            'qr_hash' => 'required|string',
        ]);

        $calculatedHash = hash_hmac('sha256', $validated['ticket_code'], config('app.key'));
        if (! hash_equals($calculatedHash, $validated['qr_hash'])) {
            return response()->json(['result' => 'invalid', 'message' => 'INVALID / TAMPERED TICKET'], 400);
        }

        // Eager load event to inspect event ownership
        $ticket = Ticket::with(['order', 'ticketType.event'])
            ->where('ticket_code', $validated['ticket_code'])
            ->first();

        if (! $ticket) {
            return response()->json(['result' => 'invalid', 'message' => 'TICKET NOT FOUND'], 404);
        }

        // 1. Check if the authenticated user has an organizer profile
        $organizer = auth()->user()->organizer;

        // 2. Prevent scanning tickets from other organizers' events
        if (! $organizer || $ticket->ticketType->event->organizer_id !== $organizer->id) {
            return response()->json([
                'result' => 'invalid',
                'message' => 'UNAUTHORIZED: TICKET BELONGS TO ANOTHER EVENT',
            ], 403);
        }

        if ($ticket->status === 'used') {
            return response()->json([
                'result' => 'already_used',
                'message' => 'ALREADY SCANNED!',
                'attendee' => $ticket->order->customer_name,
            ], 409);
        }

        $ticket->update(['status' => 'used']);

        TicketScan::create([
            'ticket_id' => $ticket->id,
            'scanned_by_user_id' => auth()->id(),
            'scan_result' => 'granted',
            'device_info' => $request->userAgent(),
        ]);

        return response()->json([
            'result' => 'granted',
            'message' => 'ENTRY GRANTED',
            'attendee' => $ticket->order->customer_name,
            'tier' => $ticket->ticketType->name,
        ]);
    }
}