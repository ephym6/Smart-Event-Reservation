<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

/**
 * DatabaseSeeder orchestrates ordered, idempotent seeders.
 *
 * Notes:
 * - Runs curated seeders for venues, events, and inventory to produce realistic demo data.
 * - Also creates a few users via factory for testing.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Minimal users for testing/login later (adjust as needed)
        User::factory()->create(['name' => 'Demo User']);
        User::factory(4)->create();

        // Domain seeders (ordered: venues -> events -> inventory)
        $this->call([
            VenueSeeder::class,
            EventSeeder::class,
            InventoryItemSeeder::class,
            // ReservationSeeder::class, // Add when flows are implemented
            // ReservationItemSeeder::class,
        ]);
    }
}
