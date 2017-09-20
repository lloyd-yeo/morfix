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

class CheckInteractionsWorking extends Command {

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
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $time_start = microtime(true);
        $users = array();

        if ($this->argument("email") == "slave") {

            $users = User::all();

            foreach ($users as $user) {

                echo "Retrieved user [" . $user->email . "] [" . $user->tier . "]\n";

                $instagram_profiles = InstagramProfile::where('email', $user->email)
                        ->get();
                $from = Carbon::now()->subHours(3);
                $to = Carbon::now();

                foreach ($instagram_profiles as $ig_profile) {
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

                    if ((is_null($user_follow) || is_null($user_unfollow)) && $ig_profile->auto_follow = 1) {
                        $ig_profile->auto_follow_working = 0;
                        echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                    }

                    if (!is_null($user_follow) || !is_null($user_unfollow) || $ig_profile->auto_follow == 0) {
                        $ig_profile->auto_follow_working = 1;
                        echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                    }

                    if ($ig_profile->auto_comment_working === 0 || $ig_profile->auto_like_working === 0 || $ig_profile->auto_follow_working === 0) {
                        $ig_profile->auto_interactions_working = 0;
                    } else if ($ig_profile->auto_comment_working === 1 && $ig_profile->auto_like_working === 1 && $ig_profile->auto_follow_working === 1) {
                        $ig_profile->auto_interactions_working = 1;
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
                $from = Carbon::now()->subHours(3);
                $to = Carbon::now();

                foreach ($instagram_profiles as $ig_profile) {
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

                    if ((is_null($user_follow) || is_null($user_unfollow)) && $ig_profile->auto_follow = 1) {
                        $ig_profile->auto_follow_working = 0;
                        echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                    }

                    if (!is_null($user_follow) || !is_null($user_unfollow) || $ig_profile->auto_follow == 0) {
                        $ig_profile->auto_follow_working = 1;
                        echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                    }

                    if ($ig_profile->auto_comment_working === 0 || $ig_profile->auto_like_working === 0 || $ig_profile->auto_follow_working === 0) {
                        $ig_profile->auto_interactions_working = 0;
                    } else if ($ig_profile->auto_comment_working === 1 && $ig_profile->auto_like_working === 1 && $ig_profile->auto_follow_working === 1) {
                        $ig_profile->auto_interactions_working = 1;
                    }

                    $ig_profile->save();
                }
            }
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo '<b>Total Execution Time:</b> ' . $execution_time . ' Seconds' . "\n";
        } else {
            $time_start = microtime(true);

            $users = User::whereRaw('email IN (SELECT DISTINCT(email) FROM user_insta_profile) AND partition = 0')
                    ->orderBy('user_id', 'desc')
                    ->get();

            foreach ($users as $user) {

                echo "Retrieved user [" . $user->email . "] [" . $user->tier . "]\n";

                $instagram_profiles = InstagramProfile::where('email', $user->email)
                        ->get();

                $from = Carbon::now()->subHours(3);
                $to = Carbon::now();

                foreach ($instagram_profiles as $ig_profile) {
                    $user_like = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
                            ->whereBetween('date_liked', array($from, $to))
                            ->first();
                    echo "Retrieved user-profile [" . $ig_profile->insta_username . "]\n";
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

                    if ((is_null($user_follow) || is_null($user_unfollow)) && $ig_profile->auto_follow = 1) {
                        $ig_profile->auto_follow_working = 0;

                        echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                    }
                    if (!is_null($user_follow) || !is_null($user_unfollow) || $ig_profile->auto_follow == 0) {
                        $ig_profile->auto_follow_working = 1;

                        echo "[" . $ig_profile->insta_username . "] Updated follow info \n";
                    }
                    if ($ig_profile->auto_comment_working === 0 || $ig_profile->auto_like_working === 0 || $ig_profile->auto_follow_working === 0) {
                        $ig_profile->auto_interactions_working = 0;
                        echo "[" . $ig_profile->insta_username . "] INTERACTIONS NOT WORKING! \n";
                    } elseif ($ig_profile->auto_comment_working === 1 && $ig_profile->auto_like_working === 1 && $ig_profile->auto_follow_working === 1) {
                        $ig_profile->auto_interactions_working = 1;
                        echo "[" . $ig_profile->insta_username . "] INTERACTIONS WORKING! \n";
                    }
                    
                    $ig_profile->save();
                }
            }
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo 'Total Execution Time: ' . $execution_time . ' Seconds' . "\n";
        }
    }

}
