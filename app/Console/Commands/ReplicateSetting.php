<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class ReplicateSetting extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:replicate {from} {to} {to_id}';

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
        $from = $this->argument('from');
        $to = $this->argument('to');
        $to_id = $this->argument('to_id');
        
        $comments = DB::connection("mysql_old")->select("SELECT comment FROM user_insta_profile_comment WHERE insta_username = ?;", [$from]);
        foreach ($comments as $comment) {
            $comment_ = $comment->comment;
            DB::connection("mysql_old")->insert("INSERT INTO user_insta_profile_comment (insta_username, comment) VALUES (?,?);", [$to, $comment_]);
        }
        
//        $hashtags = DB::connection("mysql_old")->select("SELECT hashtag FROM insta_affiliate.user_insta_target_hashtag WHERE insta_username = ?;", [$from]);
//        foreach ($hashtags as $hashtag) {
//            $hashtag_ = $hashtag->hashtag;
//            DB::connection("mysql_old")->insert("INSERT INTO insta_affiliate.user_insta_target_hashtag (insta_username, insta_id, hashtag) VALUES (?,?,?);", [$to, $to_id, $hashtag_]);
//        }
//        
//        $usernames = DB::connection("mysql_old")->select("SELECT target_username FROM user_insta_target_username WHERE insta_username = ?;", [$from]);
//        foreach ($usernames as $username) {
//            $username_ = $username->target_username;
//            DB::connection("mysql_old")->insert("INSERT INTO insta_affiliate.user_insta_target_username (insta_username, insta_id, target_username) VALUES (?,?,?);", [$to, $to_id, $username_]);
//        }
    }

}
