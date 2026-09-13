<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Display a listing of the attendee's purchased tickets.
     */
    public function index(): View
    {
        // Query tickets belonging to orders placed by the authenticated user
        $tickets = Ticket::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id())
                  ->where('status', 'paid');
        })
        ->with(['ticketType', 'order.event'])
        ->latest()
        ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Display a specific ticket with its QR code details.
     */
    public function show(Ticket $ticket): View
    {
        // Ensure the ticket belongs to an order owned by the authenticated user
        if ($ticket->order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->load(['ticketType', 'order.event']);

        // Generate the HMAC signature hash used by the scanner
        $qrHash = hash_hmac('sha256', $ticket->ticket_code, config('app.key'));
        
        // Payload expected by TicketVerificationController
        $qrPayload = json_encode([
            'code' => $ticket->ticket_code,
            'hash' => $qrHash,
        ]);

        return view('tickets.show', compact('ticket', 'qrPayload'));
    }
}