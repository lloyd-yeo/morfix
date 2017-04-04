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
    protected $signature = 'ig:login {ig_username} {ig_password}';

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
        $ig_username = $this->argument("ig_username");
        $ig_password = $this->argument("ig_password");
        $this->line($ig_username . " " . $ig_password);
        $config = array();
        $config["storage"] = "mysql";
        $config["dbusername"] = "root";
        $config["dbpassword"] = "inst@ffiliates123";
        $config["dbhost"] = "52.221.60.235:3306";
        $config["dbname"] = "morfix";
        $config["dbtablename"] = "instagram_sessions";

        $debug = false;
        $truncatedDebug = false;
        $instagram = new \InstagramAPI\Instagram($debug, $truncatedDebug, $config);
        
        $proxies = DB::connection("mysql_old")->select("SELECT proxy, assigned FROM insta_affiliate.proxy WHERE assigned = 0 LIMIT 1;");
        foreach ($proxies as $proxy) {
            $instagram->setUser($ig_username, $ig_password);
            $instagram->setProxy($proxy->proxy);
            $instagram->login();
        }
    }

}
