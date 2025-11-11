<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    protected $primaryKey = 'venue_id'; // custom primary key

    protected $fillable = [
        'venue_name',
        'description',
        'capacity',
        'location',
        'price_per_hour',
        'status',
    ];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
    ];

    /**
     * Relationships
     */

    // A venue can host many events
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'venue_id', 'venue_id');
    }

    // A venue can have many reservations
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'venue_id', 'venue_id');
    }
}
