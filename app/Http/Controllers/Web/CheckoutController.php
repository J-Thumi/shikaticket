<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Payment;
use App\Models\TicketReservation;
use App\Models\TicketType;
use App\Services\BitikaPaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected BitikaPaymentService $bitika
    ) {
    }

    /**
     * Reserve tickets for the current checkout session.
     */
    public function reserve(
        Request $request,
        Event $event
    ): RedirectResponse {
        $validated = $request->validate([
            'ticket_type_id' => [
                'required',
                'integer',
                'exists:ticket_types,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'session_id' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        /*
         * Make sure the ticket type actually belongs to this event.
         */
        $ticketType = TicketType::query()
            ->where('id', $validated['ticket_type_id'])
            ->where('event_id', $event->id)
            ->firstOrFail();

        /*
         * Don't allow arbitrary sessions to reserve tickets for
         * another checkout session.
         */
        $reservation = $ticketType->reserve(
            quantity: $validated['quantity'],
            sessionId: $validated['session_id'],
        );

        return redirect()->route(
            'checkout.show',
            $reservation
        );
    }

    /**
     * Display checkout page.
     */
    public function show(
        Request $request,
        TicketReservation $reservation
    ): View {
        /*
         * Make sure this reservation belongs to the current
         * checkout session.
         */
        if ($reservation->session_id !== $request->session()->getId()) {
            abort(403, 'This reservation does not belong to your session.');
        }

        if ($reservation->expires_at->isPast()) {
            abort(
                410,
                'Reservation timed out. Please select your tickets again.'
            );
        }

        $reservation->load([
            'ticketType.event',
        ]);

        return view('checkout.show', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * Initiate Bitika STK Push payment.
     */
    public function process(
        Request $request,
        TicketReservation $reservation
    ): JsonResponse {
        /*
         * 1. Verify reservation belongs to current session.
         */
        if ($reservation->session_id !== $request->session()->getId()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reservation.',
            ], 403);
        }

        /*
         * 2. Verify reservation hasn't expired.
         */
        if ($reservation->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation timed out. Please select your tickets again.',
            ], 410);
        }

        /*
         * 3. Don't allow a reservation to be paid twice.
         */
        if (
            $reservation->order()
                ->whereIn('status', ['pending', 'paid'])
                ->exists()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This reservation already has a payment in progress.',
            ], 409);
        }

        /*
         * 4. Validate customer information.
         */
        $validated = $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'customer_email' => [
                'required',
                'email',
                'max:255',
            ],

            'customer_phone' => [
                'required',
                'string',
                'regex:/^(254|\+254|0)?(7|1)\d{8}$/',
            ],

            'custom_responses' => [
                'nullable',
                'array',
            ],
        ]);

        /*
         * 5. Load the exact ticket type.
         */
        $reservation->load('ticketType.event');

        $ticketType = $reservation->ticketType;

        if (!$ticketType) {
            Log::error('Reservation has no ticket type.', [
                'reservation_id' => $reservation->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'The selected ticket is no longer available.',
            ], 422);
        }

        /*
         * 6. Calculate total from the server-side price.
         *
         * Never trust an amount sent by the frontend.
         */
        $totalAmount = (int) (
            $ticketType->price * $reservation->quantity
        );

        if ($totalAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid ticket amount.',
            ], 422);
        }

        /*
         * 7. Create a stable idempotency key for this order.
         */
        $idempotencyKey = (string) Str::uuid();

        Log::info('Initiating Bitika checkout payment', [
            'reservation_id' => $reservation->id,
            'ticket_type_id' => $ticketType->id,
            'quantity' => $reservation->quantity,
            'amount' => $totalAmount,
            'user_id' => auth()->id(),
        ]);

        /*
         * 8. Create the order.
         */
        try {
            $order = Order::create([
                'order_number' => 'ST-' . strtoupper(Str::random(8)),

                'event_id' => $ticketType->event_id,

                'reservation_id' => $reservation->id,

                'user_id' => auth()->id(),

                'customer_name' => $validated['customer_name'],

                'customer_email' => $validated['customer_email'],

                'customer_phone' => $validated['customer_phone'],

                'total_amount' => $totalAmount,

                'currency' => 'KES',

                'status' => 'pending',

                'idempotency_key' => $idempotencyKey,

                'custom_responses' =>
                    $validated['custom_responses'] ?? null,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to create checkout order.', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create your order. Please try again.',
            ], 500);
        }

        /*
         * 9. Send STK Push to Bitika.
         */
        try {
            $bitikaResponse = $this->bitika->collect(
                $validated['customer_phone'],
                $totalAmount,
                $idempotencyKey
            );
        } catch (Exception $e) {
            $order->update([
                'status' => 'failed',
            ]);

            Log::error('Bitika STK Push exception.', [
                'order_id' => $order->id,
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to initiate payment. Please try again.',
            ], 502);
        }

        /*
         * 10. Normalize Bitika response.
         */
        $responseData = $bitikaResponse['response']
            ?? $bitikaResponse;

        $gatewayStatus = strtolower(
            $responseData['status'] ?? ''
        );

        $transactionCode =
            $responseData['transaction_code'] ?? null;

        /*
         * 11. Validate Bitika response.
         */
        if (
            empty($transactionCode) ||
            $gatewayStatus !== 'pending'
        ) {
            $order->update([
                'status' => 'failed',
            ]);

            Log::warning('Bitika payment initiation failed.', [
                'order_id' => $order->id,
                'response' => $responseData,
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    $responseData['message']
                    ?? 'Payment initiation failed.',
            ], 400);
        }

        /*
         * 12. Save gateway transaction.
         */
        try {
            $payment = Payment::create([
                'order_id' => $order->id,

                'gateway' => 'bitika',

                'transaction_reference' =>
                    $transactionCode,

                'amount' => $totalAmount,

                'status' => 'initiated',

                'payload' => $responseData,
            ]);
        } catch (Exception $e) {
            /*
             * Important:
             *
             * Bitika may already have accepted the payment request.
             * Therefore don't blindly tell the customer that the
             * payment definitely failed.
             */
            Log::critical(
                'Bitika payment created but local payment record failed.',
                [
                    'order_id' => $order->id,
                    'transaction_code' => $transactionCode,
                    'error' => $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Payment was initiated, but we could not save the payment record. Please contact support if money was deducted.',
            ], 500);
        }

        /*
         * 13. Return checkout information to frontend.
         */
        return response()->json([
            'success' => true,

            'message' =>
                'STK Push sent! Enter your M-Pesa PIN on your phone.',

            'order_number' => $order->order_number,

            'transaction_code' => $payment->transaction_reference,

            'status' => 'pending',

            'amount' => $totalAmount,

            'currency' => 'KES',
        ], 200);
    }

    /**
     * Poll order payment status.
     */
    public function status(
        Request $request,
        Order $order
    ): JsonResponse {
        /*
         * Don't allow someone to poll arbitrary orders.
         *
         * For guests, customer email/phone or a secure checkout
         * token should eventually be used instead.
         */
        if (
            auth()->check() &&
            $order->user_id !== auth()->id()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        return response()->json([
            'success' => true,

            'order_number' => $order->order_number,

            'status' => $order->status,

            'is_paid' => $order->status === 'paid',
        ]);
    }

    /**
     * Display successful order.
     */
    public function success(Order $order): View
    {
        $order->load([
            'tickets.ticketType',
            'event',
            'payment',
        ]);

        return view('orders.success', [
            'order' => $order,
        ]);
    }
}