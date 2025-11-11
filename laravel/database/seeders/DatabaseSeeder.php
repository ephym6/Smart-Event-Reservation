<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Venue;
use App\Models\Event;
use App\Models\InventoryItem;
use App\Models\Reservation;
use App\Models\ReservationItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create();
        Venue::factory(5)->create();
        Event::factory(20)->create();
        InventoryItem::factory(30)->create();
        Reservation::factory(15)->create();
        ReservationItem::factory(40)->create();
    }
}
