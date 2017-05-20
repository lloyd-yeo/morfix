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
use App\InstagramProfilePhotoPostSchedule;
use App\StripeDetail;

class PaymentController extends Controller
{
    function upgrade(Request $request, $plan) {
        \Stripe\Stripe::setApiKey("sk_test_dAO7D2WkkUOHnuHgXBeti0KM");
        
        if ($plan == "Premium") {
            $plan_id = "0137";
            
        } else if ($plan == "Pro") {
            $plan_id = "MX370";
            
        } else if ($plan == "Business") {
            $plan_id = "0297";
            
        } else if ($plan == "Mastermind") {
            $plan_id = "MX970";
            
        }
        
        return response()->json(["plan" => $plan]);
        #return view('payment.index', [
        #]);
    }
}
