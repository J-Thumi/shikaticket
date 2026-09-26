<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Marketer;
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

        /*
        |--------------------------------------------------------------------------
        | Organizer Events
        |--------------------------------------------------------------------------
        */

        $eventsQuery = Event::where('organizer_id', $organizer->id);

        $eventIds = (clone $eventsQuery)->pluck('id');

        $eventsCount = $eventIds->count();

        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */

        $paidOrdersQuery = Order::whereIn('event_id', $eventIds)
            ->where('status', 'paid');

        $totalOrders = (clone $paidOrdersQuery)->count();

        $totalRevenue = (clone $paidOrdersQuery)->sum('total_amount');

        $avgOrderValue = $totalOrders > 0
            ? $totalRevenue / $totalOrders
            : 0;

        $totalCustomers = (clone $paidOrdersQuery)
        ->distinct('customer_email')
        ->count('customer_email');

        /*
        |--------------------------------------------------------------------------
        | Marketer Commissions
        |--------------------------------------------------------------------------
        */

        $commissionOrdersQuery = Order::whereIn('event_id', $eventIds)
            ->where('status', 'paid')
            ->whereNotNull('marketer_id');

        $totalCommission = (clone $commissionOrdersQuery)
            ->sum('commission_amount');

        $unpaidCommissionTotal = (clone $commissionOrdersQuery)
            ->where('is_commission_paid', false)
            ->sum('commission_amount');

        $paidCommissionTotal = (clone $commissionOrdersQuery)
            ->where('is_commission_paid', true)
            ->sum('commission_amount');

        /*
        |--------------------------------------------------------------------------
        | Marketers
        |--------------------------------------------------------------------------
        */

        $marketersQuery = Marketer::where('organizer_id', $organizer->id);

        $activeMarketersCount = (clone $marketersQuery)
            ->where('is_active', true)
            ->count();

        $totalMarketersCount = (clone $marketersQuery)->count();

        /*
        |--------------------------------------------------------------------------
        | Marketer Commission Breakdown (table data)
        |--------------------------------------------------------------------------
        */

        $unpaidMarketers = Marketer::query()
            ->where('organizer_id', $organizer->id)
            ->whereHas('orders', function ($query) use ($eventIds) {
                $query->whereIn('event_id', $eventIds)
                    ->where('status', 'paid')
                    ->where('is_commission_paid', false)
                    ->where('commission_amount', '>', 0);
            })
            ->withCount([
                'orders as paid_orders_count' => function ($query) use ($eventIds) {
                    $query->whereIn('event_id', $eventIds)
                        ->where('status', 'paid');
                },
            ])
            ->withSum([
                'orders as revenue_generated' => function ($query) use ($eventIds) {
                    $query->whereIn('event_id', $eventIds)
                        ->where('status', 'paid');
                },
            ], 'total_amount')
            ->withSum([
                'orders as unpaid_commission' => function ($query) use ($eventIds) {
                    $query->whereIn('event_id', $eventIds)
                        ->where('status', 'paid')
                        ->where('is_commission_paid', false)
                        ->where('commission_amount', '>', 0);
                },
            ], 'commission_amount')
            ->orderByDesc('unpaid_commission')
            ->get();

        // Count for the KPI card, NOT the collection itself
        $marketersWithUnpaidCommission = $unpaidMarketers->count();

        /*
        |--------------------------------------------------------------------------
        | Net Revenue
        |--------------------------------------------------------------------------
        |
        | Net revenue after platform fee and marketer commissions.
        |
        | Platform fee = 10%
        |
        */

        $platformFee = $totalRevenue * 0.10;

        $netRevenue = $totalRevenue
            - $platformFee
            - $totalCommission;

        /*
        |--------------------------------------------------------------------------
        | Ticket Capacity & Inventory
        |--------------------------------------------------------------------------
        */

        $ticketTypesQuery = TicketType::whereIn('event_id', $eventIds);

        $ticketTypeIds = (clone $ticketTypesQuery)->pluck('id');

        $totalCapacity = (clone $ticketTypesQuery)
            ->sum('total_quantity');

        $totalTicketsSold = (clone $ticketTypesQuery)
            ->sum('sold_quantity');

        $capacityPercentage = $totalCapacity > 0
            ? round(($totalTicketsSold / $totalCapacity) * 100, 1)
            : 0;

        $activeTiersCount = (clone $ticketTypesQuery)
            ->where('is_active', true)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Active Checkout Holds
        |--------------------------------------------------------------------------
        */

        $activeHoldsCount = TicketReservation::whereIn(
                'ticket_type_id',
                $ticketTypeIds
            )
            ->where('expires_at', '>', now())
            ->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | Check-In Metrics
        |--------------------------------------------------------------------------
        */

        $paidTicketQuery = Ticket::whereHas('order', function ($query) use ($eventIds) {
            $query->whereIn('event_id', $eventIds)
                ->where('status', 'paid');
        });

        $totalIssuedTickets = (clone $paidTicketQuery)->count();

        $checkedInCount = (clone $paidTicketQuery)
            ->where('status', 'used')
            ->count();

        $checkInRate = $totalIssuedTickets > 0
            ? round(($checkedInCount / $totalIssuedTickets) * 100, 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Top Performing Events
        |--------------------------------------------------------------------------
        */

        $topEvents = Event::where('organizer_id', $organizer->id)
            ->withSum([
                'orders as total_revenue' => function ($query) {
                    $query->where('status', 'paid');
                },
            ], 'total_amount')
            ->withCount([
                'orders as paid_orders_count' => function ($query) {
                    $query->where('status', 'paid');
                },
            ])
            ->withCount([
                'orders as tickets_sold_count' => function ($query) {
                    $query->where('orders.status', 'paid')
                        ->join(
                            'tickets',
                            'orders.id',
                            '=',
                            'tickets.order_id'
                        );
                },
            ])
            ->whereHas('orders', function ($query) {
                $query->where('status', 'paid');
            })
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Orders
        |--------------------------------------------------------------------------
        */

        $recentOrders = (clone $paidOrdersQuery)
            ->with([
                'event',
                'marketer',
            ])
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view('organizer.dashboard', compact(
            'totalRevenue',
            'netRevenue',
            'platformFee',
            'avgOrderValue',
            'totalOrders',
            'totalCustomers',

            'totalTicketsSold',
            'totalCapacity',
            'capacityPercentage',
            'activeHoldsCount',
            'activeTiersCount',

            'eventsCount',
            'topEvents',

            'totalIssuedTickets',
            'checkedInCount',
            'checkInRate',

            'activeMarketersCount',
            'totalMarketersCount',
            'totalCommission',
            'unpaidCommissionTotal',
            'paidCommissionTotal',
            'marketersWithUnpaidCommission',
            'unpaidMarketers',

            'recentOrders'
        ));
    }
}