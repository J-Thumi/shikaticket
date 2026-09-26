<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Marketer extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'name',
        'email',
        'phone',
        'referral_code',
        'commission_percent',
        'fixed_commission',
        'commission_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_percent' => 'decimal:2',
        'fixed_commission' => 'decimal:2',
    ];

    /**
     * Calculate total commission for a given order amount.
     */
    public function calculateCommission(float $orderTotal): float
    {
        if ($this->commission_type === 'percent') {
            $commission = $orderTotal * ($this->commission_percent / 100);

            return round($commission, 2);
        }

        if ($this->commission_type === 'fixed') {
            return round((float) $this->fixed_commission, 2);
        }

        return 0.00;
    }

    protected static function booted(): void
    {
        static::creating(function (Marketer $marketer) {
            if (empty($marketer->referral_code)) {
                $marketer->referral_code =
                    strtoupper(Str::random(8));
            }
        });
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(
            Event::class,
            'event_marketer'
        )->withTimestamps();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getReferralUrl(Event $event): string
    {
        return route('events.show', [
            'event' => $event->slug,
            'ref' => $this->referral_code,
        ]);
    }
}