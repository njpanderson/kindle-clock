<?php

return [
    'ip' => env('KINDLE_IP'),

    'location' => [
        // Default to central London
        'lat' => env('LOCATION_LAT', '51.50605855145484'),
        'lng' => env('LOCATION_LNG', '-0.1236284121480435')
    ]
];
