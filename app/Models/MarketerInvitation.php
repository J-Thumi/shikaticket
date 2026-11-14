<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class MarketerInvitation extends Model
{
    protected $fillable = [
        'organizer_id',
        'event_id',
        'created_by',
        'token_hash',
        'token_encrypted',
        'commission_type',
        'fixed_commission',
        'commission_percent',
        'agreement_enabled',
        'agreement_title',
        'agreement_text',
        'is_active',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'agreement_enabled' => 'boolean',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
            'fixed_commission' => 'decimal:2',
            'commission_percent' => 'decimal:2',
        ];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(MarketerApplication::class);
    }

    public function isAvailable(): bool
    {
        return $this->is_active
            && (!$this->expires_at || $this->expires_at->isFuture());
    }

    /**
     * Generate the original public invitation URL.
     */
    public function getShareUrlAttribute(): ?string
    {
        if (!$this->token_encrypted) {
            return null;
        }

        try {
            $token = Crypt::decryptString($this->token_encrypted);

            return route('marketer-invitations.show', [
                'token' => $token,
            ]);
        } catch (DecryptException $e) {
            return null;
        }
    }
}