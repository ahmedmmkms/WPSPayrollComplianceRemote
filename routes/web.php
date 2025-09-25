<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $locale = request()->getPreferredLanguage(['en', 'ar']) ?? 'en';
    App::setLocale($locale);

    return view('landing', ['locale' => $locale]);
});

Route::get('/health', fn () => response()->json(['status' => 'ok']))->name('health');
