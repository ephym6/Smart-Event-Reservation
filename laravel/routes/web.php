<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\AuthController;

Route::view('/', 'home')->name('home');
Route::view('/dashboard', 'dashboard')->name('dashboard');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [VenueController::class, 'dashboard'])->name('dashboard');

    // Venues listing
    Route::get('/venues', [VenueController::class, 'index'])->name('venues.list');

    // Single venue details
    Route::get('/venues/{id}', [VenueController::class, 'show'])->name('venues.details');

    // Booking form
    Route::get('/book', [VenueController::class, 'book'])->name('venues.book');

    // Handle reservation submission
    Route::post('/reserve', [VenueController::class, 'reserve'])->name('venues.reserve');
});

Route::get('/test', function () {
    return 'Test works!';
});

// Temporary debug routes to inspect venues in the database
Route::get('/debug/venues-count', function () {
    return response()->json(['count' => app()->make(\App\Models\Venue::class)::count()]);
});

Route::get('/debug/venues-sample', function () {
    $venues = app()->make(\App\Models\Venue::class)::limit(10)->get();
    return response()->json($venues);
});
