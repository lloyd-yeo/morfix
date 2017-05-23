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
            return view('training.affiliate', [
                
            ]);
        } else if ($type == "6figureprofile") {
            return view('training.6figure', [
                
            ]);
        }
    }
}
