<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/dashboard', 'dashboard')->name('dashboard');

Route::get('/test', function () {
    return 'Test works!';
});
