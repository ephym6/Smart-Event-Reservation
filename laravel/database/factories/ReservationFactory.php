<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('now', '+3 months');
        $end = (clone $start)->modify('+'.rand(1,5).' hours');

        return [
            'user_id' => User::factory(),
            'venue_id' => Venue::factory(),
            'event_id' => Event::factory(),
            'start_time' => $start,
            'end_time' => $end,
            'status' => $this->faker->randomElement(['pending','approved','cancelled','completed']),
            'total_cost' => $this->faker->randomFloat(2, 5000, 200000),
        ];
    }
}
