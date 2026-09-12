<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\TicketReservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BitikaWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $transactionCode = $request->input('data.transaction_code');
        $status = strtolower($request->input('data.status', ''));

        Log::info('Bitika Webhook Payload Received', [
            'event'            => $request->input('event'),
            'transaction_code' => $transactionCode,
            'status'           => $status,
            'payload'          => $request->all(),
        ]);
        $payment = Payment::where('transaction_reference', $transactionCode)->first();

        if (!$payment) {

            Log::warning('Payment record not found for transaction code', ['transaction_code' => $transactionCode]);
            return response()->json(['message' => 'Payment record not found'], 404);
        }

        $order = $payment->order;

        if ($order->status === 'paid') {
            return response()->json(['message' => 'Order already processed']);
        }

        if (in_array($status, ['fulfilled', 'paid', 'success', 'successful'])) {
            DB::transaction(function () use ($order, $payment, $request) {
                // 1. Mark Payment and Order as successful
                $payment->update([
                    'status'  => 'successful',
                    'payload' => array_merge((array) $payment->payload, ['webhook' => $request->all()]),
                ]);

                $order->update(['status' => 'paid']);

                // 2. Locate active reservation
                
                $reservation = $order->reservation;

                if (!$reservation) {
                    Log::error('Reservation not found for order', [
                        'order_id' => $order->id,
                        'reservation_id' => $order->reservation_id,
                    ]);

                    throw new \RuntimeException(
                        'Reservation not found for order.'
                    );
                }

                $ticketType = $reservation->ticketType;
                $quantity = $reservation->quantity;

                // 3. Issue individual QR tickets
                for ($i = 0; $i < $quantity; $i++) {
                    $order->tickets()->create([
                        'ticket_type_id' => $ticketType->id,
                        'status'         => 'valid',
                    ]);
                }

                // 4. Update ticket quantities & clean up lock
                if ($reservation) {
                    $ticketType->decrement('reserved_quantity', $reservation->quantity);
                    $ticketType->increment('sold_quantity', $reservation->quantity);
                    $reservation->delete();
                } else {
                    $ticketType->increment('sold_quantity', $quantity);
                }
            });

            Log::info('Order successfully fulfilled', ['order_id' => $order->id]);
        } elseif (in_array($status, ['failed', 'cancelled'])) {
            $payment->update([
                'status'  => 'failed',
                'payload' => array_merge((array) $payment->payload, ['webhook' => $request->all()]),
            ]);

            $order->update(['status' => 'failed']);
        }

        return response()->json(['success' => true]);
    }
}