<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TicketReservation extends Model
{
    use HasFactory;

    public $timestamps = false; // Custom created_at tracking only

    protected $fillable = [
        'session_id',
        'ticket_type_id',
        'quantity',
        'expires_at',
        'created_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }
}