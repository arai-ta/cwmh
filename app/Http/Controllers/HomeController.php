<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home')
            ->with('sourceUrl', env('APP_SOURCE_DIST_URL', 'https://github.com/arai-ta/cwmh'));
    }
}
