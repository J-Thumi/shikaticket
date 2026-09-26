<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Marketer;
use App\Models\MarketerPayout;
use App\Services\BlinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class MarketerPayoutController extends Controller
{
    /**
     * Process Lightning payment to a Marketer via Blink API
     */
    public function processPayout(Request $request, Marketer $marketer, BlinkService $blinkService)
    {
        $request->validate([
            'payout_type' => 'required|in:ln_address,bolt11,manual',
            'lightning_address' => 'required_if:payout_type,ln_address|nullable|string',
            'bolt11_invoice' => 'required_if:payout_type,bolt11|nullable|string',
            'amount_sats' => 'required_if:payout_type,ln_address|nullable|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $unpaidOrders = $marketer->orders()
            ->where('status', 'paid')
            ->where('is_commission_paid', false)
            ->get();

        if ($unpaidOrders->isEmpty()) {
            return back()->with('error', 'There are no pending unpaid commissions for this marketer.');
        }

        $totalCommissionKes = $unpaidOrders->sum('commission_amount');
        $payoutType = $request->input('payout_type');

        try {
            DB::beginTransaction();

            $blinkStatus = 'SUCCESS';
            $destination = '';

            if ($payoutType === 'ln_address') {
                $destination = $request->input('lightning_address');
                $sats = (int) $request->input('amount_sats');

                // Execute Blink LN Address payment
                $response = $blinkService->sendToLnAddress($destination, $sats);

                if (!empty($response['errors'])) {
                    $errorMsg = $response['errors'][0]['message'] ?? 'Blink payment failed';
                    throw new Exception("Blink Error: {$errorMsg}");
                }

                $blinkStatus = $response['status'] ?? 'SUCCESS';

            } elseif ($payoutType === 'bolt11') {
                $destination = $request->input('bolt11_invoice');

                // Execute Blink BOLT11 Invoice payment
                $response = $blinkService->payLnInvoice($destination);

                if (!empty($response['errors'])) {
                    $errorMsg = $response['errors'][0]['message'] ?? 'Blink payment failed';
                    throw new Exception("Blink Error: {$errorMsg}");
                }

                $blinkStatus = $response['status'] ?? 'SUCCESS';

            } else {
                $destination = 'Manual Cash/M-Pesa Transfer';
            }

            // In MarketerPayoutController.php where response is returned:
            $blinkTransactionId = $response['transaction']['id'] ?? null;
            $blinkStatus = $response['status'] ?? 'SUCCESS';
            // Record Payout
            $payout = MarketerPayout::create([
                'marketer_id' => $marketer->id,
                'organizer_id' => $marketer->organizer_id,
                'amount' => $totalCommissionKes,
                'currency' => 'KES',
                'payment_method' => $payoutType,
                'transaction_reference' => $blinkTransactionId,
                'destination' => $destination,
                'blink_status' => $blinkStatus,
                'notes' => $request->input('notes'),
                'paid_at' => now(),
            ]);

            // Mark attributed orders as paid
            $marketer->orders()
                ->whereIn('id', $unpaidOrders->pluck('id'))
                ->update([
                    'is_commission_paid' => true,
                    'commission_paid_at' => now(),
                ]);

            DB::commit();

            return back()->with('status', 'Payout processed successfully via ' . strtoupper($payoutType) . '!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Marketer Payout Exception', ['error' => $e->getMessage()]);
            return back()->with('error', $e->getMessage());
        }
    }
}