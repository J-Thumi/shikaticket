<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'reservation_id',
        'event_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'currency',
        'status',
        'idempotency_key',
        'custom_responses',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'custom_responses' => 'array',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(TicketReservation::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}