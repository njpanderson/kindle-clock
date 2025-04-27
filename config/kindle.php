<?php

return [
    'ip' => env('KINDLE_IP'),

    'brightness' => [
        'initial' => 1,
        'min' => 0,
        'max' => 10,

        /**
         * Set auto.enabled to true if you want the backlight light level to be
         * controlled by the sun position (sunrise - sunset)
         */
        'auto' => [
            'enabled' => true,
            // Brightness in the day
            'day' => 0,
            // Brightness at night
            'night' => 1
        ]
    ],

    'location' => [
        // Default to central London
        'lat' => env('LOCATION_LAT', '51.50605855145484'),
        'lng' => env('LOCATION_LNG', '-0.1236284121480435')
    ]
];
