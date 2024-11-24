<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\WeatherService;

class WeatherController extends Controller
{
    public function forecast()
    {
        $weather = app(WeatherService::class);

        return view('weather', [
            'forecast' => $weather->forecastDays(
                config('kindle.location.lat'),
                config('kindle.location.lng')
            )
        ]);
    }
}
