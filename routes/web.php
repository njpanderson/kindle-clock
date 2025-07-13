<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ImageController;
use App\Http\Controllers\KindleController;
use App\Http\Controllers\UIController;
use App\Http\Controllers\WeatherController;

Route::get('/', function () {
    return view('index');
});

Route::prefix('/ui')->group(function() {
    Route::get('/', [UIController::class, 'show']);
    Route::get('/weather', [WeatherController::class, 'forecast']);
    // Route::get('/images/{image}', [ImageController::class, 'image'])
        // ->where('image', '.*');
});

Route::prefix('/kindle')->controller(KindleController::class)->group(function() {
    Route::patch('/brightness/{brightness}', 'setBrightness');
    // Route::patch('/frontlight/boost', 'boostFrontLight');
    Route::get('/frontlight', 'getBrightness');
    Route::get('/alslux', 'getAlsLux');
    Route::get('/brightness-for-lux', 'getBrightnessForLux');
    Route::post('/set-time', 'setTime');
    Route::post('/setup', 'setup');
});
