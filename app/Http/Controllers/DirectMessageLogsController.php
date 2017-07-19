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
        $ig_profile = InstagramProfile::find($id);
        
        if ($ig_profile == NULL) {
            return redirect('home');
        }
        
        if ($ig_profile->email != Auth::user()->email) {
             return redirect('home');
        }
        
        $sent_dm_jobs = DmJob::where('insta_username', $ig_profile->insta_username)
                                ->where('fulfilled', 1)
                                ->orderBy('updated_at', 'desc')
                                ->take(10)
                                ->get();
        
        $pending_dm_jobs = DmJob::where('insta_username', $ig_profile->insta_username)->where('fulfilled', false)->orderBy('job_id', 'asc')->take(10)->get();
        
        return view('dm.log.index', [
            'sent_dm_jobs' => $sent_dm_jobs,
            'pending_dm_jobs' => $pending_dm_jobs,
            'ig_profile' => $ig_profile,
        ]);
    }
    
    public function cancel(Request $request, $id) {
        if (DmJob::destroy($id)) {
            return Response::json(array("success" => true, 'message' => "Your pending DM has been cancelled."));
        } else {
            return Response::json(array("success" => false, 'message' => "We've encountered an error please try again later."));
        }
    }
    
    public function cancelAllPendingJobs(Request $request, $insta_id) {
        $ig_profile = InstagramProfile::find($insta_id);
        
        if ($ig_profile == NULL) {
            return redirect('home');
        }
        
        if ($ig_profile->email != Auth::user()->email) {
             return redirect('home');
        }
        
        DmJob::where('fulfilled', 0)->where('insta_username', $ig_profile->insta_username)->update(['fulfilled', 2]);
        return Response::json(array("success" => true, 'message' => "Your pending DM has all been cancelled."));
    }
}
