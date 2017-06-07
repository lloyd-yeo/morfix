<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingVideoController extends Controller {

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

            $iframe_setup = "<iframe src=\"https://player.vimeo.com/video/214766168\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_bio = "<iframe src=\"https://player.vimeo.com/video/215135861\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_secure = "<iframe src=\"https://player.vimeo.com/video/214766196\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_accttype = "<iframe src=\"https://player.vimeo.com/video/214766217\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_content = "<iframe src=\"https://player.vimeo.com/video/214766262\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_content2 = "<iframe src=\"https://player.vimeo.com/video/214766286\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_content3 = "<iframe src=\"https://player.vimeo.com/video/214765973\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_createcontent = "<iframe src=\"https://player.vimeo.com/video/215135912\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_posttiming = "<iframe src=\"https://player.vimeo.com/video/214766000\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_repost = "<iframe src=\"https://player.vimeo.com/video/214766014\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_whotofollow = "<iframe src=\"https://player.vimeo.com/video/215135955\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_hashtag = "<iframe src=\"https://player.vimeo.com/video/214766033\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_engagementgroup = "<iframe src=\"https://player.vimeo.com/video/215135978\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_analytics = "<iframe src=\"https://player.vimeo.com/video/214766068\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_shoutout = "<iframe src=\"https://player.vimeo.com/video/214766094\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_influencer = "<iframe src=\"https://player.vimeo.com/video/214766125\" width=\"640\" height=\"1138\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_rates = "<iframe src=\"https://player.vimeo.com/video/214766149\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";

            $morfix_training_video = array(
                "ig-setup" => $iframe_setup,
                "ig-bio" => $iframe_bio,
                "ig-secure" => $iframe_secure,
                "ig-accttype" => $iframe_accttype,
                "ig-content" => $iframe_content,
                "ig-content2" => $iframe_content2,
                "ig-content3" => $iframe_content3,
                "ig-createcontent" => $iframe_createcontent,
                "ig-posttiming" => $iframe_posttiming,
                "ig-repost" => $iframe_repost,
                "ig-whotofollow" => $iframe_whotofollow,
                "ig-hashtag" => $iframe_hashtag,
                "ig-engagementgroup" => $iframe_engagementgroup,
                "ig-analytics" => $iframe_analytics,
                "ig-shoutout" => $iframe_shoutout,
                "ig-influencer" => $iframe_influencer,
                "ig-rates" => $iframe_rates
            );

            $morfix_training_video_header = array(
                "ig-setup" => "Setting up Instagram to Win",
                "ig-bio" => "Bio & Link in Bio",
                "ig-secure" => "Securing your Account",
                "ig-accttype" => "Types of Accounts",
                "ig-content" => "Finding Great Content (Part 1)",
                "ig-content2" => "Finding Great Content (Part 2)",
                "ig-content3" => "Finding Great Content (Part 3)",
                "ig-createcontent" => "Creating Instagram Content",
                "ig-posttiming" => "Timing to Post",
                "ig-repost" => "How to Repost",
                "ig-whotofollow" => "Who to Follow",
                "ig-hashtag" => "Finding Hashtags",
                "ig-engagementgroup" => "Engagement Group",
                "ig-analytics" => "Instagram Analytics",
                "ig-shoutout" => "Shoutout",
                "ig-influencer" => "Reaching Out to Influencers",
                "ig-rates" => "Negotiate Rates");

            return view('training.6figure', [
                'morfix_training_video' => $morfix_training_video,
                'morfix_training_video_header' => $morfix_training_video_header,
            ]);
        } else if ($type == "fbadsbasic") {

            $iframe_dashboard = "<iframe src=\"https://player.vimeo.com/video/192896299\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            $iframe_profile = "<iframe src=\"https://player.vimeo.com/video/192896315\" width=\"640\" height=\"400\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
            
            $morfix_training_video = array("dashboard" => $iframe_dashboard, "profile" => $iframe_profile);

            $morfix_training_video_header = array("dashboard" => "Part 1 - Dashboard", "profile" => "Part 2 - Profile");

            return view('training.morfix', [
                'morfix_training_video' => $morfix_training_video,
                'morfix_training_video_header' => $morfix_training_video_header,
            ]);
        }
    }

}
