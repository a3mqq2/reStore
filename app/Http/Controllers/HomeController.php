<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\MoogoldController;

class HomeController extends Controller
{
    public function home() {
        $smileone = new SmileOneController();
        $moogold = new MoogoldController();

        // Safe access with default fallback values
        $smileone_response = $smileone->queryPoints();
        $balance = $smileone_response['smile_points'] ?? 0;

        $moogold_response = $moogold->getBalance();
        $moogold_balance = $moogold_response['balance'] ?? 0;

        return view('home',compact('balance','moogold_balance'));
    }
}
