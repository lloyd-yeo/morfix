<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
use App\InstagramProfile;

class EngagementGroupManual extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'engagementgroup:like {media_id} {comment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run engagement group on a media_id.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $ig_profiles = InstagramProfile::where('checkpoint_required', 0)
                ->where('account_disabled', 0)
                ->where('invalid_user', 0)
                ->where('incorrect_pw', 0)
                ->where('invalid_proxy', 0)
                ->get();

        $mediaId = $this->argument('media_id');

        $default_comments = array();
        $default_comments[] = "That is really insta-worthy.";
        $default_comments[] = "Seriously. That's a awesome photo!";
        $default_comments[] = "Really love your feeds. Keep it coming!";
        $default_comments[] = "Your photos look really really good!";
        $default_comments[] = "Those are some good-looking photos!";
        $default_comments[] = "That's an amazing shot!";
        $default_comments[] = "Keep it going! I really like your feed.";
        $default_comments[] = "Keep the photos coming!";
        $default_comments[] = "That is a breathtaking photo! Nicely done!";
        $default_comments[] = "Nice photo! I love your feed!";
        $default_comments[] = "I really love this photo.";
//        $default_comments[] = "";

        foreach ($ig_profiles as $ig_profile) {

            $ig_username = $ig_profile->insta_username;
            $ig_password = $ig_profile->insta_pw;

            $config = array();
            $config["storage"] = "mysql";
            $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
            $config["dbtablename"] = "instagram_sessions";

            $debug = false;
            $truncatedDebug = false;
            $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);

            if ($ig_profile->proxy === NULL) {
                $proxy = Proxy::inRandomOrder()->first();
                $ig_profile->proxy = $proxy->proxy;
                $ig_profile->save();
                $proxy->assigned = $proxy->assigned + 1;
                $proxy->save();
            }

            $instagram->setProxy($ig_profile->proxy);
            #$instagram->setUser($ig_username, $ig_password);

            try {
                $explorer_response = $instagram->login($ig_username, $ig_password);
            } catch (\InstagramAPI\Exception\InvalidUserException $invalid_user_ex) {
                $ig_profile->invalid_user = 1;
                $ig_profile->save();
                continue;
            } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                continue;
            } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                $ig_profile->checkpoint_required = 1;
                $ig_profile->save();
                continue;
            } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                continue;
            } catch (\InstagramAPI\Exception\BadRequestException $badrequest_ex) {
                continue;
            } catch (\InstagramAPI\Exception\ForcedPasswordResetException $forcedpwreset_ex) {
                $ig_profile->incorrect_pw = 1;
                $ig_profile->save();
                continue;
            } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                $ig_profile->incorrect_pw = 1;
                $ig_profile->save();
                continue;
            } catch (\InstagramAPI\Exception\AccountDisabledException $accountdisabled_ex) {
                $ig_profile->invalid_user = 1;
                $ig_profile->save();
                continue;
            } catch (\InstagramAPI\Exception\ChallengeRequiredException $challenge_ex) {
                $ig_profile->checkpoint_required = 1;
                $ig_profile->save();
                continue;
            } catch (\InstagramAPI\Exception\SentryBlockException $sentryblock_ex) {
                continue;
            }

            try {
                $response = $instagram->media->like($mediaId);

                if ($ig_profile->owner()->trial_activation === 1) {
                    
                } else {
                    if ($ig_profile->auto_comment === 1) {
                        if ($this->argument('comment') == 1) {
                            $comments = \App\InstagramProfileComment::where('insta_username', $ig_profile->insta_username)->get();
                            if (count($comments) > 0) {
                                $comment = $comments->random();
                                if (!empty($comment->comment)) {
                                    $instagram->media->comment($mediaId, $comment->comment);
                                }
                            }
                        }
                    }
                }
            } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_required_ex) {
                continue;
            } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                continue;
            } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                $ig_profile->checkpoint_required = 1;
                $ig_profile->save();
                continue;
            } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                continue;
            } catch (\InstagramAPI\Exception\BadRequestException $badrequest_ex) {
                continue;
            } catch (\InstagramAPI\Exception\LoginRequiredException $loginrequired_ex) {
                continue;
            }

            var_dump($response);
        }
    }

}
