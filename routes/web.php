<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KindleController;
use App\Http\Controllers\WeatherController;

Route::get('/', function () {
    return view('index');
});

Route::prefix('/ui')->group(function() {
    Route::get('/', function () {
        return view('ui');
    });

    Route::get('/weather', [WeatherController::class, 'forecast']);
});

Route::prefix('/kindle')->controller(KindleController::class)->group(function() {
    Route::patch('/brightness/{brightness}', 'setBrightness');
    // Route::patch('/frontlight/boost', 'boostFrontLight');
    Route::get('/frontlight', 'getBrightness');
    Route::get('/alslux', 'getAlsLux');
    Route::post('/setup', 'setup');
});
