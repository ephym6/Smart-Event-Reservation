<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'res_item_id'; // custom primary key

    public $timestamps = false; // optional (since your table doesn’t include created_at/updated_at)

    protected $fillable = [
        'reservation_id',
        'item_id',
        'quantity_reserved',
    ];

    protected $casts = [
        'quantity_reserved' => 'integer',
    ];

    /**
     * Relationships
     */

    // Each reservation item belongs to one reservation
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'reservation_id');
    }

    // Each reservation item belongs to one inventory item
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'item_id', 'item_id');
    }
}
