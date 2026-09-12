<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ticket_type_id',
        'ticket_code',
        'qr_code_hash',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ticket) {
            if (empty($ticket->ticket_code)) {
                $ticket->ticket_code = (string) Str::uuid();
            }
            if (empty($ticket->qr_code_hash)) {
                $ticket->qr_code_hash = hash_hmac('sha256', $ticket->ticket_code, config('app.key'));
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function scans(): HasMany
    {
        return $this->hasMany(TicketScan::class);
    }
}