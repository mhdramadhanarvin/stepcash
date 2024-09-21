<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ConsoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request, $command)
    {
        $params = $request->all();
        Artisan::call($command, $params);
        $output = Artisan::output();
        return $output;
    }
}
