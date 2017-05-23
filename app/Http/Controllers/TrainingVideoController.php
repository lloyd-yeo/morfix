<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingVideoController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    function index(Request $request, $type) {
        if ($type == "training-morfix") {
            return view('training.morfix', [
                
            ]);
        } else if ($type == "training-affiliate") {
            return view('training.affiliate', [
                
            ]);
        } else if ($type == "training-6figureprofile") {
            return view('training.6figure', [
                
            ]);
        }
    }
}
