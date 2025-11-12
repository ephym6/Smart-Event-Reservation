<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Support\Carbon;

/**
 * Seeds a few concrete events mapped to seeded venues.
 *
 * Strategy:
 * - Resolve venue_id by venue_name to avoid relying on auto-increment assumptions.
 * - Keep counts very small; these are showcase records.
 */
class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'event_name' => 'Tech Summit Nairobi',
                'venue' => 'Kenyatta International Convention Centre (KICC)',
                'event_date' => now()->addDays(30)->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'description' => 'Regional technology and innovation summit.',
            ],
            [
                'event_name' => 'Garden Wedding',
                'venue' => 'Karen Country Club',
                'event_date' => now()->addDays(45)->toDateString(),
                'start_time' => '14:00:00',
                'end_time' => '20:00:00',
                'description' => 'Outdoor wedding ceremony and reception.',
            ],
            [
                'event_name' => 'Cultural Gala Night',
                'venue' => 'Bomas of Kenya',
                'event_date' => now()->addDays(60)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '22:00:00',
                'description' => 'Performances and dinner gala.',
            ],
        ];

        foreach ($events as $e) {
            $venue = Venue::where('venue_name', $e['venue'])->first();
            if (! $venue) {
                continue; // Venue not seeded; skip gracefully
            }

            Event::updateOrCreate(
                [
                    'event_name' => $e['event_name'],
                    'venue_id' => $venue->getKey(),
                ],
                [
                    'venue_id' => $venue->getKey(),
                    'event_date' => $e['event_date'],
                    'start_time' => $e['start_time'],
                    'end_time' => $e['end_time'],
                    'description' => $e['description'] ?? null,
                ],
            );
        }
    }
}
