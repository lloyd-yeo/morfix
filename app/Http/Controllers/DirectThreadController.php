<?php

namespace App\Http\Controllers;

use App\DmJob;
use Illuminate\Http\Request;
use App\DmThread;

class DirectThreadController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
	    $response = array();
		$instagram_profiles = InstagramProfile::where('email', Auth::user()->email)
			->take(Auth::user()->num_acct)->get();
		if($instagram_profiles){
            $thread = DmThread::where('user_id', $instagram_profiles->user_id)
                ->leftJoin('dm_thread_item', 'dm_thread.thread_id', 'dm_thread_item.thread_id')
                ->leftJoin('dm_thread_users', 'dm_thread.thread_id', 'dm_thread_users.thread_id')->get();
            $response['thread'] = $thread;
        }
		return view('dm.thread.profiles', [
			'user_thread' => $response['thread'],'instagram_profiles' => $instagram_profiles,
		]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return view('dm.thread.inbox', [
		]);
	}
}
