<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\User;
use App\InstagramHelper;

class SendDm implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $profile;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\InstagramProfile $profile) {
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        DB::reconnect();

        $ig_profile = $this->profile;
        $insta_username = $ig_profile->insta_username;
        $insta_user_id = $ig_profile->insta_user_id;
        $insta_id = $ig_profile->insta_id;
        $insta_pw = $ig_profile->insta_pw;
        $auto_dm_delay = $ig_profile->auto_dm_delay;
        $temporary_ban = $ig_profile->temporary_ban;
        $insta_new_follower_template = $ig_profile->insta_new_follower_template;
        $follow_up_message = $ig_profile->follow_up_message;
        $proxy = $ig_profile->proxy;

        echo "[$insta_username] retrieved...\n";

        $dm_job = DmJob::where('insta_username', $insta_username)
                ->whereRaw('NOW() > time_to_send AND fulfilled = 0')
                ->orderBy('job_id', 'ASC')
                ->first();

        if ($dm_job === NULL) {
            echo "[$insta_username] does not have outstanding jobs...\n";
            return;
        } else {
            echo "[$insta_username] retrieved job...\n";

            if (trim($dm_job->message) == "") {
            	echo "DM Job is Empty\n";
                return;
            }
        }

        //Mark job as fulfilled (2) when the person has turned off follow-up-messaging 
        //and the message is a follow-up.
        if ($ig_profile->auto_dm_delay == 0 && $dm_job->follow_up_order == 1) {
	        echo "Profile auto_dm_delay is 0 AND DM Job has follow up order of 1.\n";
            $dm_job->fulfilled = 2;
            $dm_job->save();
            return;
        }

