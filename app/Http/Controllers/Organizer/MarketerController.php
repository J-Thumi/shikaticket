<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Marketer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MarketerController extends Controller
{
    public function index()
    {
        $organizer = Auth::user()->organizer;

        $marketers = $organizer->marketers()
            ->withCount('orders')
            ->latest()
            ->paginate(20);

        return view(
            'organizer.marketers.index',
            compact('marketers')
        );
    }

    public function create()
    {
        return view('organizer.marketers.create');
    }

    public function store(Request $request)
    {
        $organizer = Auth::user()->organizer;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'commission_type' => [
                'required',
                'in:fixed,percent',
            ],

            'fixed_commission' => [
                'nullable',
                'required_if:commission_type,fixed',
                'numeric',
                'min:0',
            ],

            'commission_percent' => [
                'nullable',
                'required_if:commission_type,percent',
                'numeric',
                'min:0',
                'max:100',
            ],
        ]);

        // Make sure only the selected commission type is stored
        if ($validated['commission_type'] === 'fixed') {
            $validated['commission_percent'] = null;
        } else {
            $validated['fixed_commission'] = null;
        }

        $validated['referral_code'] = $this->generateReferralCode();

        $marketer = $organizer->marketers()->create($validated);

        return redirect()
            ->route('organizer.marketers.index')
            ->with(
                'status',
                "{$marketer->name} was added as a marketer."
            );
    }

    public function edit(Marketer $marketer)
    {
        $this->authorizeMarketer($marketer);

        return view(
            'organizer.marketers.edit',
            compact('marketer')
        );
    }

   public function update(
        Request $request,
        Marketer $marketer
    ) {
        $this->authorizeMarketer($marketer);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'commission_type' => [
                'required',
                'in:fixed,percent',
            ],

            'fixed_commission' => [
                'nullable',
                'required_if:commission_type,fixed',
                'numeric',
                'min:0',
            ],

            'commission_percent' => [
                'nullable',
                'required_if:commission_type,percent',
                'numeric',
                'min:0',
                'max:100',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);

        // Only keep the selected commission type's value.
        if ($validated['commission_type'] === 'fixed') {
            $validated['commission_percent'] = null;
        } else {
            $validated['fixed_commission'] = null;
        }

        $marketer->update($validated);

        return redirect()
            ->route('organizer.marketers.show', $marketer)
            ->with(
                'status',
                'Marketer updated successfully.'
            );
    }

    public function destroy(Marketer $marketer)
    {
        $this->authorizeMarketer($marketer);

        /*
         * Don't delete if they already have sales.
         *
         * Deactivating preserves historical attribution.
         */
        $marketer->update([
            'is_active' => false,
        ]);

        return back()->with(
            'status',
            'Marketer has been deactivated.'
        );
    }

    private function generateReferralCode(): string
    {
        do {
            $code = strtoupper(
                'ST-' . Str::random(7)
            );
        } while (
            Marketer::where(
                'referral_code',
                $code
            )->exists()
        );

        return $code;
    }

    private function authorizeMarketer(
        Marketer $marketer
    ): void {
        abort_unless(
            $marketer->organizer_id ===
                Auth::user()->organizer->id,
            403
        );
    }
    public function show(Marketer $marketer)
    {
        $this->authorizeMarketer($marketer);

        // Load events with paid order count
        $marketer->load([
            'events' => function ($query) use ($marketer) {
                $query->withCount([
                    'orders as marketer_paid_orders' => function ($q) use ($marketer) {
                        $q->where('status', 'paid')
                        ->where('marketer_id', $marketer->id);
                    },
                ]);
            },
        ]);

        // All orders attributed to this marketer
        $orders = Order::where('marketer_id', $marketer->id)
            ->with(['event', 'tickets'])
            ->latest()
            ->paginate(15);

        // Paid orders query
        $paidOrdersQuery = Order::where('marketer_id', $marketer->id)
            ->where('status', 'paid');

        // Revenue
        $totalRevenue = (clone $paidOrdersQuery)
            ->sum('total_amount');

        // Number of paid orders
        $paidOrdersCount = (clone $paidOrdersQuery)
            ->count();

        // Calculate tickets and commission
        $paidOrders = (clone $paidOrdersQuery)
            ->with('tickets')
            ->get();

        $ticketsSold = 0;
        $commissionEarned = 0.00;

        foreach ($paidOrders as $order) {
            $orderTickets = $order->tickets->sum('quantity');

            $ticketsSold += $orderTickets;

            $commissionEarned += $marketer->calculateCommission(
                (float) $order->total_amount,
                $orderTickets
            );
        }

        return view('organizer.marketers.show', compact(
            'marketer',
            'orders',
            'totalRevenue',
            'paidOrdersCount',
            'ticketsSold',
            'commissionEarned'
        ));
    }
}