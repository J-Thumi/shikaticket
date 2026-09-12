<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketReservation;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $organizer = auth()->user()->organizer;

        // Base query for organizer's events
        $eventsQuery = Event::where('organizer_id', $organizer->id);
        $eventIds = (clone $eventsQuery)->pluck('id');
        $eventsCount = $eventsQuery->count();

        // Orders query
        $paidOrders = Order::whereIn('event_id', $eventIds)
            ->where('status', 'paid');

        $totalRevenue = $paidOrders->sum('total_amount');
        $netRevenue = $totalRevenue * 0.90; // Accounting for 10% platform fee
        $avgOrderValue = $paidOrders->count() > 0 ? $totalRevenue / $paidOrders->count() : 0;

        // Ticket Capacities & Inventory Metrics
        $ticketTypes = TicketType::whereIn('event_id', $eventIds);
        $totalCapacity = $ticketTypes->sum('total_quantity');
        $totalTicketsSold = $ticketTypes->sum('sold_quantity');
        $capacityPercentage = $totalCapacity > 0 ? round(($totalTicketsSold / $totalCapacity) * 100, 1) : 0;
        $activeTiersCount = (clone $ticketTypes)->where('is_active', true)->count();

        // Active Checkout Holds
        $activeHoldsCount = TicketReservation::whereIn('ticket_type_id', $ticketTypes->pluck('id'))
            ->where('expires_at', '>', now())
            ->sum('quantity');

        // Gate Check-in Rate Metrics
        $totalIssuedTickets = Ticket::whereHas('order', function ($query) use ($eventIds) {
            $query->whereIn('event_id', $eventIds)->where('status', 'paid');
        })->count();

        $checkedInCount = Ticket::whereHas('order', function ($query) use ($eventIds) {
            $query->whereIn('event_id', $eventIds)->where('status', 'paid');
        })->where('status', 'used')->count();

        $checkInRate = $totalIssuedTickets > 0 ? round(($checkedInCount / $totalIssuedTickets) * 100, 1) : 0;

        // Top Performing Events Query
        $topEvents = Event::where('organizer_id', $organizer->id)
            ->withCount(['orders as tickets_sold_count' => function ($query) {
                $query->where('orders.status', 'paid')
                      ->join('tickets', 'orders.id', '=', 'tickets.order_id');
            }])
            ->withSum(['orders as total_revenue' => function ($query) {
                $query->where('status', 'paid');
            }], 'total_amount')
            ->having('total_revenue', '>', 0)
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // Recent Orders Query
        $recentOrders = (clone $paidOrders)
            ->with('event')
            ->latest()
            ->take(5)
            ->get();

        return view('organizer.dashboard', compact(
            'totalRevenue',
            'netRevenue',
            'totalTicketsSold',
            'totalCapacity',
            'capacityPercentage',
            'eventsCount',
            'activeHoldsCount',
            'avgOrderValue',
            'activeTiersCount',
            'checkedInCount',
            'checkInRate',
            'topEvents',
            'recentOrders'
        ));
    }
}