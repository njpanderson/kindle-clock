<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KindleController;

Route::get('/', function () {
    return view('index');
});

Route::get('/ui', function () {
    return view('ui');
});

Route::get('/kindle/brightness/{brightness}', [KindleController::class, 'brightness']);
