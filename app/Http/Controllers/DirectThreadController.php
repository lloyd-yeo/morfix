<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DmJob;

class DirectThreadController extends MorfixController
{
	function __construct()
	{
		$this->model = new DmJob();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$instagram_profiles = InstagramProfile::where('email', Auth::user()->email)
			->take(Auth::user()->num_acct)->get();
		return view('dm.thread.profiles', [
			'user_ig_profiles' => $instagram_profiles,
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
