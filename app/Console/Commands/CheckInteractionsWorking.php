<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Auth;
use App\InstagramProfile;
use App\User;
use App\InstagramProfileCommentLog;
use App\InstagramProfileLikeLog;
use App\InstagramProfileFollowLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Events\UsersInteractionsFailed;
use App\Events\SlaveUsersInteractionsFailed;
use App\UserInteractionFailed;
use App\Helper;

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
    protected $failed_profiles;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->failed_profiles = collect();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        if (NULL !== $this->argument("email")) {

            $time_start = microtime(true);

            $users = User::where('email', $this->argument("email"))
                ->orderBy('partition', 'desc')
                ->get();

            $this->updateUserProfileWorking($users);

            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo '<b>Total Execution Time:</b> ' . $execution_time . ' Seconds' . "\n";
        } else {
            $time_start = microtime(true);

            $users = User::whereRaw('email IN (SELECT DISTINCT(email) FROM user_insta_profile)')
                ->where('tier', '>', 1)
                ->orderBy('partition', 'asc')
                ->get();

            $this->updateUserProfileWorking($users);
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo 'Total Execution Time: ' . $execution_time . ' Seconds' . "\n";
        }
    }

    public function checkIgProfile($ig_profile, $tier, $partition)
    {
        $updated = false;
        $from = Carbon::now()->subHours(3)->toDateTimeString();
        $to = Carbon::now()->toDateTimeString();
        $user_like = NULL;
        $user_comment = NULL;
        $user_follow = NULL;
        $user_unfollow = NULL;

        if ($partition > 0) {
        	echo "[" . $ig_profile->insta_username . "] retrieving slave connection PDO\n";

            $connection_name = Helper::getConnection($partition);

            $user_like = DB::connection($connection_name)->table('user_insta_profile_like_log')
                ->where('insta_username', $ig_profile->insta_username)
                ->whereBetween('date_liked', array($from, $to))
                ->first();

            $user_comment = DB::connection($connection_name)->table('user_insta_profile_comment_log')
                ->where('insta_username', $ig_profile->insta_username)
                ->whereBetween('date_commented', array($from, $to))
                ->first();

            $user_follow = DB::connection($connection_name)->table('user_insta_profile_follow_log')
                ->where('insta_username', $ig_profile->insta_username)
                ->whereBetween('date_inserted', array($from, $to))
                ->first();

            $user_unfollow = DB::connection($connection_name)->table('user_insta_profile_follow_log')
                ->where('insta_username', $ig_profile->insta_username)
                ->whereBetween('date_unfollowed', array($from, $to))
                ->first();

        } elseif ($partition === 0) {
	        echo "[" . $ig_profile->insta_username . "] on Master\n";

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
        }

	    echo "[" . $ig_profile->insta_username . "] examining Auto-Like\n";
        if (is_null($user_like) && $ig_profile->auto_like == 1) {
            $ig_profile->auto_like_working = 0;
            echo "[" . $ig_profile->insta_username . "] Updated like info to 0\n";
        }

        if (!is_null($user_like) || $ig_profile->auto_like == 0) {
            $ig_profile->auto_like_working = 1;
            echo "[" . $ig_profile->insta_username . "] Updated like info to 1\n";
        }

	    echo "[" . $ig_profile->insta_username . "] examining Auto-Comment\n";
        if (is_null($user_comment) && $ig_profile->auto_comment == 1) {
            $ig_profile->auto_comment_working = 0;
            echo "[" . $ig_profile->insta_username . "] Updated comment info to 0 \n";
        }

        if (!is_null($user_comment) || $ig_profile->auto_comment == 0) {
            $ig_profile->auto_comment_working = 1;
            echo "[" . $ig_profile->insta_username . "] Updated comment info to 1 \n";
        }
	    echo "[" . $ig_profile->insta_username . "] examining Auto-Follow/Unfollow\n";
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
            $ig_profile->save();
            if ($ig_profile->incorrect_pw === 0 && $ig_profile->checkpoint_required === 0 && $ig_profile->account_disabled === 0 && $ig_profile->invalid_user === 0) {
                if ($ig_profile->auto_follow_ban === 0 && $ig_profile->auto_like_ban === 0 && $ig_profile->auto_comment_ban === 0) {
                    $check_exist = UserInteractionFailed::where('email', $ig_profile->email)->first();
                    if ($check_exist === NULL) {
                        $profile = new UserInteractionFailed;
                        $profile->email = $ig_profile->email;
                        $profile->insta_username = $ig_profile->insta_username;
                        $profile->tier = $tier;
                        $profile->partition = 0;
                        $profile->timestamp = Carbon::now()->toDateTimeString();
                        $profile->save();
                        return $profile;
                    } else {
                        return NULL;
                    }

                }
            } else {
                return NULL;
            }

        } else if ($ig_profile->auto_comment_working === 1 && $ig_profile->auto_like_working === 1 && $ig_profile->auto_follow_working === 1) {
            $ig_profile->auto_interactions_working = 1;
            $ig_profile->save();
            $check = UserInteractionFailed::where('insta_username', $ig_profile->insta_username)->first();
            if ($check !== NULL) {
                $check->delete();
            }
            return NULL;
        }


    }

    /**
     * @param $users
     */
    public function updateUserProfileWorking($users)
    {
        $current_partition = 0;
        foreach ($users as $user) {

            echo "Retrieved user [" . $user->email . "] [" . $user->tier . "]\n";

            $partition = $user->partition;

            if ($partition !== $current_partition) {
                $current_partition = $partition;
                if ($this->failed_profiles->isNotEmpty()) {
                    //notify how many updated

                    event(new UsersInteractionsFailed($this->failed_profiles));
                    echo '$count: ' . $this->failed_profiles->count() . ' and UserInteractionsFailed event called' . "\n";
                    $this->failed_profiles = collect();
                }
            }
			echo "[" . $user->email . "] retrieving profiles...\n";
            $instagram_profiles = InstagramProfile::where('email', $user->email)
                ->get();
	        echo "[" . $user->email . "] retrieved " . $instagram_profiles->count() . " profile\n";

            foreach ($instagram_profiles as $ig_profile) {
                $tier = $user->tier;
                $failed_profile = $this->checkIgProfile($ig_profile, $tier, $partition);
                if ($failed_profile) {
                    $this->failed_profiles->push($failed_profile);
                }
            }

        }
        if ($this->failed_profiles->isNotEmpty()) {
            //notify how many updated

            event(new UsersInteractionsFailed($this->failed_profiles));
            echo '$count: ' . $this->failed_profiles->count() . ' and UserInteractionsFailed event called' . "\n";
            $this->failed_profiles = collect();
        }
    }


}


