<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MarketingController extends Controller
{
    public function landing(): View
    {
        return view('marketing.landing');
    }
}
