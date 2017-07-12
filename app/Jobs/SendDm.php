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

        $dm_job = \App\DmJob::where('insta_username', $insta_username)
                ->where('fulfilled', 0)
                ->whereRaw('NOW() > time_to_send')
                ->orderBy('job_id', 'ASC')
                ->first();

        if (is_null($dm_job)) {
            echo "[$insta_username] failed to retrieved job...\n";
            exit;
        } else {
            echo "[$insta_username] retrieved job...\n";
        }

        try {
            $config = array();
            $config["storage"] = "mysql";
            $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
            $config["dbtablename"] = "instagram_sessions";
            $debug = false;
            $truncatedDebug = false;
            $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
            $ig_username = $insta_username;
            $ig_password = $insta_pw;


            if ($ig_profile->proxy === NULL) {
                $proxy = Proxy::inRandomOrder()->first();
                $ig_profile->proxy = $proxy->proxy;
                $ig_profile->save();
                $proxy->assigned = $proxy->assigned + 1;
                $proxy->save();
            }

            $instagram->setProxy($ig_profile->proxy);
            $instagram->setUser($ig_username, $ig_password);
            $instagram->login();

            try {
                $delay = rand(35, 45);
                $recipients = array();
                $recipients["users"] = array();
                echo $dm_job->recipent_insta_id . "\n";
                $recipients["users"][] = [$dm_job->recipent_insta_id];
                $direct_msg_resp = $instagram->direct->sendText($recipients, $dm_job->message);
                echo "[$insta_username] " . $dm_job->job_id . "\n";
                var_dump($direct_msg_resp);

                echo "[$insta_username] auto-delay: " . $auto_dm_delay . "\n";
                $dm_job->fulfilled = 1;
                $dm_job->updated_at = \Carbon\Carbon::now();
                $dm_job->save();

                if ($ig_profile->auto_dm_delay == 1) {
                    \App\DmJob::where('insta_username', $insta_username)
                            ->where('fulfilled', 0)
                            ->where('recipent_insta_id', $dm_job->reciepent_insta_id)
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
            } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";
                if (stripos(trim($request_ex->getMessage()), "feedback_required") !== false) {
                    $ig_profile->last_sent_dm = \Carbon\Carbon::now()->addHours(6);
                    $ig_profile->temporary_ban = \Carbon\Carbon::now()->addHours(6);
                    $ig_profile->save();
                    $dm_job->error_msg = $request_ex->getMessage();
                    $dm_job->save();
                }
                if (stripos(trim($request_ex->getMessage()), "checkpoint_required") !== false) {
                    $ig_profile->checkpoint_required = 1;
                    $ig_profile->save();
                }
            }
        } catch (Exception $ex) {
            echo "[" . $insta_username . "] " . $ex->getMessage() . "\n";
            echo "[" . $insta_username . "] " . $ex->getTraceAsString() . "\n";
        }
    }

}
