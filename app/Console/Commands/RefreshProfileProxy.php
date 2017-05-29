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

class RefreshProfileProxy extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:proxy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Proxies for Instagram Profiles';

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
        $user_insta_profiles = DB::connection('mysql_old')->select("SELECT * FROM user_insta_profile WHERE error_msg LIKE \"%cURL%\";");
        foreach ($user_insta_profiles as $ig_profile) {
            $this->line($ig_profile->insta_username . "\t" . $ig_profile->insta_pw);
            $ig_username = $ig_profile->insta_username;
            $ig_password = $ig_profile->insta_pw;
            
            $proxies = DB::connection("mysql_old")->select("SELECT proxy, assigned FROM insta_affiliate.proxy ORDER BY RAND();");
            foreach ($proxies as $proxy) {
                $rows_affected = DB::connection('mysql_old')->update('update user_insta_profile set proxy = ? where id = ?;', [$proxy->proxy, $ig_profile->id]);
                $rows_affected = DB::connection('mysql_old')->update('update proxy set assigned = 1 where proxy = ?;', [$proxy->proxy]);
            }
        }
    }

}
