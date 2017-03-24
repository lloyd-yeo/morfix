<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;


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
        
        $instagram_profiles = DB::table('morfix_instagram_profiles')
                ->where('email', Auth::user()->email)
                ->take(10)
                ->get();
        
        return view('interactions', [
            'user_ig_profiles' => $instagram_profiles,
        ]);
    }
    
}
