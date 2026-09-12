<?php
namespace App\Http\Controllers\Web;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class TicketController extends Controller
{
    public function index()
    {
        // Fetch the authenticated user's tickets
        $tickets = auth()->user()->tickets;

        return view('tickets.index', compact('tickets'));
    }

    public function show($ticketId)
    {
        // Fetch the ticket by ID
        $ticket = auth()->user()->tickets()->findOrFail($ticketId);

        return view('tickets.show', compact('ticket'));
    }
}
