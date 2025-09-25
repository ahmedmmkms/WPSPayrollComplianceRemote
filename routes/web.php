<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $locale = request()->getPreferredLanguage(['en', 'ar']) ?? 'en';
    App::setLocale($locale);

    return view('landing', ['locale' => $locale]);
})->name('home');

Route::get('/login', [LoginController::class, 'redirect'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/health', fn () => response()->json(['status' => 'ok']));
