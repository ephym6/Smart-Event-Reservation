<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'event_name' => $this->faker->catchPhrase(),
            'venue_id' => Venue::factory(), // automatically creates venue
            'event_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
