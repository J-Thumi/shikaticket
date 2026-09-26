<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketerPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketer_id',
        'organizer_id',
        'amount',
        'currency',
        'payment_method',
        'destination',
        'blink_status',
        'transaction_reference',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function marketer(): BelongsTo
    {
        return $this->belongsTo(Marketer::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }
}