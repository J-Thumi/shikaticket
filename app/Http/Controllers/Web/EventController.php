<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        // Featured spotlight events (e.g., happening this week)
        $featuredEvents = Event::published()
            ->upcoming()
            ->with(['organizer', 'ticketTypes'])
            ->where('start_date', '<=', now()->addDays(7))
            ->orderBy('start_date', 'asc')
            ->take(8)
            ->get();

        // Main filtered event listing
        $query = Event::published()
            ->upcoming()
            ->with(['organizer', 'ticketTypes']);

        if ($request->filled('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('start_date', '<=', $request->to_date);
        }

        if ($request->filled('max_price')) {
            $query->whereHas('ticketTypes', function ($q) use ($request) {
                $q->where('is_active', true)->where('price', '<=', $request->max_price);
            });
        }

        $events = $query->orderBy('start_date', 'asc')
            ->paginate(12)
            ->withQueryString();

        return view('events.index', compact('events', 'featuredEvents'));
    }

    public function show(Event $event): View
    {
        $event->load(['organizer', 'ticketTypes' => function ($query) {
            $query->where('is_active', true);
        }]);

        return view('events.show', compact('event'));
    }
}