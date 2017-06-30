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
use Illuminate\Support\Facades\DB;

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

        $dm_job = getDmJobsByIgUsername($insta_username, $servername, $username, $password, $dbname);
        
        $dm_job = NULL;
        $conn = getConnection($servername, $username, $password, $dbname);
        //$users = DB::table('users')
        //             ->select(DB::raw('count(*) as user_count, status'))
          //           ->where('status', '<>', 1)
            //         ->groupBy('status')
              //       ->get();
        $stmt_get_dm_job = DB::dm_job('jobs')
                            ->select(DB::raw)
        $stmt_get_dm_job = $conn->prepare("SELECT job_id, recipient_username, recipient_insta_id, recipient_fullname, message "
                . "FROM insta_affiliate.dm_job "
                . "WHERE insta_username = ? AND fulfilled = 0 AND NOW() > time_to_send ORDER BY job_id ASC LIMIT 1;");
        $stmt_get_dm_job->bind_param("s", $insta_username);
        $stmt_get_dm_job->execute();
        $stmt_get_dm_job->store_result();
        $stmt_get_dm_job->bind_result($job_id, $recipient_username, $recipient_insta_id, $recipient_fullname, $message);
        while ($stmt_get_dm_job->fetch()) {
            $dm_job = array(
                "job_id" => $job_id,
                "recipient_username" => $recipient_username,
                "recipient_insta_id" => $recipient_insta_id,
                "recipient_fullname" => $recipient_fullname,
                "message" => $message
            );
        }
        $stmt_get_dm_job->free_result();
        $stmt_get_dm_job->close();
        $conn->close();
        return $dm_job;
        
        
        if (is_null($dm_job)) {
            exit;
        }

        try {
            $config = array();
            $config["storage"] = "mysql";
            $config["dbusername"] = "root";
            $config["dbpassword"] = "inst@ffiliates123";
            $config["dbhost"] = "52.221.60.235:3306";
            $config["dbname"] = "morfix";
            $config["dbtablename"] = "instagram_sessions";
            $debug = false;
            $truncatedDebug = false;
            $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
            $ig_username = $insta_username;
            $ig_password = $insta_pw;

            if (is_null($proxy)) {
                continue;
            } else {
                $instagram->setProxy($proxy);
                $instagram->setUser($ig_username, $ig_password);
                $instagram->login();
                try {
                    $delay = rand(35, 45);
                    $recipients = array();
                    $recipients["users"] = [$dm_job["recipient_insta_id"]];
                    $direct_msg_resp = $instagram->direct->sendText($recipients, $dm_job["message"]);
                    echo "[$insta_username] " . $dm_job["job_id"] . "\n";
                    #$direct_msg_resp = $instagram->directMessage($dm_job["recipient_insta_id"], $dm_job["message"]);
                    var_dump($direct_msg_resp);

                    updateDmJobFulfilled($dm_job["job_id"], $auto_dm_delay, $ig_username, $dm_job["recipient_insta_id"], $servername, $username, $password, $dbname);

                    if (!is_null($temporary_ban)) {
                        updateUserNextSendTime($insta_username, $delay, "banned", $servername, $username, $password, $dbname);
                    } else {
                        updateUserNextSendTime($insta_username, $delay, "normal", $servername, $username, $password, $dbname);
                    }
                } catch (\InstagramAPI\Exception\RequestException $request_ex) {
                    echo "[" . $insta_username . "] " . $request_ex->getMessage() . "\n";
                    if (stripos(trim($request_ex->getMessage()), "feedback_required") !== false) {
                        updateUserFeedbackRequired($insta_username, $dm_job["job_id"], $request_ex->getMessage(), $servername, $username, $password, $dbname);
                        continue;
                    }
                    if (stripos(trim($request_ex->getMessage()), "checkpoint_required") !== false) {
                        updateUserCheckpointRequired($insta_username, $servername, $username, $password, $dbname);
                        continue;
                    }
                }
            }
        } catch (Exception $ex) {
            echo "[" . $insta_username . "] " . $ex->getMessage() . "\n";
            echo "[" . $insta_username . "] " . $ex->getTraceAsString() . "\n";
        }
    }

}
