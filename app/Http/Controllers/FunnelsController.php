<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FunnelsController extends Controller
{
    public function show(){
        return view('funnels.ebook');
    }

    public function test(){
        return "test";
    }
}
