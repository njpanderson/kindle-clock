<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

use App\Helpers\DateTime;

// https://open-meteo.com/en/docs
class WeatherService
{
    public function forecastDays(
        $lat,
        $lng,
        int $days = 3
    ) {
        $forecast = $this->fetchForecast($lat, $lng, $days);

        $transposed = [];
        $numEntries = count($forecast['daily']['time']);

        for ($i = 0; $i < $numEntries; $i++) {
            $date = new Carbon($forecast['daily']['time'][$i]);

            $entry = [
                'time' => $date,
                'dayName' => DateTime::relativeDayName($date)
            ];

            // Loop through all keys in the original array except 'time'
            foreach ($forecast['daily'] as $key => $values) {
                if ($key !== 'time') {
                    $entry[$key] = $values[$i];

                    if ($key === 'weather_code') {
                        $entry['image'] = config('weathercodes.' . $values[$i]);

                        $entry['day_icon'] = svg(
                            $entry['image']['day']['icon'],
                            'w-full'
                        )->toHtml();

                        $entry['night_icon'] = svg(
                            $entry['image']['night']['icon'],
                            'w-full'
                        )->toHtml();
                    }
                }
            }

            // Formulate precipitation probability based on ((hours / 24) * max)
            $entry['precipitation_probability'] = (
                round(
                    ($entry['precipitation_hours'] / 24) * $entry['precipitation_probability_max']
                )
            );

            $transposed[] = $entry; // Add the entry to the transposed array
        }

        // If you want the transposed array to be indexed numerically
        $transposed = array_values($transposed);

        $forecast['daily'] = collect($transposed);

        return $forecast;
    }

    public function fetchForecast($lat, $lng, int $days)
    {
        $options = [
            'latitude' => $lat,
            'longitude' => $lng,
            'daily' => implode(',', [
                'weather_code',
                'temperature_2m_max',
                'temperature_2m_min',
                'precipitation_probability_max',
                'precipitation_hours'
            ]),
            'wind_speed_unit' => 'mph',
            'timezone' => 'auto',
            'forecast_days' => $days,
            // 'models' => 'ukmo_seamless'
        ];

        // Define a cache key based on the city name
        $cacheKey = crc32(
            'weather_forecast_' . $lat . $lng . $days . json_encode($options)
        );

        // Attempt to retrieve the forecast from the cache
        return Cache::remember($cacheKey, 3600, function () use ($options) {
            // https://api.open-meteo.com/v1/forecast?latitude=51.5085&longitude=-0.1257&daily=weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max&wind_speed_unit=mph&timezone=auto&forecast_days=3&models=ukmo_seamless
            $response = Http::acceptJson()
                ->timeout(3)
                ->get('https://api.open-meteo.com/v1/forecast', $options);

            if ($response->successful()) {
                return $response->json();
            } else {
                throw new \Exception('Unable to fetch weather data - status: ' .  $response->status());
            }
        });
    }
}
