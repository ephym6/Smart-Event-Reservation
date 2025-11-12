<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id'; // custom primary key

    public $timestamps = false; // events table does not have created_at/updated_at

    protected $fillable = [
        'event_name',
        'venue_id',
        'event_date',
        'start_time',
        'end_time',
        'description',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Relationships
     */

    // Each event belongs to a venue
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'venue_id');
    }

    // An event can have many reservations
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'event_id', 'event_id');
    }
}
