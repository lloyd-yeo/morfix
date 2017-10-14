<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\InstagramProfile;
use App\User;
use App\InstagramProfileCommentLog;
use App\InstagramProfileLikeLog;
use App\InstagramProfileFollowLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Events\UserInteractionsFailed;
use App\UserInteractionFailed;

class CheckInteractionsWorking extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interactions:checkworking {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users who have working interactions';

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
        $time_start = microtime(true);
        $users = array();

        if ($this->argument("email") == "slave") {

            $users = User::all();

            foreach ($users as $user) {

                echo "Retrieved user [" . $user->email . "] [" . $user->tier . "]\n";
                $instagram_profiles = InstagramProfile::where('email', $user->email)
                    ->get();

                foreach ($instagram_profiles as $ig_profile) {

                    $tier = $user->tier;
                    $this->checkIgProfile($ig_profile, $tier);
                }
            }
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo '<b>Total Execution Time:</b> ' . $execution_time . ' Seconds' . "\n";
        } else if (NULL !== $this->argument("email")) {

            $users = User::where('email', $this->argument("email"))
                ->orderBy('user_id', 'desc')
                ->get();

            foreach ($users as $user) {

                echo "Retrieved user [" . $user->email . "] [" . $user->tier . "]\n";

                $instagram_profiles = InstagramProfile::where('email', $user->email)
                    ->get();

                foreach ($instagram_profiles as $ig_profile) {

                    $tier = $user->tier;
                    $this->checkIgProfile($ig_profile, $tier);
                }
            }
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo '<b>Total Execution Time:</b> ' . $execution_time . ' Seconds' . "\n";
        } else {
            $time_start = microtime(true);

            $users = User::whereRaw('email IN (SELECT DISTINCT(email) FROM user_insta_profile)')
                ->where('partition', 0)
                ->orderBy('user_id', 'desc')
                ->take(50)
                ->get();


            foreach ($users as $user) {

                echo "Retrieved user [" . $user->email . "] [" . $user->tier . "]\n";

                $instagram_profiles = InstagramProfile::where('email', $user->email)
                    ->get();
                foreach ($instagram_profiles as $ig_profile) {
                    $tier = $user->tier;
                    $this->checkIgProfile($ig_profile, $tier);
                }
            }

            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo 'Total Execution Time: ' . $execution_time . ' Seconds' . "\n";
        }
    }

    public function checkIgProfile($ig_profile, $tier)
    {

        $from = Carbon::now()->subHours(3)->toDateTimeString();
        $to = Carbon::now()->toDateTimeString();

        $user_like = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
            ->whereBetween('date_liked', array($from, $to))
            ->first();

        $user_comment = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)
            ->whereBetween('date_commented', array($from, $to))
            ->first();

        $user_follow = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
            ->whereBetween('date_inserted', array($from, $to))
            ->first();

        $user_unfollow = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
            ->whereBetween('date_unfollowed', array($from, $to))
            ->first();

        if (is_null($user_like) && $ig_profile->auto_like == 1) {
            $ig_profile->auto_like_working = 0;
            echo "[" . $ig_profile->insta_username . "] Updated like info to 0\n";
        }

        if (!is_null($user_like) || $ig_profile->auto_like == 0) {
            $ig_profile->auto_like_working = 1;
            echo "[" . $ig_profile->insta_username . "] Updated like info to 1\n";
        }

        if (is_null($user_comment) && $ig_profile->auto_comment == 1) {
            $ig_profile->auto_comment_working = 0;
            echo "[" . $ig_profile->insta_username . "] Updated comment info to 0 \n";
        }

        if (!is_null($user_comment) || $ig_profile->auto_comment == 0) {
            $ig_profile->auto_comment_working = 1;
            echo "[" . $ig_profile->insta_username . "] Updated comment info to 1 \n";
        }

        if ($ig_profile->auto_follow == 0 && $ig_profile->auto_unfollow == 0) { #User turned off auto follow & auto unfollow
            $ig_profile->auto_follow_working = 1;
            echo "[" . $ig_profile->insta_username . "] didn't turn on Auto-Follow/Unfollow \n";
        } else {
            if ($ig_profile->auto_follow == 1 && $ig_profile->auto_unfollow == 0) {
                #turn on Auto-Follow only
                if (!is_null($user_follow)) {
                    #If there are follow logs in the past 3 hours then it's working.
                    #user_follow is first() of past 3 hour follow logs.
                    $ig_profile->auto_follow_working = 1;
                    echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                } else {
                    $ig_profile->auto_follow_working = 0;
                    echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                }
            } else if ($ig_profile->auto_unfollow == 1 && $ig_profile->auto_follow == 0) {
                #turn on Auto-Unfollow only
                if (!is_null($user_unfollow)) {
                    #If there are unfollow logs in the past 3 hours then it's working.
                    #$user_unfollow is first() of past 3 hour unfollow logs.
                    $ig_profile->auto_follow_working = 1;
                    echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                } else {
                    $ig_profile->auto_follow_working = 0;
                    echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                }
            } else {
                #turn on Both
                if ((!is_null($user_follow) || !is_null($user_unfollow))) {
                    #If there are follow logs or unfollow logs in the past 3 hours then it's working.
                    $ig_profile->auto_follow_working = 1;
                    echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                } else {
                    $ig_profile->auto_follow_working = 0;
                    echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                }
            }
        }

        if ($ig_profile->auto_comment_working === 0 || $ig_profile->auto_like_working === 0 || $ig_profile->auto_follow_working === 0) {
            $ig_profile->auto_interactions_working = 0;
            if ($ig_profile->incorrect_pw === 0 && $ig_profile->checkpoint_required === 0 && $ig_profile->auto_follow_ban === 0 && $ig_profile->auto_like_ban === 0 && $ig_profile->auto_comment_ban === 0 && $tier > 1) {
                $profile = new UserInteractionFailed;
                $profile->email = $ig_profile->email;
                $profile->insta_username = $ig_profile->insta_username;
                $profile->tier = $tier;
                $profile->save();

            }
        } else if ($ig_profile->auto_comment_working === 1 && $ig_profile->auto_like_working === 1 && $ig_profile->auto_follow_working === 1) {
            $ig_profile->auto_interactions_working = 1;
            $check = UserInteractionFailed::where('insta_username', $ig_profile->insta_username)->first();
            if ($check !== NULL) {
                $check->destroy();
            }

        }

        $ig_profile->save();

        DB::connection('mysql_master')->table('user_insta_profile')
            ->where('id', $ig_profile->id)
            ->update(['auto_like_working' => $ig_profile->auto_like_working,
                'auto_comment_working' => $ig_profile->auto_comment_working,
                'auto_follow_working' => $ig_profile->auto_follow_working,
                'auto_interactions_working' => $ig_profile->auto_interactions_working]);
    }

}
