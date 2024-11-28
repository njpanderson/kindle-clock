<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Process;
use Illuminate\Http\Request;

use App\Services\KindleService;
use Illuminate\Support\Facades\Validator;

class KindleController extends Controller
{
    public function __construct(
        public KindleService $kindle
    ) {
        //
    }

    /**
     * Set up the kindle by sending various commands
     */
    public function setup()
    {
        // Disable screensaver
        $this->kindle->run('lipc-set-prop com.lab126.powerd preventScreenSaver 1');

        // Disable front light auto levelling
        $this->kindle->run('lipc-set-prop com.lab126.powerd flAuto 0');

        // Set initial front light brightness
        $brightness = config('kindle.brightness.initial');
        $this->kindle->run(
            'lipc-set-prop com.lab126.powerd flIntensity ' . $brightness
        );

        return response()->json([
            'status' => 'ok',
            'brightness' => $brightness
        ]);
    }

    /**
     * Using the backlight-boost.sh script, will boost the backlight of the
     * Kindle based on ambient light. Will set the light to a maximum of 10.
     */
    public function boostFrontLight()
    {
        $result = $this->kindle->run('/mnt/us/kindle-clock/fl-boost.sh');

        if (preg_match('/(\{.*?\})/', $result, $matches)) {
            return response()->json(json_decode($matches[0]));
        } else {
            return response()->json([
                'error' => 'Could not set backlight level'
            ]);
        }
    }

    /**
     * Set the Kindle brightness (0 - 25)
     */
    public function setBrightness($brightness = 0)
    {
        Validator::make([
            'brightness' => $brightness
        ], [
            'brightness' => [
                'required',
                'integer',
                'min:0',
                'max:25'
            ]
        ])->validate();

        $result = $this->kindle->run('lipc-set-prop com.lab126.powerd flIntensity ' . $brightness);

        return response()->json($result);
    }

    public function getBrightness()
    {
        $result = $this->kindle->run('lipc-get-prop com.lab126.powerd flIntensity');

        if (preg_match('/\d+/', $result, $matches)) {
            return response()->json((int) $matches[0]);
        } else {
            return response()->json([
                'error' => 'Could not get front light status'
            ]);
        }
    }

    /**
     * Get the ambient light sensor readout in lux
     * Can be 0 - 65535 but a nice range is around 0 - 100
     */
    public function getAlsLux()
    {
        $result = $this->kindle->run('lipc-get-prop com.lab126.powerd alsLux');

        if (preg_match('/\d+/', $result, $matches)) {
            return response()->json((int) $matches[0]);
        } else {
            return response()->json([
                'error' => 'Could not get ambient light status'
            ]);
        }
    }
}
