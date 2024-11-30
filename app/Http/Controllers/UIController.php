<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Potd;

class UIController extends Controller
{
    public function show() {
        return view('ui', [
            'potd' => Potd::getPotd()
        ]);
    }
}
