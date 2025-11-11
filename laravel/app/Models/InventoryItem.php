<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id'; // custom primary key

    protected $fillable = [
        'venue_id',
        'item_name',
        'quantity_available',
    ];

    protected $casts = [
        'quantity_available' => 'integer',
    ];

    /**
     * Relationships
     */

    // Each inventory item belongs to a specific venue
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'venue_id');
    }

    // Each inventory item can appear in many reservation items
    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class, 'item_id', 'item_id');
    }
}
