<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class Potd {
    public static function getPotd()
    {
        $items = config('potd');
        $currentDayOfYear = date('z');

        // Calculate the index based on the current day
        $index = $currentDayOfYear % count($items);

        // Get the item for today
        $today = $items[$index];

        $image = 'potd/' . $today['src'];
        $compressedPath = 'compressed/' . $image;

        $compressed = Storage::disk('images')->get($compressedPath);

        if ($compressed === null) {
            // Compress and output
            $file = Storage::disk('images')->get($image);
            $image = Image::read($file);

            $compressed = $image
                ->scale(height: 800)
                ->toJpeg(60);

            Storage::disk('images')->put($compressedPath, $compressed);
        }

        $compressed = Image::read($compressed);

        return [
            'src' => $compressedPath,
            'width' => $compressed->width(),
            'height' => $compressed->height(),
            'artist' => $today['artist'],
            'title' => $today['title'],
            'year' => $today['year'],
        ];
    }
}
