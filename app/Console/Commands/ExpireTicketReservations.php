<?php

namespace App\Console\Commands;

use App\Models\TicketReservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireTicketReservations extends Command
{
    protected $signature = 'tickets:expire-reservations';

    protected $description = 'Expire unpaid ticket reservations and release inventory';

    public function handle(): int
    {
        TicketReservation::query()
            ->where('status', 'pending')
            ->where('expires_at', '<=', now())
            ->select('id')
            ->chunkById(100, function ($reservations) {
                foreach ($reservations as $item) {
                    DB::transaction(function () use ($item) {
                        $reservation = TicketReservation::query()
                            ->whereKey($item->id)
                            ->lockForUpdate()
                            ->first();

                        if (
                            ! $reservation ||
                            $reservation->status !== 'pending' ||
                            $reservation->expires_at->isFuture()
                        ) {
                            // we cannot access reservation id if it is not there is no reservation but we must log even if no reservation for debugging purposes
                            Log::info('No reservation found for expiration');
                            return;
                        }

                        $ticketType = $reservation->ticketType()
                            ->lockForUpdate()
                            ->first();

                        if (! $ticketType) {
                            Log::error('Ticket type missing for expired reservation', [
                                'reservation_id' => $reservation->id,
                            ]);

                            return;
                        }

                        $ticketType->decrement(
                            'reserved_quantity',
                            $reservation->quantity
                        );

                        $reservation->update([
                            'status' => 'expired',
                        ]);

                        Log::info('Ticket reservation expired', [
                            'reservation_id' => $reservation->id,
                            'ticket_type_id' => $ticketType->id,
                            'quantity_released' => $reservation->quantity,
                        ]);
                    });
                }
            });
        return self::SUCCESS;
    }
}