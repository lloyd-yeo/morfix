<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class EngagementGroup extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'engagement:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start liking using the engagemnt group.';

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
        $outstanding_engagements = DB::connection('mysql_old')
                ->select("SELECT media_id FROM insta_affiliate.engagement_group_job WHERE engaged = 0 ORDER BY date_logged DESC LIMIT 1;");
        foreach ($outstanding_engagements as $outstanding_engagement) {
            $media_id = $outstanding_engagement->media_id;
            $engagement_group_users = DB::connection('mysql_old')
                    ->select("SELECT p.insta_username, p.insta_pw, p.proxy, p.auto_like, p.auto_comment
                                FROM user_insta_profile p, user u
                                WHERE p.user_id = u.user_id
                                AND p.checkpoint_required = 0
                                AND p.invalid_user = 0
                                AND p.incorrect_pw = 0
                                AND p.feedback_required = 0
                                AND p.invalid_proxy = 0
                                AND p.account_disabled = 0
                                AND (
                                u.user_tier = 1 
                                OR (u.user_tier > 1 AND p.auto_interaction = 1 AND (p.auto_like = 1 OR p.auto_comment = 1))
                                )
                                LIMIT 10000;");

            foreach ($engagement_group_users as $ig_profile) {
                $ig_username = $ig_profile->insta_username;
                $ig_password = $ig_profile->insta_pw;

                $config = array();
                $config["storage"] = "mysql";
                $config["dbusername"] = "root";
                $config["dbpassword"] = "inst@ffiliates123";
                $config["dbhost"] = "52.221.60.235:3306";
                $config["dbname"] = "morfix";
                $config["dbtablename"] = "instagram_sessions";

                $debug = true;
                $truncatedDebug = false;
                $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
                if ($ig_profile->proxy === NULL) {
                    $proxies = DB::connection("mysql_old")->select("SELECT proxy, assigned FROM insta_affiliate.proxy WHERE assigned = 0 LIMIT 1;");
                    foreach ($proxies as $proxy) {
                        $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set proxy = ? where id = ?;', [$proxy->proxy, $ig_profile->id]);
                        $instagram->setProxy($proxy->proxy);
                        $rows_affected = DB::connection('mysql_old')->update('update proxy set assigned = 1 where proxy = ?;', [$proxy->proxy]);
                    }
                } else {
                    $instagram->setProxy($ig_profile->proxy);
                }

                try {
                    $instagram->setUser($ig_username, $ig_password);
                    $explorer_response = $instagram->login();
                    $like_response = $instagram->like($media_id);
                } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
                    $this->error("checkpt\t" . $checkpoint_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set checkpoint_required = 1 where id = ?;', [$ig_profile->id]);
                    continue;
                } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
                    $this->error("network\t" . $network_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$network_ex->getMessage(), $ig_profile->id]);
                    continue;
                } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
                    $this->error("endpt\t" . $endpoint_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set error_msg = ? where id = ?;', [$endpoint_ex->getMessage(), $ig_profile->id]);
                    continue;
                } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
                    $this->error("incorrectpw\t" . $incorrectpw_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set incorrect_pw = 1, error_msg = ? where id = ?;', [$incorrectpw_ex->getMessage(), $ig_profile->id]);
                    continue;
                } catch (\InstagramAPI\Exception\FeedbackRequiredException $feedback_ex) {
                    $this->error("feedback\t" . $feedback_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set invalid_proxy = 1, error_msg = ? where id = ?;', [$feedback_ex->getMessage(), $ig_profile->id]);
                    continue;
                } catch (\InstagramAPI\Exception\EmptyResponseException $emptyresponse_ex) {
                    continue;
                } catch (\InstagramAPI\Exception\AccountDisabledException $acctdisabled_ex) {
                    $this->error("acctdisabled\t" . $acctdisabled_ex->getMessage());
                    DB::connection('mysql_old')->update('update user_insta_profile set invalid_user = 1, error_msg = ? where id = ?;', [$acctdisabled_ex->getMessage(), $ig_profile->id]);
                    continue;
                }
            }
        }
    }

}