        try {

            $ig_username = $insta_username;
            $ig_password = $insta_pw;

            $instagram = InstagramHelper::initInstagram();
            if (!InstagramHelper::login($instagram, $ig_profile)) {
                return;
            }

            try {
                $delay = rand(50, 65);
                $recipients = array();
                $recipients["users"] = array();
                $recipients["users"][] = $dm_job->recipient_insta_id;
                $direct_msg_resp = $instagram->direct->sendText($recipients, $dm_job->message);
                echo $dm_job->recipient_insta_id . "\n";
                echo "[$insta_username] " . $dm_job->job_id . "\n";
                var_dump($direct_msg_resp);
                echo "[$insta_username] auto-delay: " . $auto_dm_delay . "\n";
                $dm_job->fulfilled = 1;
                $dm_job->updated_at = \Carbon\Carbon::now();
                $dm_job->save();

                if ($ig_profile->auto_dm_delay == 1) {
                    DmJob::where('insta_username', $insta_username)
                            ->where('fulfilled', 0)
                            ->where('recipient_insta_id', $dm_job->recipient_insta_id)
                            ->update(['time_to_send' => \Carbon\Carbon::tomorrow()]);
                }

                if (!is_null($temporary_ban)) {
                    $ig_profile->last_sent_dm = \Carbon\Carbon::tomorrow();
                    $ig_profile->temporary_ban = NULL;
                    $ig_profile->dm_probation = 1;
                    $ig_profile->save();
                } else {
                    $ig_profile->last_sent_dm = \Carbon\Carbon::now()->addMinutes($delay);
                    $ig_profile->dm_probation = 0;
                    $ig_profile->save();
                }
            } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                $this->handleInstagramException($ig_profile, $checkpoint_ex);
            } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                $this->handleInstagramException($ig_profile, $network_ex);
            } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                $this->handleInstagramException($ig_profile, $endpoint_ex);
            } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                $this->handleInstagramException($ig_profile, $incorrectpw_ex);
            } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
                $this->handleInstagramException($ig_profile, $feedback_ex);
            } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
                $this->handleInstagramException($ig_profile, $emptyresponse_ex);
            } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
                $this->handleInstagramException($ig_profile, $acctdisabled_ex);
            } catch (\InstagramAPI\Exception\ThrottledException $throttled_ex) {
                $this->handleInstagramException($ig_profile, $throttled_ex);
            }
        } catch (Exception $ex) {
            echo "[" . $insta_username . "] " . $ex->getMessage() . "\n";
            echo "[" . $insta_username . "] " . $ex->getTraceAsString() . "\n";
        }
    }

    private function handleInstagramException($ig_profile, $ex) {
        $ig_username = $ig_profile->insta_username;
        if (strpos($ex->getMessage(), 'Throttled by Instagram because of too many API requests') !== false) {
            $ig_profile->last_sent_dm = \Carbon\Carbon::now()->addHours(6);
            $ig_profile->save();
            echo "\n[$ig_username] has last_sent_dm shifted forward to " . \Carbon\Carbon::now()->addHours(6)->toDateTimeString() . "\n";
            return;
        } else if ($ex instanceof \InstagramAPI\Exception\FeedbackRequiredException) {
            if ($ex->hasResponse()) {
	            $feedback_response = $ex->getResponse()->asArray();
	            $feedback_msg = $feedback_response['feedback_message'];
                if (strpos($feedback_msg, 'This action was blocked. Please try again later. We restrict certain content and actions to protect our community. Tell us if you think we made a mistake') !== false) {
                    $ig_profile->last_sent_dm = \Carbon\Carbon::now()->addHours(6);
                    $ig_profile->temporary_ban = \Carbon\Carbon::now()->addHours(6);
                    $ig_profile->save();
                    echo "\n[$ig_username] was blocked & has last_sent_dm shifted forward to " . \Carbon\Carbon::now()->addHours(6)->toDateTimeString() . "\n";
                    return;
                } else if (strpos($feedback_msg, 'It looks like your profile contains a link that is not allowed') !== false) {
                    $ig_profile->last_sent_dm = \Carbon\Carbon::now()->addHours(1);
                    $ig_profile->invalid_proxy = 1;
                    $ig_profile->save();
                    echo "\n[$ig_username] has invalid proxy & last_sent_dm shifted forward to " . \Carbon\Carbon::now()->addHours(1)->toDateTimeString() . "\n";
                    return;
                } else if (strpos($feedback_msg, 'It looks like you were misusing this feature by going too fast') !== false) {
                    dump($ex);
                	$ig_profile->last_sent_dm = \Carbon\Carbon::now()->addHours(6);
                    $ig_profile->temporary_ban = \Carbon\Carbon::now()->addHours(6);
                    $ig_profile->save();
                    echo "\n[$ig_username] is going too fast & last_sent_dm shifted forward to " . \Carbon\Carbon::now()->addHours(6)->toDateTimeString() . "\n";
                    return;
                }
            }
            $ig_profile->error_msg = $ex->getMessage();
        } else if ($ex instanceof \InstagramAPI\Exception\CheckpointRequiredException) {
            $ig_profile->checkpoint_required = 1;
            $ig_profile->error_msg = $ex->getMessage();
        } else if ($ex instanceof \InstagramAPI\Exception\NetworkException) {
            
        } else if ($ex instanceof \InstagramAPI\Exception\EndpointException) {
            if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                $ig_profile->error_msg = $ex->getMessage();
            } else if ($ex->getMessage() === "InstagramAPI\Response\LoginResponse: The username you entered doesn't appear to belong to an account. Please check your username and try again.") {
                $ig_profile->invalid_user = 1;
            }
        } else if ($ex instanceof \InstagramAPI\Exception\IncorrectPasswordException) {
            $ig_profile->incorrect_pw = 1;
        } else if ($ex instanceof \InstagramAPI\Exception\AccountDisabledException) {
            $ig_profile->account_disabled = 1;
        } else if ($ex instanceof \InstagramAPI\Exception\ThrottledException) {
            $ig_profile->last_sent_dm = \Carbon\Carbon::now()->addHours(6);
            $ig_profile->temporary_ban = \Carbon\Carbon::now()->addHours(6);
            $ig_profile->save();
            echo "\n[$ig_username] got throttled & last_sent_dm shifted forward to " . \Carbon\Carbon::now()->addHours(1)->toDateTimeString() . "\n";
            return;
        }

        if ($ex->hasResponse()) {
            dump($ex->getResponse());
        } else {
            echo("\nThis exception has no response.\n");
        }

        $ig_profile->save();
    }

}
