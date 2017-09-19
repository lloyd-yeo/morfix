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
    protected $signature = 'interactions:checkworking {email?}   ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update users who have working interactions';

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
        $users = array();
        if (NULL !== $this->argument("email")) {
            $users = User::where('email', $this->argument("email"))
                    ->orderBy('user_id', 'desc')
                    ->get();

            foreach ($users as $user) {
                $instagram_profiles = InstagramProfile::where('email', $user->email)
                        ->get();
                foreach ($instagram_profiles as $ig_profile) {
                    $user_like = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
                            ->where('date_liked', '<=', Carbon::now())
                            ->where('date_liked', '>=', Carbon::now()->subHours(3))
                            ->first();

                    $user_comment = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)
                            ->where('date_commented', '<=', Carbon::now())
                            ->where('date_commented', '>=', Carbon::now()->subHours(3))
                            ->first();

                    $user_follow = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                            ->where('date_inserted', '<=', Carbon::now())
                            ->where('date_inserted', '>=', Carbon::now()->subHours(3))
                            ->first();
                    $user_unfollow = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                            ->where('date_unfollowed', '<=', Carbon::now())
                            ->where('date_unfollowed', '>=', Carbon::now()->subHours(3))
                            ->first();


                    if ($user_like != null) {
                        $user->auto_interaction_like = 1;
                        $user->save;
                    }
                    if ($user_comment != null) {
                        $user->auto_interaction_comment = 1;
                        $user->save;
                    }
                    if ($user_follow != null || $user_follow != null) {
                        $user->auto_interaction_follow = 1;
                        $user->save;
                    }
                    if ($user->auto_interaction_comment === 1 || $user->auto_interaction_like === 1 || $user->auto_interaction_follow === 1) {
                        $user->auto_interaction_working = 1;
                        $user->save;
                    } elseif ($user->auto_interaction_comment === 0 && $user->auto_interaction_like === 0 && $user->auto_interaction_follow === 1) {
                        $user->auto_interaction_working = 0;
                        $user->save;
                    }
                }
            }
        }
    }

}
