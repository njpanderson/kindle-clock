<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateTime
{
    public static function relativeDayName(Carbon $date)
    {
        if ($date->isToday()) {
            return 'Today';
        } elseif ($date->isTomorrow()) {
            return 'Tomorrow';
        } else {
            return $date->format('l');
        }
    }
}
