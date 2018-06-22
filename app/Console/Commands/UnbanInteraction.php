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

class UnbanInteraction extends Command {

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
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $update_user_trial_activation = DB::update("UPDATE user SET trial_activation = 2 WHERE user_tier > 1;");
        $invalidate_proxy = DB::update("UPDATE user_insta_profile SET proxy = NULL, invalid_proxy = 0 WHERE invalid_proxy = 1;");
        $ig_profiles = DB::select("SELECT id FROM user_insta_profile WHERE proxy = NULL;");
        foreach ($ig_profiles as $ig_profile) {
            $proxies = DB::select("SELECT proxy, assigned FROM insta_affiliate.proxy ORDER BY RAND();");
            foreach ($proxies as $proxy) {
                $rows_affected = DB::update('update user_insta_profile set proxy = ? where id = ?;', [$proxy->proxy, $ig_profile->id]);
                $rows_affected = DB::update('update proxy set assigned = assigned + 1 where proxy = ?;', [$proxy->proxy]);
            }
        }
    }
}
