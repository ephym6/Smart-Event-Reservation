<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{
    protected $model = Venue::class;

    public function definition(): array
    {
        return [
            'venue_name' => $this->faker->company() . ' Hall',
            'description' => $this->faker->sentence(),
            'capacity' => $this->faker->numberBetween(50, 2000),
            'location' => $this->faker->city(),
            'price_per_hour' => $this->faker->randomFloat(2, 5000, 200000),
            'status' => $this->faker->randomElement(['available', 'maintenance', 'unavailable']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
