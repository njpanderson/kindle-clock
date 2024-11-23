<?php

return [

    'api_key' => env('OPENWEATHER_API_KEY', ''),

    'lang' => env('OPENWEATHER_API_LANG', 'en'),

    'date_format' => 'm/d/Y',
    'time_format' => 'h:i A',
    'day_format' => 'l',

    /**
     * Unit Configuration
     * --------------------------------------
     * Available units are c, f, k. (k is default)
     *
     * For temperature in Fahrenheit (f) and wind speed in miles/hour, use units=imperial
     * For temperature in Celsius (c) and wind speed in meter/sec, use units=metric
     */

    'temp_format' => 'c',
];
