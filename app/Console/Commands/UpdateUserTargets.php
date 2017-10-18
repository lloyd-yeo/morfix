<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class UpdateUserTargets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:usertargets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update & rectify any wrong user targets.';

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
        DB::update("UPDATE user_insta_target_username SET target_username = REPLACE (target_username, \"@\", \"\");");
        DB::update("UPDATE user_insta_target_username SET target_username = REPLACE (target_username, \"#\", \"\");");
        DB::update("UPDATE user_insta_target_hashtag SET hashtag = REPLACE (hashtag, \"@\", \"\");");
        DB::update("UPDATE user_insta_target_hashtag SET hashtag = REPLACE (hashtag, \"#\", \"\");");
        DB::update("UPDATE user_insta_target_username SET target_username = REPLACE(`target_username`, ' ', '');");
        DB::update("UPDATE user_insta_target_hashtag SET hashtag = REPLACE(`hashtag`, ' ', '');");
        DB::update("UPDATE user_insta_target_username SET target_username = REPLACE(`target_username`, '\n', '');");
        DB::update("UPDATE user_insta_target_hashtag SET hashtag = REPLACE(`hashtag`, '\n', '');");
        DB::update("UPDATE user_insta_target_username SET target_username = REPLACE(`target_username`, '\t', '');");
        DB::update("UPDATE user_insta_target_hashtag SET hashtag = REPLACE(`hashtag`, '\t', '');");
    }
}
