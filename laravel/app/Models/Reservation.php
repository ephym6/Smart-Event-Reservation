<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    protected $primaryKey = 'reservation_id'; // custom primary key

    protected $fillable = [
        'user_id',
        'venue_id',
        'event_id',
        'start_time',
        'end_time',
        'status',
        'total_cost',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Relationships
     */

    // Each reservation belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Each reservation belongs to a venue
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'venue_id');
    }

    // Each reservation belongs to an event
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    // Each reservation can have multiple reservation items
    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class, 'reservation_id', 'reservation_id');
    }
}
