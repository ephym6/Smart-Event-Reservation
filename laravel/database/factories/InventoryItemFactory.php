<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        return [
            'venue_id' => Venue::factory(),
            'item_name' => $this->faker->word(),
            'quantity_available' => $this->faker->numberBetween(1, 50),
        ];
    }
}
