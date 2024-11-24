<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KindleController;
use App\Http\Controllers\WeatherController;

Route::get('/', function () {
    return view('index');
});

Route::get('/ui', function () {
    return view('ui');
});

Route::get('/ui/weather', [WeatherController::class, 'forecast']);

Route::patch('/kindle/brightness/{brightness}', [KindleController::class, 'setBrightness']);
Route::patch('/kindle/frontlight/boost', [KindleController::class, 'boostFrontLight']);
Route::get('/kindle/frontlight', [KindleController::class, 'getBrightness']);
Route::get('/kindle/alslux', [KindleController::class, 'getAlsLux']);
