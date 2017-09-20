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
use DB;

class UpdateUserTotalInteractionStatistics extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:interactionstats {mode?} {node?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Suite of functions for updating stats on Slaves.';

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
        
        $ig_profiles = array();
        if ($this->argument('node') == "master") { //get only those residing on the Master
            $ig_profiles = InstagramProfile::where('partition', 0)->get();
        } else { //running from a Slave.
            $ig_profiles = InstagramProfile::all();
        }
        
        if ($this->argument('mode') !== NULL && $this->argument('mode') == "refresh") {
            foreach ($ig_profiles as $ig_profile) {
                $this->refreshDailyStats($ig_profile);
            }
        }
        else if ($this->argument('mode') !== NULL && $this->argument('mode') == "accumulate") {
            foreach ($ig_profiles as $ig_profile) {
                $this->accumulate($ig_profile);
            }
        }
        else {
            //Global init;
            foreach ($ig_profiles as $ig_profile) {
                $this->initialUpdateOfTotalStats($ig_profile);
            }
        }
    }
    
    private function accumulate($ig_profile) {
        
        $ig_profile->total_likes = $ig_profile->total_likes + $ig_profile->daily_likes;
        $ig_profile->daily_likes = 0;
        $ig_profile->total_comments = $ig_profile->total_comments + $ig_profile->daily_comments;
        $ig_profile->daily_comments = 0;
        $ig_profile->total_follows = $ig_profile->total_follows + $ig_profile->daily_follows;
        $ig_profile->daily_follows = 0;
        $ig_profile->total_unfollows = $ig_profile->total_unfollows + $ig_profile->daily_unfollows;
        $ig_profile->daily_unfollows = 0;
        $ig_profile->save();
        
        DB::connection('mysql_master')->table('user_insta_profile')
                ->where('id', $ig_profile->id)
                ->update(['daily_likes' => $ig_profile->daily_likes,
                          'daily_comments' => $ig_profile->daily_comments,
                          'daily_follows' => $ig_profile->daily_follows,
                          'daily_unfollows' => $ig_profile->daily_unfollows,
                          'total_likes' => $ig_profile->total_likes,
                          'total_comments' => $ig_profile->total_comments,
                          'total_follows' => $ig_profile->total_follows,
                          'total_unfollows' => $ig_profile->total_unfollows]);
    }
    
    private function refreshDailyStats($ig_profile) {
        
        $daily_likes = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
                ->whereDate('date_liked', '=', Carbon::today()->toDateString())
                ->count();
        $ig_profile->daily_likes = $daily_likes;

        $daily_comments = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)
                ->whereDate('date_commented', '=', Carbon::today()->toDateString())
                ->count();
        $ig_profile->daily_comments = $daily_comments;

        $daily_follows = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('follow', 1)
                ->whereDate('date_inserted', '=', Carbon::today()->toDateString())
                ->count();
        $ig_profile->daily_follows = $daily_follows;

        $daily_unfollows = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)->where('unfollowed', 1)
                ->whereDate('date_unfollowed', '=', Carbon::today()->toDateString())
                ->count();
        $ig_profile->daily_unfollows = $daily_unfollows;
        
        if ($ig_profile->save()) {
            $this->info("Saved for profile [" . $ig_profile->insta_username . "]");
        }
        
        DB::connection('mysql_master')->table('user_insta_profile')
                ->where('id', $ig_profile->id)
                ->update(['daily_likes' => $ig_profile->daily_likes,
                          'daily_comments' => $ig_profile->daily_comments,
                          'daily_follows' => $ig_profile->daily_follows,
                          'daily_unfollows' => $ig_profile->daily_unfollows]);
    }

    private function initialUpdateOfTotalStats($ig_profile) {
        $total_likes = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)->count();
        $ig_profile->total_likes = $total_likes;

        $total_likes_archived = LikeLogsArchive::where('insta_username', $ig_profile->insta_username)->count();
        $ig_profile->total_likes = $ig_profile->total_likes + $total_likes_archived;

        $daily_likes = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)
                ->whereBetween('date_liked', [Carbon::now()->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
                    Carbon::now()->setTime(23, 59, 59)->format('Y-m-d H:i:s')])
                ->count();
        $ig_profile->daily_likes = $daily_likes;

        $total_comments = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)->count();
        $ig_profile->total_comments = $total_comments;

        $daily_comments = InstagramProfileCommentLog::where('insta_username', $ig_profile->insta_username)
                ->whereBetween('date_commented', [Carbon::now()->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
                    Carbon::now()->setTime(23, 59, 59)->format('Y-m-d H:i:s')])
                ->count();
        $ig_profile->daily_comments = $daily_comments;

        $total_follows = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                ->where('follow', 1)
                ->count();

        $ig_profile->total_follows = $total_follows;

        $daily_follows = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                ->whereBetween('date_inserted', [Carbon::now()->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
                    Carbon::now()->setTime(23, 59, 59)->format('Y-m-d H:i:s')])
                ->count();
        $ig_profile->daily_follows = $daily_follows;

        $total_unfollows = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                ->where('unfollowed', 1)
                ->count();
        $ig_profile->total_unfollows = $total_unfollows;

        $daily_unfollows = InstagramProfileFollowLog::where('insta_username', $ig_profile->insta_username)
                ->whereBetween('date_unfollowed', [Carbon::now()->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
                    Carbon::now()->setTime(23, 59, 59)->format('Y-m-d H:i:s')])
                ->count();
        $ig_profile->daily_unfollows = $daily_unfollows;
        
        if ($ig_profile->save()) {
            $this->line("[" . $ig_profile->insta_username . "] " . "Initial Update Done!");
        }
    }

}
