<?php

namespace App\Services;

use Cmfcmf\OpenWeatherMap;

class WeatherService
{
    protected $owm;

    public function __construct()
    {
        $apiKey = config('openweather.api_key');
        $this->owm = new OpenWeatherMap($apiKey);
    }

    public function getThreeDayForecast($city)
    {
        // Get the 5-day forecast
        $forecast = $this->owm->getWeatherForecast($city, 'metric', config('openweather.lang'), '5');

        $threeDayForecast = [];
        foreach ($forecast->list as $day) {
            $date = date('Y-m-d', $day->dt);
            if (!isset($threeDayForecast[$date])) {
                $threeDayForecast[$date] = [
                    'temperature' => $day->main->temp,
                    'weather' => $day->weather[0]->description,
                ];
            }
            // Stop after collecting three days
            if (count($threeDayForecast) >= 3) {
                break;
            }
        }

        return $threeDayForecast;
    }
}
