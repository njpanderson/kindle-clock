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

            /**
             * Mode — either lux or sun:
             *  - lux: uses the ambient light levels to determine brightness
             *         by polling the kindle for its sensor readout at regular
             *         intervals
             *  - sun: less intensive or accurate to ambient light, uses the
             *         sunrise/set times to determine light levels.
             */
            'mode' => 'lux',

            /**
             * Lux mode options
             * An array of min/max lux settings which define the brightness
             * level of the front light. If neither min or max is defined, it is
             * assumed to be the minimum/maximum possible value.
             */
            'lux' => [
                'levels' => [[
                    'max' => 10,
                    'brightness' => 1
                ], [
                    'min' => 11,
                    'max' => 20,
                    'brightness' => 2
                ], [
                    'min' => 21,
                    'brightness' => 0
                ]]
            ],

            // Sun mode options
            'sun' => [
                // Brightness in the day
                'day' => 0,
                // Brightness at night
                'night' => 1
            ]
        ]
    ],

    'location' => [
        // Default to central London
        'lat' => env('LOCATION_LAT', '51.50605855145484'),
        'lng' => env('LOCATION_LNG', '-0.1236284121480435')
    ]
];
