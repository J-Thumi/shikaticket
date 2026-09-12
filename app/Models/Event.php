<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'slug',
        'description',
        'venue_name',
        'venue_address',
        'start_date',
        'end_date',
        'banner_url',
        'status',
        'settings',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'settings'   => 'array',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Order::class);
    }

    // Custom Query Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_date', '>=', now());
    }

    // Dynamic Accessors for the View
    public function getDateAttribute(): string
    {
        return $this->start_date ? $this->start_date->format('D, M j') : '';
    }

    public function getDateLabelAttribute(): string
    {
        return $this->start_date ? $this->start_date->format('D, M j · g:i A') : '';
    }

    public function getMinPriceAttribute(): float
    {
        return (float) ($this->ticketTypes->where('is_active', true)->min('price') ?? 0);
    }

    public function getIsSellingFastAttribute(): bool
    {
        return $this->ticketTypes->contains(function ($ticket) {
            if ($ticket->total_quantity <= 0) return false;
            $remaining = $ticket->total_quantity - ($ticket->sold_quantity + $ticket->reserved_quantity);
            return ($remaining / $ticket->total_quantity) < 0.20; // Less than 20% tickets remaining
        });
    }
}