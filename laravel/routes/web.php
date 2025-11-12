<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationItemController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

// Home page

Route::get('/', [HomeController::class, 'index'])->name('home');

// ---------------- USERS ----------------
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', function () {
    return view('users.create');
})->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', function ($id) {
    return view('users.edit', ['id' => $id]);
})->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// ---------------- VENUES ----------------
Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
Route::get('/venues/create', function () {
    return view('venues.create');
})->name('venues.create');
Route::post('/venues', [VenueController::class, 'store'])->name('venues.store');
Route::get('/venues/{id}/edit', function ($id) {
    return view('venues.edit', ['id' => $id]);
})->name('venues.edit');
Route::put('/venues/{id}', [VenueController::class, 'update'])->name('venues.update');
Route::delete('/venues/{id}', [VenueController::class, 'destroy'])->name('venues.destroy');

// ---------------- EVENTS ----------------
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/create', function () {
    return view('events.create');
})->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{id}/edit', function ($id) {
    return view('events.edit', ['id' => $id]);
})->name('events.edit');
Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

// ---------------- INVENTORY ITEMS ----------------
Route::get('/inventory', [InventoryItemController::class, 'index'])->name('inventory.index');
Route::get('/inventory/create', function () {
    return view('inventory.create');
})->name('inventory.create');
Route::post('/inventory', [InventoryItemController::class, 'store'])->name('inventory.store');
Route::get('/inventory/{id}/edit', function ($id) {
    return view('inventory.edit', ['id' => $id]);
})->name('inventory.edit');
Route::put('/inventory/{id}', [InventoryItemController::class, 'update'])->name('inventory.update');
Route::delete('/inventory/{id}', [InventoryItemController::class, 'destroy'])->name('inventory.destroy');

// ---------------- RESERVATIONS ----------------
Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
Route::get('/reservations/create', function () {
    return view('reservations.create');
})->name('reservations.create');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/{id}/edit', function ($id) {
    return view('reservations.edit', ['id' => $id]);
})->name('reservations.edit');
Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

// ---------------- RESERVATION ITEMS ----------------
Route::get('/reservation-items', [ReservationItemController::class, 'index'])->name('reservation_items.index');
Route::get('/reservation-items/create', function () {
    return view('reservation_items.create');
})->name('reservation_items.create');
Route::post('/reservation-items', [ReservationItemController::class, 'store'])->name('reservation_items.store');
Route::get('/reservation-items/{id}/edit', function ($id) {
    return view('reservation_items.edit', ['id' => $id]);
})->name('reservation_items.edit');
Route::put('/reservation-items/{id}', [ReservationItemController::class, 'update'])->name('reservation_items.update');
Route::delete('/reservation-items/{id}', [ReservationItemController::class, 'destroy'])->name('reservation_items.destroy');

// ---------------- SHOW ROUTES (detail pages) ----------------
Route::get('/venues/{id}', [VenueController::class, 'show'])->name('venues.show');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/reservations/{id}', [ReservationController::class, 'show'])->name('reservations.show');
Route::get('/reservations/{id}/success', [ReservationController::class, 'success'])->name('reservations.success');

// ---------------- DASHBOARD ----------------
Route::view('/dashboard', 'dashboard')->name('dashboard');

// ---------------- AUTH (USER) ----------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ---------------- AUTH (ADMIN) ----------------
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');
