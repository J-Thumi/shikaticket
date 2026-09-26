<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Marketer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventMarketerController extends Controller
{
    public function index(Event $event)
    {
        $this->authorizeEvent($event);

        $event->load([
            'marketers' => function ($query) {
                $query->withCount('orders');
            },
        ]);

        $availableMarketers = Auth::user()
            ->organizer
            ->marketers()
            ->where('is_active', true)
            ->whereNotIn(
                'id',
                $event->marketers->pluck('id')
            )
            ->orderBy('name')
            ->get();

        return view(
            'organizer.events.marketers',
            compact(
                'event',
                'availableMarketers'
            )
        );
    }

    public function store(
        Request $request,
        Event $event
    ) {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'marketer_id' => [
                'required',
                'exists:marketers,id',
            ],
        ]);

        $marketer = Auth::user()
            ->organizer
            ->marketers()
            ->where('id', $validated['marketer_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $event->marketers()->syncWithoutDetaching([
            $marketer->id,
        ]);

        return back()->with(
            'status',
            "{$marketer->name} was added to this event."
        );
    }

    public function destroy(
        Event $event,
        Marketer $marketer
    ) {
        $this->authorizeEvent($event);

        $event->marketers()->detach(
            $marketer->id
        );

        return back()->with(
            'status',
            "{$marketer->name} was removed from this event."
        );
    }

    private function authorizeEvent(Event $event): void
    {
        abort_unless(
            $event->organizer_id ===
                Auth::user()->organizer->id,
            403
        );
    }
}