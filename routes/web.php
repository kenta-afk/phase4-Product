<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect', [MicrosoftAuthController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/callback', [MicrosoftAuthController::class, 'callback'])->name('auth.callback');
