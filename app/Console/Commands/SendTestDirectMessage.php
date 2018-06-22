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
use App\InstagramHelper;

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
        $config["pdo"] = DB::connection()->getPdo();
        $config["dbtablename"] = "instagram_sessions";
        
        $debug = false;
        $truncatedDebug = false;

	    $instagram = InstagramHelper::initInstagram();

	    $proxy = NULL;
	    if ($this->argument('insta_username') == 'l-ywz@hotmail.com') {
		    $proxy = 'http://7708f98575:SvEH1i87@104.203.100.176:4444';
		    Log::info('[TEST SENDDM] ' . Auth::user()->email . ' using proxy: ' . $proxy);
		    $instagram->setProxy($proxy);
	    }
        
        $sender = InstagramProfile::where('insta_username', $this->argument('insta_username'))->first();
        $recipient = InstagramProfile::where('insta_username', $this->argument('recipient_username'))->first();

        $text = $this->argument('message');

	    $explorer_response = $instagram->login($sender->insta_username, $sender->insta_pw);
	    var_dump($explorer_response);

        $recipients = array();
        $recipients["users"] = [$recipient->insta_user_id]; 
        $response = $instagram->direct->sendText($recipients, $text);
        var_dump($response);
    }
}
