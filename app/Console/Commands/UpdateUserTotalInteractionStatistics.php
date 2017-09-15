<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\InstagramProfile;
use App\User;
use App\LikeLogsArchive;
use App\InstagramProfileLikeLog;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;

class UpdateUserTotalInteractionStatistics extends Command
{
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
        $partition = 0;
        if ($this->argument('partition') !== NULL) {
            $partition = $this->argument('partition');
        } else {
            //Global update;
            $ig_profiles = InstagramProfile::all();
            foreach ($ig_profiles as $ig_profile) {
                if ($ig_profile->auto_like == 1 && $ig_profile->total_likes == 0) {
                    $total_likes = InstagramProfileLikeLog::where('insta_username', $ig_profile->insta_username)->count();
                    $ig_profile->total_likes = $total_likes;
                    $ig_profile->save();
                }
            }
        }
    }
}
