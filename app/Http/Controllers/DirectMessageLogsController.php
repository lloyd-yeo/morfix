<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\InstagramProfile;
use App\DmJob;
use Response;

class DirectMessageLogsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    
    public function index(Request $request, $id) {
        $instagram_profiles = InstagramProfile::find('id', $id);
        $dm_jobs = DmJob::where('insta_username', $instagram_profiles->insta_username)->orderBy('job_id', 'asc')->take(10)->get();
        return view('dm.log', [
            'dm_job' => $dm_jobs,
        ]);
    }
}
