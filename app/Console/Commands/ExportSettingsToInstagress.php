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
                dump($tags);

                $usernames = array();
                $interaction_usernames = InstagramProfileTargetUsername::where('insta_username', $insta_username)->get();

                foreach ($interaction_usernames as $interaction_username) {
                    $target_username = $interaction_username->target_username;
                    $gress_username = $this->searchUsernames($gress_id, $target_username);

                    if ($gress_username){
                        $usernames[] =  array("id" => $gress_username["id"], "username" => $gress_username["username"],
                            "full_name" => $gress_username["full_name"], "profile_picture" => $gress_username["profile_picture"]);
                    }

                }

                dump($usernames);

                echo"Moving to Calling Api to update usernames";

                $this->addUsernames($gress_id, $usernames);

                $this->addHashtags($gress_id, $tags);

            }
        }
    }

    public function searchUsernames($gress_id, $target_username) {
        $client = new Client();

        $token = '1d73c7c1b10f05f2048f54083e9381ac18f36261a98416a00b7eed6b00d56eb4';

        echo"[GET USERNAME DETAILS] Calling activity status api for session data... \n";

        try {
            $response = $client->get("https://gress.io/api/activity/settings/usernames/search?token=" . $token . "&id=" . $gress_id . "&q=" . $target_username);

            echo"[GET USERNAME DETAILS] Finished making call to Instagress Endpoint now... \n";

            $content = $response->getBody()->getContents();
//            echo"[GET USERNAME DETAILS] Reponse rcvd: " . $content;

            $result_json = json_decode($content, true);

            // Instagress return in lowercase true
            if ($result_json["ok"]) {
                echo"[GET USERNAME DETAILS] Rcvd successful response... \n";
                $usernames = $result_json['usernames'];
                if($usernames[0]["username"] == $target_username){
                    echo"[GET USERNAME DETAILS] FIRST USERNAME IS CORRECT! \n";
                    return $usernames[0];
                }
            }
            else{
                return false;
            }
        } catch (RequestException $e) {
            echo"[GET USERNAME DETAILS] Request Exception encountered: " . $e->getMessage();
            echo"[GET USERNAME DETAILS] Request Body: " . $e->getRequest()->getBody()->getContents();
        }
    }

    public function addUsernames($gress_id, $usernames)
    {
        $client = new Client();
        $token = '1d73c7c1b10f05f2048f54083e9381ac18f36261a98416a00b7eed6b00d56eb4';
        try {
                $response = $client->post('https://gress.io/api/activity/settings/set?token=' . $token, [
                    'json' => [
                        'id' => $gress_id,
                        'settings' => [
                            'usernames' => $usernames,
                        ]
                    ]
                ]);


            echo"[ADD USERNAME] Finished making call to Instagress Endpoint now... \n";
            $content = $response->getBody()->getContents();
            echo "[ADD USERNAME] Reponse rcvd: " . $content . "\n";

            return json_decode($content, true);

        } catch (RequestException $e) {
            echo"[ADD USERNAME] Request Exception encountered: " . $e->getMessage() . "\n";
            echo"[ADD USERNAME] Request Body: " . $e->getRequest()->getBody()->getContents() . "\n";
        }
    }
    public function addHashtags($gress_id, $tags)
    {
        $client = new Client();
        $token = '1d73c7c1b10f05f2048f54083e9381ac18f36261a98416a00b7eed6b00d56eb4';
        try {
                $response = $client->post('https://gress.io/api/activity/settings/set?token=' . $token, [
                    'json' => [
                        'id' => $gress_id,
                        'settings' => [
                            'tags' => $tags,
                        ]
                    ]
                ]);


            echo"[ADD HASHTAG] Finished making call to Instagress Endpoint now... \n";
            $content = $response->getBody()->getContents();
            echo"[ADD HASHTAG] Reponse rcvd: " . $content . "\n";

            return json_decode($content, true);

        } catch (RequestException $e) {
            echo"[ADD HASHTAG] Request Exception encountered: " . $e->getMessage(). "\n";
            echo"[ADD HASHTAG] Request Body: " . $e->getRequest()->getBody()->getContents() . "\n";
        }
    }
}
