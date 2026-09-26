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
        'marketer_id',
        'commission_amount',
        'is_commission_paid',
        'commission_paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'is_commission_paid' => 'boolean',
        'commission_paid_at' => 'datetime',
        'custom_responses' => 'array',
    ];

    public function marketer(): BelongsTo
    {
        return $this->belongsTo(Marketer::class);
    }
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
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }
}