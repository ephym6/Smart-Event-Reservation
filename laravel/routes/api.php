<?php
use App\Http\Controllers\{
    UserController,
    VenueController,
    EventController,
    InventoryItemController,
    ReservationController,
    ReservationItemController
};

Route::apiResource('users', UserController::class);
Route::apiResource('venues', VenueController::class);
Route::apiResource('events', EventController::class);
Route::apiResource('inventory-items', InventoryItemController::class);
Route::apiResource('reservations', ReservationController::class);
Route::apiResource('reservation-items', ReservationItemController::class);
