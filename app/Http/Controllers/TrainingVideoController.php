<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingVideoController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    function index(Request $request, $type) {
        if ($type == "morfix") {
            
            $iframe_dashboard = "<iframe src=\"https://player.vimeo.com/video/192896299\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_profile = "<iframe src=\"https://player.vimeo.com/video/192896315\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_autointeraction = "<iframe src=\"https://player.vimeo.com/video/192896261\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_directmessage = "<iframe src=\"https://player.vimeo.com/video/192896239\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_photoscheduler = "<iframe src=\"https://player.vimeo.com/video/192896326\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_dmalgorithm = "<iframe src=\"https://player.vimeo.com/video/202704134\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";

            $morfix_training_video = array("dashboard" => $iframe_dashboard, "profile" => $iframe_profile,
                "autointeraction" => $iframe_autointeraction, "directmessage" => $iframe_directmessage,
                "photoscheduling" => $iframe_photoscheduler, "dmalgorithm" => $iframe_dmalgorithm);

            $morfix_training_video_header = array("dashboard" => "Part 1 - Dashboard", "profile" => "Part 2 - Profile",
                "autointeraction" => "Part 3 - Auto Interactions", "directmessage" => "Part 4 - Direct Messages",
                "photoscheduling" => "Part 5 - Photo Scheduling", "dmalgorithm" => "Part 6 - Beating the DM Algorithm");

            return view('training.morfix', [
                'morfix_training_video' => $morfix_training_video,
                'morfix_training_video_header' => $morfix_training_video_header,
            ]);
            
        } else if ($type == "affiliate") {
            
            $iframe_welcome = "<iframe src=\"https://player.vimeo.com/video/199810006\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_comms = "<iframe src=\"https://player.vimeo.com/video/199810127\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_affiliatetrg = "<iframe src=\"https://player.vimeo.com/video/199810177\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_affiliatearea = "<iframe src=\"https://player.vimeo.com/video/201139129\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_optin = "<iframe src=\"https://player.vimeo.com/video/201139252\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_autoresponder = "<iframe src=\"https://player.vimeo.com/video/201139168\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_ebook = "<iframe src=\"https://player.vimeo.com/video/216167875\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";

            $morfix_training_video = array("i-welcome" => $iframe_welcome, "i-comms" => $iframe_comms,
                "i-affiliatetrg" => $iframe_affiliatetrg, "i-affiliatearea" => $iframe_affiliatearea,
                "i-optin" => $iframe_optin, "i-autoresponder" => $iframe_autoresponder, "i-ebook" => $iframe_ebook);

            $morfix_training_video_header = array(
                "i-welcome" => "Part 1 - Welcome Message", 
                "i-comms" => "Part 2 - Commission Plan",
                "i-affiliatetrg" => "Part 3 - Affiliate Training", 
                "i-affiliatearea" => "Part 4 - Affiliate Area",
                "i-optin" => "Part 5 - Opt In Page", 
                "i-autoresponder" => "Part 6 - Auto Responder", 
                "i-ebook" => "Part 7 - Bonus Ebook");
            
            return view('training.affiliate', [
                'morfix_training_video' => $morfix_training_video,
                'morfix_training_video_header' => $morfix_training_video_header,
            ]);
            
        } else if ($type == "6figureprofile") {
            return view('training.6figure', [
                
            ]);
        }
    }
}
