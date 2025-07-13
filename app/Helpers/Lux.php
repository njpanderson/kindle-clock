<?php

namespace App\Helpers;

class Lux
{
    /**
     * Get brightness for a given lux value based on config levels.
     *
     * @param int|float $lux
     * @return int|null  Returns brightness or null if not found
     */
    public static function getBrightnessForLux(int|float $lux)
    {
        $levels = config('kindle.brightness.auto.lux.levels');

        foreach ($levels as $level) {
            $min = isset($level['min']) ? $level['min'] : 0;
            $max = isset($level['max']) ? $level['max'] : INF;

            if ($lux >= $min && $lux <= $max) {
                return $level['brightness'];
            }
        }

        return null; // or a default value if desired
    }
}
