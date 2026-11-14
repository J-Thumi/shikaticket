<?php

namespace App\Http\Controllers\Marketer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Marketer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MarketerDashboardController extends Controller
{
    /**
     * Get all marketer profiles belonging to the authenticated user.
     */
    private function getMarketers(): Collection
    {
        $marketers = Marketer::query()
            ->where('user_id', Auth::id())
            ->get();

        abort_if(
            $marketers->isEmpty(),
            403,
            'You do not have an active marketer account.'
        );

        return $marketers;
    }

    /**
     * Get events assigned to the user's marketer profiles.
     */
    private function getAssignedEvents(Collection $marketers): Collection
    {
        $ids = $marketers->pluck('id');

        return Event::query()
            ->whereHas('marketers', function ($query) use ($ids) {
                $query->whereIn('marketers.id', $ids);
            })
            ->with([
                'marketers' => function ($query) use ($ids) {
                    $query->whereIn('marketers.id', $ids);
                },
                'ticketTypes',
            ])
            ->orderBy('start_date')
            ->get();
    }

    /**
     * Get paid orders attributed to these marketers.
     */
    private function paidOrders(Collection $marketerIds)
    {
        return Order::query()
            ->whereIn('marketer_id', $marketerIds)
            ->where('status', 'paid')
            ->with(['event', 'marketer'])
            ->withCount('tickets');
    }

    /**
     * Calculate commission totals using the existing Marketer method.
     */
    private function commissionTotals(Collection $orders): array
    {
        $earned = $orders->sum(function ($order) {
            return $order->marketer
                ? $order->marketer->calculateCommission(
                    (float) $order->total_amount
                )
                : 0;
        });

        $paid = $orders
            ->filter(fn ($order) => (bool) $order->is_commission_paid)
            ->sum(function ($order) {
                return $order->marketer
                    ? $order->marketer->calculateCommission(
                        (float) $order->total_amount
                    )
                    : 0;
            });

        return [
            'earned' => $earned,
            'paid' => $paid,
            'pending' => $earned - $paid,
        ];
    }

    /**
     * Attach sales statistics to each event.
     */
    private function attachEventPerformance(
        Collection $events,
        Collection $orders
    ): Collection {
        return $events->map(function ($event) use ($orders) {

            $eventMarketerIds = $event->marketers->pluck('id');

            $eventOrders = $orders
                ->where('event_id', $event->id)
                ->whereIn('marketer_id', $eventMarketerIds);

            $event->marketer_profile = $event->marketers->first();
            $event->tickets_sold = $eventOrders->sum('tickets_count');
            $event->orders_count = $eventOrders->count();

            $event->revenue = $eventOrders->sum('total_amount');

            $event->commission_earned = $eventOrders->sum(
                fn ($order) => $order->marketer
                    ? $order->marketer->calculateCommission(
                        (float) $order->total_amount
                    )
                    : 0
            );

            return $event;
        });
    }

    /**
     * Dashboard overview.
     */
    public function index()
    {
        $marketers = $this->getMarketers();

        $marketerIds = $marketers->pluck('id');

        $orders = $this->paidOrders($marketerIds)->get();

        $commissions = $this->commissionTotals($orders);

        $events = $this->getAssignedEvents($marketers);

        $events = $this->attachEventPerformance($events, $orders);

        $activeEvents = $events->filter(function ($event) {
            return $event->status === 'published'
                && $event->end_date
                && $event->end_date->isFuture();
        });

        $ticketsSold = $orders->sum('tickets_count');

        $totalRevenue = $orders->sum('total_amount');

        $recentOrders = $orders
            ->sortByDesc('created_at')
            ->take(8);

        return view('marketer.dashboard', compact(
            'marketers',
            'events',
            'activeEvents',
            'orders',
            'commissions',
            'ticketsSold',
            'totalRevenue',
            'recentOrders'
        ));
    }

    /**
     * List all assigned events.
     */
    public function events()
    {
        $marketers = $this->getMarketers();

        $orders = $this->paidOrders(
            $marketers->pluck('id')
        )->get();

        $events = $this->getAssignedEvents($marketers);

        $events = $this->attachEventPerformance($events, $orders);

        return view('marketer.events.index', compact(
            'events',
            'marketers'
        ));
    }

    /**
     * Individual event performance.
     */
    public function showEvent(Event $event)
    {
        $marketers = $this->getMarketers();

        $assignedMarketers = $event->marketers()
            ->whereIn('marketers.id', $marketers->pluck('id'))
            ->get();

        abort_if($assignedMarketers->isEmpty(), 404);

        $marketer = $assignedMarketers->first();

        $allOrders = $this->paidOrders(
            collect([$marketer->id])
        )
            ->where('event_id', $event->id)
            ->get();

        $commissions = $this->commissionTotals($allOrders);

        $ticketsSold = $allOrders->sum('tickets_count');

        $totalRevenue = $allOrders->sum('total_amount');

        $orders = $this->paidOrders(
            collect([$marketer->id])
        )
            ->where('event_id', $event->id)
            ->latest()
            ->paginate(15);

        return view('marketer.events.show', compact(
            'event',
            'marketer',
            'orders',
            'allOrders',
            'commissions',
            'ticketsSold',
            'totalRevenue'
        ));
    }

    /**
     * Sales history.
     */
    public function sales()
    {
        $marketers = $this->getMarketers();

        $marketerIds = $marketers->pluck('id');

        $allOrders = $this->paidOrders($marketerIds)->get();

        $commissions = $this->commissionTotals($allOrders);

        $orders = $this->paidOrders($marketerIds)
            ->latest()
            ->paginate(20);

        return view('marketer.sales.index', compact(
            'orders',
            'allOrders',
            'commissions'
        ));
    }

    /**
     * Commission history.
     */
    public function commissions(Request $request)
    {
        $marketers = $this->getMarketers();

        $marketerIds = $marketers->pluck('id');

        $allOrders = $this->paidOrders($marketerIds)->get();

        $totals = $this->commissionTotals($allOrders);

        $status = $request->query('status', 'all');

        abort_unless(
            in_array($status, ['all', 'pending', 'paid'], true),
            404
        );

        $query = $this->paidOrders($marketerIds);

        if ($status === 'paid') {
            $query->where('is_commission_paid', true);
        } elseif ($status === 'pending') {
            $query->where(function ($q) {
                $q->where('is_commission_paid', false)
                    ->orWhereNull('is_commission_paid');
            });
        }

        $orders = $query->latest()
            ->paginate(20)
            ->withQueryString();

        $orders->getCollection()->transform(function ($order) {
            $order->commission_amount = $order->marketer
                ? $order->marketer->calculateCommission(
                    (float) $order->total_amount
                )
                : 0;

            return $order;
        });

        return view('marketer.commissions.index', compact(
            'orders',
            'totals',
            'status'
        ));
    }
}
