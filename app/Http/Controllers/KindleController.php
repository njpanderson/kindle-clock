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

    public function brightness($brightness = 0)
    {
        Validator::make([
            'brightness' => $brightness
        ], [
            'brightness' => [
                'required',
                'integer',
                'min:0',
                'max:30'
            ]
        ])->validate();

        $result = $this->kindle->run('lipc-set-prop com.lab126.powerd flIntensity ' . $brightness);

        return response()->json(['result' => $result]);
    }
}
