<?php

namespace App\Http\Controllers;

use App\Models\Venue;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch 6 featured venues (for example)
        $featuredVenues = Venue::inRandomOrder()->take(6)->get();

        return view('home', compact('featuredVenues'));
    }
}
