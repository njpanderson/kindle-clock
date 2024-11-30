<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageController extends Controller
{
    public function image(
        $image
    ) {
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
        } else {
            // Output existing image
            $compressed = Image::read($compressed)->encode();
        }

        $headers = [
            'Content-Type' => $compressed->mediaType(),
            'Content-Disposition' => 'inline; filename="' . basename($compressedPath) . '"',
        ];

        return response($compressed, 200, $headers);
    }
}
