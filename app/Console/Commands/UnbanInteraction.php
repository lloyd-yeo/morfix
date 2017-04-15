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

class UnbanInteraction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:unban';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove soft bans by Morfix on Auto Interaction';

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
        $invalidate_proxy = DB::connection("mysql_old")->update("UPDATE user_insta_profile SET proxy = NULL, invalid_proxy = 0 WHERE invalid_proxy = 1;");
        $remove_follow_ban = DB::connection("mysql_old")->update("UPDATE user_insta_profile SET auto_follow_ban = 0, auto_follow_ban_time = NULL WHERE auto_follow_ban = 1 AND NOW() >= auto_follow_ban_time;");
    }
}
