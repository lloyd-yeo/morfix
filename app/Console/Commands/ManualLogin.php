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

class ManualLogin extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:login {ig_username} {ig_password} {two_fa?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Login to Instagram.';

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
        DB::reconnect();
        $ig_username = $this->argument("ig_username");
        $ig_password = $this->argument("ig_password");
        
        $this->line($ig_username . " " . $ig_password);
        
        $config = array();
        $config["storage"] = "mysql";
        $config["pdo"] = DB::connection('mysql_igsession')->getPdo();
        $config["dbtablename"] = "instagram_sessions";

        $debug = true;
        $truncatedDebug = false;
        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
        
        $proxies = DB::connection("mysql_old")->select("SELECT proxy, assigned FROM insta_affiliate.proxy ORDER BY RAND();");
        foreach ($proxies as $proxy) {
            $this->line($proxy->proxy);
            $instagram->setUser($ig_username, $ig_password);
            $instagram->setProxy($proxy->proxy);
            $explorer_response = $instagram->login();
            $this->line(serialize($explorer_response));
            
            $ig_profile = InstagramProfile::where('insta_username', $ig_username)->first();
            if ($ig_profile !== NULL) {
                $profile_pics = $instagram->getCurrentUser()->user->hd_profile_pic_versions;
                foreach ($profile_pics as $profile_pic) {
                    var_dump($profile_pic);
                }
            }
            break;
        }
    }

}
