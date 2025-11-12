<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;

/**
 * Seeds canonical venues for Nairobi, Kenya.
 *
 * Design goals:
 * - Human-recognizable places for demos (no lorem-ipsum).
 * - Idempotent: updateOrCreate by venue_name to allow re-runs.
 * - Minimal fields filled to match migrations and UI needs.
 */
class VenueSeeder extends Seeder
{
    public function run(): void
    {
        $venues = [
            [
                'venue_name' => 'Karen Country Club',
                'description' => 'Historic members club in Karen with lush grounds and event facilities.',
                'capacity' => 400,
                'location' => 'Karen, Nairobi',
                'price_per_hour' => 150000, // indicative placeholder
                'status' => 'available',
            ],
            [
                'venue_name' => 'Kenyatta International Convention Centre (KICC)',
                'description' => 'Iconic convention centre in Nairobi CBD with multiple halls and rooftop.',
                'capacity' => 5000,
                'location' => 'Nairobi CBD, Nairobi',
                'price_per_hour' => 300000,
                'status' => 'available',
            ],
            [
                'venue_name' => 'Bomas of Kenya',
                'description' => 'Cultural centre in Lang’ata with large auditoriums and outdoor spaces.',
                'capacity' => 2000,
                'location' => "Lang'ata, Nairobi",
                'price_per_hour' => 120000,
                'status' => 'available',
            ],
            [
                'venue_name' => 'Safari Park Hotel & Casino',
                'description' => 'Conference hotel with extensive meeting facilities and gardens.',
                'capacity' => 1500,
                'location' => 'Kasarani, Nairobi',
                'price_per_hour' => 180000,
                'status' => 'available',
            ],
            [
                'venue_name' => 'Sarit Expo Centre',
                'description' => 'Modern exhibition hall within the Sarit Centre complex.',
                'capacity' => 3000,
                'location' => 'Westlands, Nairobi',
                'price_per_hour' => 250000,
                'status' => 'available',
            ],
        ];

        foreach ($venues as $data) {
            Venue::updateOrCreate(
                ['venue_name' => $data['venue_name']],
                $data,
            );
        }
    }
}
