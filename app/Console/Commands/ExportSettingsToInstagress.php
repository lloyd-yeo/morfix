<?php

namespace App\Console\Commands;

use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use Illuminate\Console\Command;
use App\InstagramProfile;
use Log;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use Response;

class ExportSettingsToInstagress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exportinstagress:settings {username} {gress_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export Morfix settings to Instagress settings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "Command starting \n";
        $insta_username = $this->argument("username");
        $gress_id = $this->argument('gress_id');

        if ($insta_username) {
            echo "Username given \n";
            $ig_profile = InstagramProfile::where('insta_username', $this->argument("username"))->get();

            if($ig_profile){

                echo "Username exists \n";
                //retrieve hashtags
                $tags = array();
                $interaction_hashtags = InstagramProfileTargetHashtag::where('insta_username', $insta_username)->get();

                foreach ($interaction_hashtags as $interaction_hashtag) {
                    $tags[] = $interaction_hashtag->hashtag;
                }
                dump($tags . "\n");

                $usernames = array();
                $interaction_usernames = InstagramProfileTargetUsername::where('insta_username', $insta_username)->get();

                foreach ($interaction_usernames as $interaction_username) {
                    $target_username = $interaction_username->target_username;
                    $valid_username = $this->searchUsernames($gress_id, $target_username);

                    if ($valid_username){
                        $usernames[] = $interaction_username->target_username;
                    }

                }

                dump($usernames . "\n");

            }
        }
    }

    public function searchUsernames($gress_id, $target_username) {
        $client = new Client();

        $token = '1d73c7c1b10f05f2048f54083e9381ac18f36261a98416a00b7eed6b00d56eb4';

        echo"[GET USERNAME DETAILS] Calling activity status api for session data...";

        try {
            $response = $client->get("https://gress.io/api/activity/settings/usernames/search?token=" . $token . "&id=" . $gress_id . "&q=" . $target_username);

            echo"[GET USERNAME DETAILS] Finished making call to Instagress Endpoint now...";

            $content = $response->getBody()->getContents();
            echo"[GET USERNAME DETAILS] Reponse rcvd: " . $content;

            $result_json = json_decode($content, true);

            // Instagress return in lowercase true
            if ($result_json["ok"]) {
                echo"[GET USERNAME DETAILS] Rcvd successful response...";
                $usernames = $result_json['usernames'];
                echo $usernames;
            }
            else{
                return false;
            }
        } catch (RequestException $e) {
            echo"[GET USERNAME DETAILS] Request Exception encountered: " . $e->getMessage();
            echo"[GET USERNAME DETAILS] Request Body: " . $e->getRequest()->getBody()->getContents();
        }
    }

//    public function addUsernames(Request $request)
//    {
//        $ig_profile = InstagramProfile::find($request->input('profile_id'));
//        $blacklist = $request->input('blacklist');
//
//        Log::info("request is " . serialize($request->all()));
//
//        if ($ig_profile == NULL) {
//            return response()->json(['success' => FALSE]);
//        }
//
//        $ig_profile_setting = InteractionSetting::where('instagram_profile_id', $request->input('profile_id'))
//            ->first();
//
//        if ($ig_profile_setting == NULL) {
//            $ig_profile_setting = new InteractionSetting;
//            $ig_profile_setting->instagram_profile_id = $request->input('profile_id');
//            $ig_profile_setting->save();
//        }
//
//        $usernames = $request->input('usernames');
//
//
//        foreach ($usernames as $username) {
//            if (InteractionUsername::where('gress_id', $username["id"])
//                    ->where('instagram_profile_id', $request->input('profile_id'))
//                    ->first() == NULL) {
//                $ig_profile_username = new InteractionUsername;
//                $ig_profile_username->instagram_profile_id = $request->input('profile_id');
//                $ig_profile_username->blacklist =  $blacklist;
//                $ig_profile_username->username = $username["username"];
//                $ig_profile_username->gress_id = $username["id"];
//                $ig_profile_username->gress_full_name = $username["full_name"];
//                $ig_profile_username->gress_profile_picture = $username["profile_picture"];
//                $ig_profile_username->save();
//            }
//        }
//
//        $this->updateUsernameList($ig_profile, $blacklist);
//
//        $interaction_usernames = InteractionUsername::where('instagram_profile_id', $request->input('profile_id'))
//            ->where('blacklist',$blacklist)
//            ->get();
//        if($blacklist){
//            return view('interaction.tagsinput.bl-username-result-div', [ 'bl_usernames' => $interaction_usernames ]);
//        }else{
//            return view('interaction.tagsinput.username-result-div', [ 'usernames' => $interaction_usernames ]);
//        }
//    }
//    public function addHashtags(Request $request)
//    {
//        $ig_profile = InstagramProfile::find($request->input('profile_id'));
//        $blacklist = $request->input('blacklist');
//
//        if ($ig_profile == NULL) {
//            return response()->json(['success' => FALSE]);
//        }
//
//        $ig_profile_setting = InteractionSetting::where('instagram_profile_id', $request->input('profile_id'))
//            ->first();
//
//        if ($ig_profile_setting == NULL) {
//            $ig_profile_setting = new InteractionSetting;
//            $ig_profile_setting->instagram_profile_id = $request->input('profile_id');
//            $ig_profile_setting->save();
//        }
//        $ig_profile_setting = InteractionSetting::where('instagram_profile_id', $request->input('profile_id'))
//            ->first();
//
//        if ($ig_profile_setting == NULL) {
//            $ig_profile_setting = new InteractionSetting;
//            $ig_profile_setting->instagram_profile_id = $request->input('profile_id');
//            $ig_profile_setting->save();
//        }
//
//        $tags_csv = $request->input('tags');
//
//        $tags = explode(',', $tags_csv);
//
//        foreach ($tags as $tag) {
//            if (InteractionHashtag::where('hashtag', $tag)
//                    ->where('instagram_profile_id', $request->input('profile_id'))
//                    ->where('blacklist', $blacklist)
//                    ->first() == NULL) {
//                $ig_profile_hashtag = new InteractionHashtag;
//                $ig_profile_hashtag->instagram_profile_id = $request->input('profile_id');
//                $ig_profile_hashtag->blacklist = $blacklist;
//                $ig_profile_hashtag->hashtag = $tag;
//                $ig_profile_hashtag->save();
//            }
//        }
//
//        $this->updateHashtagList($ig_profile, $blacklist);
//
//        $interaction_hashtags = InteractionHashtag::where('instagram_profile_id', $request->input('profile_id'))
//            ->where('blacklist', $blacklist)
//            ->get();
//
//        if ($blacklist) {
//            return view('interaction.tagsinput.bl-tag-result-div', ['bl_tags' => $interaction_hashtags]);
//        } else {
//            return view('interaction.tagsinput.tag-result-div', ['tags' => $interaction_hashtags]);
//        }
//    }
}
