<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\InstagramProfile;
use App\InstagramProfileCommentLog;

class RetrieveCommentForCertainDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:comment';

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
        DB::table('user_insta_profile')
                ->join('user_insta_profile_comment_log', function ($join) {
                        $join->on('user_insta_profile.insta_username', '=', 'user_insta_profile_comment_log.insta_username')
                             ->where('user_insta_profile_comment_log.date_commented', '>=', '2017-08-21 00:00:00')
                             ->where('user_insta_profile_comment_log.date_commented', '>=', '2017-09-21 00:00:00');
                            
                    })
                ->select('user_insta_profile.insta_username', 'user_insta_profile.insta_pw', 
                        'user_insta_profile_comment_log.target_username', 'user_insta_profile_comment_log.target_media')
                ->get();
    }
}
