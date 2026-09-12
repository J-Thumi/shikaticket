<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'total_quantity',
        'reserved_quantity',
        'sold_quantity',
        'min_per_order',
        'max_per_order',
        'sales_start_date',
        'sales_end_date',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'sold_quantity' => 'integer',
        'min_per_order' => 'integer',
        'max_per_order' => 'integer',
        'sales_start_date' => 'datetime',
        'sales_end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(TicketReservation::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Atomically reserves tickets during checkout to prevent overselling.
     */
    public function reserve(int $quantity, string $sessionId, int $holdMinutes = 10): TicketReservation
    {
        return DB::transaction(function () use ($quantity, $sessionId, $holdMinutes) {
            $updated = DB::table('ticket_types')
                ->where('id', $this->id)
                ->whereRaw('(sold_quantity + reserved_quantity + ?) <= total_quantity', [$quantity])
                ->increment('reserved_quantity', $quantity);

            if (! $updated) {
                throw new Exception('Selected tickets are no longer available in the requested quantity.');
            }

            return $this->reservations()->create([
                'session_id' => $sessionId,
                'quantity' => $quantity,
                'expires_at' => now()->addMinutes($holdMinutes),
            ]);
        });
    }
}