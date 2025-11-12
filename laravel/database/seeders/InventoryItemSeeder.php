<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;
use App\Models\Venue;

/**
 * Seeds a minimal set of inventory items per venue (AV and seating).
 */
class InventoryItemSeeder extends Seeder
{
    public function run(): void
    {
        $perVenue = [
            ['item_name' => 'Projector', 'quantity_available' => 4],
            ['item_name' => 'Sound System', 'quantity_available' => 2],
            ['item_name' => 'Chairs', 'quantity_available' => 200],
            ['item_name' => 'Tables', 'quantity_available' => 40],
        ];

        Venue::query()->get()->each(function (Venue $venue) use ($perVenue) {
            foreach ($perVenue as $item) {
                InventoryItem::updateOrCreate(
                    [
                        'venue_id' => $venue->getKey(),
                        'item_name' => $item['item_name'],
                    ],
                    [
                        'quantity_available' => $item['quantity_available'],
                    ],
                );
            }
        });
    }
}
