<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\DefaultImageGallery;
use App\UserImages;

class PostSchedulingController extends Controller {

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
        return view('postscheduling', [
            'user_ig_profiles' => $instagram_profiles,
        ]);
    }

    /**
     * Display a gallery of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gallery($id) {
        $instagram_profiles = InstagramProfile::where('id', $id)
                ->get();
        $default_images = DefaultImageGallery::orderBy('image_id', 'desc')->get();
        $default_categories = DB::connection('mysql_old')->select("SELECT id, category FROM insta_affiliate.default_image_category;");
        $user_images = DB::connection('mysql_old')->select("SELECT * FROM insta_affiliate.user_images WHERE email = ?;", [Auth::user()->email]);
        $imgs = array();

        foreach ($default_categories as $category) { //populate categories first
            $imgs[$category->id] = array();
        }

        foreach ($default_images as $default_image) { //populate each category with images
            $imgs[$default_image->category_id][] = $default_image;
        }

        foreach ($default_categories as $category) { //populate categories first
            $imgs[$category->id] = collect($imgs[$category->id]);
        }

        return view('postscheduling.scheduling', [
            'user_ig_profiles' => $instagram_profiles,
            'default_imgs' => $default_images,
            'default_img_category' => $default_categories,
            'user_images' => $user_images,
            'imgs' => $imgs,
        ]);
    }

    public function add(Request $request) {
        if ($request->file('file')->isValid()) {
            $image = $request->file('file');
            $filename = Storage::putFile('public/uploads', $image, 'public');
            $filename = substr($filename, 7);
            
            $user_img = new UserImages;
            $user_img->email = Auth::user()->email;
            $user_img->image_path = $filename;
            $user_img->save();
            return response()->json(['success' => true, 'path' => $filename, 'id' => $user_img->id]);
        }
    }
    
    /**
     * Schedule a photo for posting
     *
     * @return \Illuminate\Http\Response
     */
    public function schedule(Request $request, $id) {
        $instagram_profiles = InstagramProfile::where('id', $id)->first();
        $insta_username = $instagram_profiles->insta_username;
        $image_id = $request->input('img_id');
        $user_img = UserImages::find($image_id);
        
        $instagram_post_schedule = new InstagramProfilePhotoPostSchedule;
        $instagram_post_schedule->insta_id = $instagram_profiles->id;
        if ($request->input('date_to_post') !== null) {
            $instagram_post_schedule->date_to_post = $request->input('date_to_post');
        }
        $instagram_post_schedule->image_path = $user_img->image_path;
        $instagram_post_schedule->caption = $request->input('caption');
        if ($request->input('first_comment') !== null) {
            $instagram_post_schedule->first_comment = $request->input('first_comment');
        }
        $instagram_post_schedule->save();
    }

}
