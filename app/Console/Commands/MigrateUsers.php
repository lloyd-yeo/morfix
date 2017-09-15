<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use App\InstagramProfile;
use App\IGProfileCookie;

class MigrateUsers extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:user {partition}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate User, InstagramProfile to the target table.';

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
        $master_users = DB::connection('mysql_master')
                ->table('user')
                ->where('partition', $this->argument('partition'))
                ->get();

        foreach ($master_users as $master_user) {
            
            $this->line($master_user);
            $user = new User();
            $user->user_id = $master_user->user_id;
            $user->email = $master_user->email;
            $user->password = $master_user->password;
            $user->num_acct = $master_user->num_acct;
            $user->last_login = $master_user->last_login;
            $user->active = $master_user->active;
            $user->verification_token = $master_user->verification_token;
            $user->timezone = $master_user->timezone;
            $user->stripe_id = $master_user->stripe_id;
            $user->user_tier = $master_user->user_tier;
            $user->premium_pro = $master_user->premium_pro;
            $user->biz_pro = $master_user->biz_pro;
            $user->name = $master_user->name;
            $user->trial_activation = $master_user->trial_activation;
            $user->trial_end_date = $master_user->trial_end_date;
            $user->close_dm_tut = $master_user->close_dm_tut;
            $user->close_dashboard_tut = $master_user->close_dashboard_tut;
            $user->close_interaction_tut = $master_user->close_interaction_tut;
            $user->close_profile_tut = $master_user->close_profile_tut;
            $user->close_scheduling_tut = $master_user->close_scheduling_tut;
            $user->ref_keyword = $master_user->ref_keyword;
            $user->paypal_email = $master_user->paypal_email;
            $user->all_time_commission = $master_user->all_time_commission;
            $user->pending_commission = $master_user->pending_commission;
            $user->tier = $master_user->tier;
            $user->admin = $master_user->admin;
            $user->vip = $master_user->vip;
            $user->created_at = $master_user->created_at;
            $user->updated_at = $master_user->updated_at;
            $user->engagement_quota = $master_user->engagement_quota;
            $user->remember_token = $master_user->remember_token;
            $user->pending_commission_payable = $master_user->pending_commission_payable;
            $user->paypal = $master_user->paypal;
            $user->last_pay_out_date = $master_user->last_pay_out_date;
            $user->partition = $master_user->partition;
            
            if ($user->save()) {
//                echo($user);
                
                $master_instagram_profiles = DB::connection('mysql_master')
                ->table('user_insta_profile')
                ->where('email', $user->email)
                ->get();
                
                foreach ($master_instagram_profiles as $master_instagram_profile) {
                    $ig_profile = new InstagramProfile;
                    $ig_profile->id = $master_instagram_profile->id;
                    $ig_profile->user_id = $master_instagram_profile->user_id;
                    $ig_profile->email = $master_instagram_profile->email;
                    $ig_profile->insta_user_id = $master_instagram_profile->insta_user_id;
                    $ig_profile->insta_username = $master_instagram_profile->insta_username;
                    $ig_profile->insta_pw = $master_instagram_profile->insta_pw;
                    $ig_profile->profile_pic_url = $master_instagram_profile->profile_pic_url;
                    $ig_profile->follower_count = $master_instagram_profile->follower_count;
                    $ig_profile->profile_full_name = $master_instagram_profile->profile_full_name;
                    $ig_profile->insta_new_follower_template = $master_instagram_profile->insta_new_follower_template;
                    $ig_profile->follow_up_message = $master_instagram_profile->follow_up_message;
                    $ig_profile->num_posts = $master_instagram_profile->num_posts;
                    $ig_profile->recent_activity_timestamp = $master_instagram_profile->recent_activity_timestamp;
                    $ig_profile->auto_dm_new_follower = $master_instagram_profile->auto_dm_new_follower;
                    $ig_profile->auto_dm_delay = $master_instagram_profile->auto_dm_delay;
                    $ig_profile->last_sent_dm = $master_instagram_profile->last_sent_dm;
                    $ig_profile->temporary_ban = $master_instagram_profile->temporary_ban;
                    $ig_profile->dm_probation = $master_instagram_profile->dm_probation;
                    $ig_profile->niche = $master_instagram_profile->niche;
                    $ig_profile->speed = $master_instagram_profile->speed;
                    $ig_profile->next_follow_time = $master_instagram_profile->next_follow_time;
                    $ig_profile->unfollow = $master_instagram_profile->unfollow;
                    $ig_profile->login_log = $master_instagram_profile->login_log;
                    $ig_profile->last_instagram_login = $master_instagram_profile->last_instagram_login;
                    $ig_profile->follow_cycle = $master_instagram_profile->follow_cycle;
                    $ig_profile->follow_quota = $master_instagram_profile->follow_quota;
                    $ig_profile->unfollow_quota = $master_instagram_profile->unfollow_quota;
                    $ig_profile->like_quota = $master_instagram_profile->like_quota;
                    $ig_profile->comment_quota = $master_instagram_profile->comment_quota;
                    $ig_profile->auto_interaction = $master_instagram_profile->auto_interaction;
                    $ig_profile->auto_comment = $master_instagram_profile->auto_comment;
                    $ig_profile->auto_like = $master_instagram_profile->auto_like;
                    $ig_profile->auto_follow = $master_instagram_profile->auto_follow;
                    $ig_profile->auto_follow_ban = $master_instagram_profile->auto_follow_ban;
                    $ig_profile->auto_follow_ban_time = $master_instagram_profile->auto_follow_ban_time;
                    $ig_profile->auto_unfollow = $master_instagram_profile->auto_unfollow;
                    $ig_profile->auto_unfollow_ban = $master_instagram_profile->auto_unfollow_ban;
                    $ig_profile->auto_unfollow_ban_time = $master_instagram_profile->auto_unfollow_ban_time;
                    $ig_profile->follow_max_followers = $master_instagram_profile->follow_max_followers;
                    $ig_profile->next_like_time = $master_instagram_profile->next_like_time;
                    $ig_profile->auto_like_ban = $master_instagram_profile->auto_like_ban;
                    $ig_profile->auto_like_ban_time = $master_instagram_profile->auto_like_ban_time;
                    $ig_profile->auto_comment_ban = $master_instagram_profile->auto_comment_ban;
                    $ig_profile->auto_comment_ban_time = $master_instagram_profile->auto_comment_ban_time;
                    $ig_profile->next_comment_time = $master_instagram_profile->next_comment_time;
                    $ig_profile->unfollow_unfollowed = $master_instagram_profile->unfollow_unfollowed;
                    $ig_profile->follow_min_followers = $master_instagram_profile->follow_min_followers;
                    $ig_profile->follow_unfollow_delay = $master_instagram_profile->follow_unfollow_delay;
                    $ig_profile->follow_recent_engaged = $master_instagram_profile->follow_recent_engaged;
                    $ig_profile->checkpoint_required = $master_instagram_profile->checkpoint_required;
                    $ig_profile->account_disabled = $master_instagram_profile->account_disabled;
                    $ig_profile->invalid_user = $master_instagram_profile->invalid_user;
                    $ig_profile->incorrect_pw = $master_instagram_profile->incorrect_pw;
                    $ig_profile->invalid_proxy = $master_instagram_profile->invalid_proxy;
                    $ig_profile->feedback_required = $master_instagram_profile->feedback_required;
                    $ig_profile->comment_feedback_required = $master_instagram_profile->comment_feedback_required;
                    $ig_profile->error_msg = $master_instagram_profile->error_msg;
                    $ig_profile->proxy = $master_instagram_profile->proxy;
                    $ig_profile->updated_at = $master_instagram_profile->updated_at;
                    $ig_profile->created_at = $master_instagram_profile->created_at;
                    if ($ig_profile->save()) {
                        
                        $master_instagram_profile_cookies = DB::connection('mysql_master_igsession')
                        ->table('instagram_sessions')
                        ->get();
                        
                        foreach ($master_instagram_profile_cookies as $master_instagram_profile_cookie) {
                            $ig_profile_cookie = new IGProfileCookie;
                            $ig_profile_cookie->id = $master_instagram_profile_cookie->id;
                            $ig_profile_cookie->username = $master_instagram_profile_cookie->username;
                            $ig_profile_cookie->settings = $master_instagram_profile_cookie->settings;
                            $ig_profile_cookie->cookies = $master_instagram_profile_cookie->cookies;
                            $ig_profile_cookie->last_modified = $master_instagram_profile_cookie->last_modified;
                            if ($ig_profile_cookie->save()){
                                dump($ig_profile_cookie);
                            }
                        }
                        
//                        $ig_profile_cookies = IGProfileCookie::all();
//                        foreach ($ig_profile_cookies as $ig_profile_cookie) {
//                            echo ($ig_profile_cookie);
//                        }
                    }
                }
            }
        }
        
        
    }

}
