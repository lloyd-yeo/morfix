<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FunnelsController extends Controller
{
    public function ebook(){
        return view('funnels.ebook');
    }

    public function vsl(){
	    return view('funnels.onlinevsl');
    }

    public function rcVsl(){
	    return view('funnels.rcvsl');
    }

	public function mcaVsl(){
		return view('funnels.mcavsl');
	}

	public function daVsl(){
		return view('funnels.davsl');
	}

	public function ospVsl(){
		return view('funnels.ospvsl');
	}

	public function mmoVsl(){
		return view('funnels.mmovsl');
	}

	public function toolVsl(){
		return view('funnels.toolvsl');
	}

	public function mlmVsl(){
		return view('funnels.mlmvsl');
	}

}
