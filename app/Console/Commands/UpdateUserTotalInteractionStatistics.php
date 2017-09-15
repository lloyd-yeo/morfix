<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\InstagramProfile;
use App\User;
use App\LikeLogsArchive;
use App\InstagramProfileLikeLog;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use Carbon\Carbon;

class UpdateUserTotalInteractionStatistics extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:interactionstats {partition?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $partition = 0;
        if ($this->argument('partition') !== NULL) {
            $partition = $this->argument('partition');
        } else {
            //Global update;
            $ig_profiles = InstagramProfile::all();
            foreach ($ig_profiles as $ig_profile) {
                $this->initialUpdateOfStats($ig_profile);
            }
        }
    }

    public function initialUpdateOfTotalStats($ig_profile) {
            $total_likes = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)->count();
            $ig_profile->total_likes = $total_likes;
            $ig_profile->save();
            
            $total_likes_archived = LikeLogsArchive::where('insta_username', $ig_profile->insta_username)->count();
            $ig_profile->total_likes = $ig_profile->total_likes + $total_likes_archived;
            $ig_profile->save();
            
            $daily_likes = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
                    ->whereBetween('date_liked', Carbon::now()->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
                            Carbon::now()->setTime(23, 59, 59)->format('Y-m-d H:i:s'))
                    ->count();
            $ig_profile->daily_likes = $daily_likes;
            $ig_profile->save();
            
            $total_comments = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)->count();
            $ig_profile->total_comments = $total_comments;
            $ig_profile->save();
            
            $daily_comments = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)
                    ->whereBetween('date_commented', Carbon::now()->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
                            Carbon::now()->setTime(23, 59, 59)->format('Y-m-d H:i:s'))
                    ->count();
            $ig_profile->daily_comments = $daily_comments;
            $ig_profile->save();
            
            $total_follows = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                    ->where('follow', 1)
                    ->count();
            
            $ig_profile->total_follows = $total_follows;
            $ig_profile->save();
            
            $daily_follows = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                    ->whereBetween('date_inserted', Carbon::now()->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
                            Carbon::now()->setTime(23, 59, 59)->format('Y-m-d H:i:s'))
                    ->count();
            $ig_profile->daily_follows = $daily_follows;
            $ig_profile->save();
            
            $total_unfollows = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                    ->where('unfollowed', 1)
                    ->count();
            $ig_profile->total_unfollows = $total_unfollows;
            $ig_profile->save();
            
            $daily_unfollows = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                    ->whereBetween('date_unfollowed', Carbon::now()->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
                            Carbon::now()->setTime(23, 59, 59)->format('Y-m-d H:i:s'))
                    ->count();
            $ig_profile->daily_unfollows = $daily_unfollows;
            $ig_profile->save();
            
    }

}
