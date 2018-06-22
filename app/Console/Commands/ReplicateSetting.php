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
        $comments = DB::select("SELECT comment FROM user_insta_profile_comment WHERE insta_username = ?;", [$from]);
        foreach ($comments as $comment) {
            $comment_ = $comment->comment;
            DB::insert("INSERT INTO user_insta_profile_comment (insta_username, comment) VALUES (?,?);", [$to, $comment_]);
        }
    }

}
