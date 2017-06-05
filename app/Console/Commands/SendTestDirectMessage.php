<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use Unicodeveloper\Emoji\Emoji;
use InstagramAPI\Instagram;

class SendTestDirectMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:senddm {insta_username?} {recipient_username?} {message?} ';

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
        $config = array();
        $config["storage"] = "mysql";
        $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";
        
        $debug = false;
        $truncatedDebug = false;
        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
        
        $sender = InstagramProfile::where('insta_username', $this->argument('insta_username'))->first();
        $recipient = InstagramProfile::where('insta_username', $this->argument('recipient_username')->first());
        $text = $this->argument('message');
        
        $instagram->setUser($sender->insta_username, $sender->insta_pw);
        $response = $instagram->direct->sendText([$recipient->insta_user_id], $text);
        var_dump($response);
    }
}
