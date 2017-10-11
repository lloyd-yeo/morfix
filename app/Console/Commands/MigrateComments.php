<?php

namespace App\Console\Commands;

use App\InstagramProfile;
use App\InstagramProfileComment;
use DB;
use Illuminate\Console\Command;

class MigrateComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:comment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate User Insta Profile Comments';

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
        DB::table('user_insta_profile_comment')->truncate();
        
        $ig_profiles = InstagramProfile::all();
        $ig_profiles_usernames = array();
        foreach ($ig_profiles as $ig_profile) {
            $ig_profiles_usernames[] = $ig_profile->insta_username;
        }
        
        $master_comments = DB::connection('mysql_master')
                        ->table('user_insta_profile_comment')
                        ->whereIn('insta_username', $ig_profiles_usernames)
                        ->get();
        
        foreach ($master_comments as $master_comment) {
            $master_comment_as_array = (array)$master_comment;
            InstagramProfileComment::create($master_comment_as_array);
        }
    }
}
