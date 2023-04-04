<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        // Checking for api key 
        $settings = Setting::first() ?? false;
        return view('index', [
            'settings' => $settings
        ]);
    }
}
