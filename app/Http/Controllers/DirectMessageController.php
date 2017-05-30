<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\InstagramProfile;
use App\DmJob;

class DirectMessageController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $instagram_profiles = InstagramProfile::where('email', Auth::user()->email)->take(10)->get();
        return view('dm', [
            'user_ig_profiles' => $instagram_profiles,
        ]);
    }
    
}
