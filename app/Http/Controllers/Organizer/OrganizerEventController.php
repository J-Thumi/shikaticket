<?php
namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrganizerEventController extends Controller
{
    public function index()
    {
        $organizer = auth()->user()->organizer;

        $events = Event::where('organizer_id', $organizer->id)
            ->withCount('ticketTypes')
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        return view('organizer.events.create');
    }

    public function store(Request $request)
    {
        $organizer = auth()->user()->organizer;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'banner_url' => 'nullable|url',

            // Ticket Types Validation
            'tickets' => 'required|array|min:1',
            'tickets.*.name' => 'required|string|max:255',
            'tickets.*.price' => 'required|numeric|min:0',
            'tickets.*.total_quantity' => 'required|integer|min:1',
            'tickets.*.min_per_order' => 'nullable|integer|min:1',
            'tickets.*.max_per_order' => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $organizer) {
            $event = Event::create([
                'organizer_id' => $organizer->id,
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
                'venue_name' => $validated['venue_name'],
                'venue_address' => $validated['venue_address'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'description' => $validated['description'],
                'banner_url' => $validated['banner_url'] ?? 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=1200',
                'status' => 'published',
            ]);

            foreach ($validated['tickets'] as $ticketData) {
                $event->ticketTypes()->create([
                    'name' => $ticketData['name'],
                    'price' => $ticketData['price'],
                    'total_quantity' => $ticketData['total_quantity'],
                    'reserved_quantity' => 0,
                    'sold_quantity' => 0,
                    'min_per_order' => $ticketData['min_per_order'] ?? 1,
                    'max_per_order' => $ticketData['max_per_order'] ?? 10,
                    'is_active' => true,
                ]);
            }
        });

        return redirect()->route('organizer.events.index')
            ->with('status', 'Event and ticket types created successfully!');
    }

    public function show(Event $event): View
    {
        // Eager load tickets and orders relations
        $event->load(['ticketTypes', 'orders']);

        return view('organizer.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        $event->load('ticketTypes');
        return view('organizer.events.edit', compact('event'));
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('organizer.events.index')
            ->with('status', 'Event deleted successfully.');
    }
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:published,draft,cancelled',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'banner_url' => 'nullable|url',

            'tickets' => 'required|array|min:1',
            'tickets.*.id' => 'nullable|exists:ticket_types,id',
            'tickets.*.name' => 'required|string|max:255',
            'tickets.*.price' => 'required|numeric|min:0',
            'tickets.*.total_quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $event) {
            $event->update([
                'title' => $validated['title'],
                'status' => $validated['status'],
                'venue_name' => $validated['venue_name'],
                'venue_address' => $validated['venue_address'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'description' => $validated['description'],
                'banner_url' => $validated['banner_url'] ?? $event->banner_url,
            ]);

            foreach ($validated['tickets'] as $ticketData) {
                if (!empty($ticketData['id'])) {
                    $event->ticketTypes()->where('id', $ticketData['id'])->update([
                        'name' => $ticketData['name'],
                        'price' => $ticketData['price'],
                        'total_quantity' => $ticketData['total_quantity'],
                    ]);
                } else {
                    $event->ticketTypes()->create([
                        'name' => $ticketData['name'],
                        'price' => $ticketData['price'],
                        'total_quantity' => $ticketData['total_quantity'],
                        'reserved_quantity' => 0,
                        'sold_quantity' => 0,
                        'min_per_order' => 1,
                        'max_per_order' => 10,
                        'is_active' => true,
                    ]);
                }
            }
        });

        return redirect()->route('organizer.events.show', $event->id)
            ->with('status', 'Event details updated successfully!');
    }
}