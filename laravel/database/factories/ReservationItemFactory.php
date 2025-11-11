<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\Reservation;
use App\Models\ReservationItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationItemFactory extends Factory
{
    protected $model = ReservationItem::class;

    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'item_id' => InventoryItem::factory(),
            'quantity_reserved' => $this->faker->numberBetween(1, 10),
        ];
    }
}
