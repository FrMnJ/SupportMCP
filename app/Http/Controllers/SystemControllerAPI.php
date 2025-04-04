<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\System;

class SystemControllerAPI extends Controller
{
    public function index()
    {
        $systems = System::all();
        $systems = $systems->map(function ($system) {
            return $system->toDto();
        });
        return response()->json($systems, 200);
    }

}