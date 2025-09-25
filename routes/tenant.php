<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:keycloak'])
    ->group(function () {
        Route::view('/dashboard', 'landing')->name('dashboard');
    });
