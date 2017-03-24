<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;

class InteractionsController extends Controller {

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $instagram_profiles = InstagramProfile::where('id', $id)
                                ->get();
        $niches = Niche::all();
        return view('interactionsettings', [
            'user_ig_profiles' => $instagram_profiles,
            'niches' => $niches,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
    
    public function toggleLike($id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->auto_like = ($instagram_profile->auto_like + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_like == 1) {
                $response = "Your auto like function has been turned <b>on</b>.";
            } else {
                $response = "Your auto like function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => fail, 'message' => $response));
        }
    }
    
    public function toggleComment($id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->auto_comment = ($instagram_profile->auto_comment + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_comment == 1) {
                $response = "Your auto comment function has been turned <b>on</b>.";
            } else {
                $response = "Your auto comment function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => fail, 'message' => $response));
        }
    }
    
    public function toggleFollow($id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->auto_follow = ($instagram_profile->auto_follow + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_follow == 1) {
                $response = "Your auto follow function has been turned <b>on</b>.";
            } else {
                $response = "Your auto follow function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => fail, 'message' => $response));
        }
    }
    
    public function toggleUnfollow($id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $instagram_profile->auto_unfollow = ($instagram_profile->auto_unfollow + 1) % 2;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_unfollow == 1) {
                $response = "Your auto unfollow function has been turned <b>on</b>.";
            } else {
                $response = "Your auto unfollow function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => fail, 'message' => $response));
        }
    }
    
    public function toggleNiche(Request $request, $id) {
        $instagram_profile = InstagramProfile::where('id', $id)->first();
        $niche = $request->input('niche');
        $instagram_profile->niche = $niche;
        $response = "There has been an error with the server. Please contact live support.";
        if ($instagram_profile->save()) {
            if ($instagram_profile->auto_unfollow == 1) {
                $response = "Your auto unfollow function has been turned <b>on</b>.";
            } else {
                $response = "Your auto unfollow function has been turned <b>off</b>.";
            }
            return Response::json(array("success" => true, 'message' => $response));
        } else {
            return Response::json(array("success" => fail, 'message' => $response));
        }
    }
}
