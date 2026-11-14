<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketerApplication extends Model
{
    protected $fillable = [
        'marketer_invitation_id',
        'user_id',
        'status',
        'agreement_title',
        'agreement_snapshot',
        'agreement_accepted',
        'signature_name',
        'agreement_accepted_at',
        'signer_ip',
        'signer_user_agent',
        'reviewed_by',
        'reviewed_at',
        'organizer_note',
        'marketer_id',
    ];

    protected function casts(): array
    {
        return [
            'agreement_accepted' => 'boolean',
            'agreement_accepted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(MarketerInvitation::class, 'marketer_invitation_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function marketer(): BelongsTo
    {
        return $this->belongsTo(Marketer::class);
    }
}
