<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use InstagramAPI\Instagram;
use App\InstagramHelper;

class InstagramLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:lgin {username} {password} {challenge_url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually login to Instagram';

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
        $instagram = InstagramHelper::initInstagram(true);

        $guzzle_options = array();
	    $guzzle_options['curl'] = array();
	    $guzzle_options['curl'][CURLOPT_PROXY] = 'http://pr.oxylabs.io:8000';
	    $guzzle_options['curl'][CURLOPT_PROXYUSERPWD] = 'customer-rmorfix-cc-US-city-san_jose-sessid-iglogin:dXehM3e7bU';
	    $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;

	    try {
	    	if ($this->argument('challenge_url') == NULL) {
			    $login_response = $instagram->login($this->argument('username'),
				    $this->argument('password'), $guzzle_options
			    );
			    dump($login_response);
		    } else {
			    dump($instagram->request($this->argument('challenge_url'))->setGuzzleOptions($guzzle_options)->getRawResponse());
		    }
	    } catch (\Exception $ex) {
			dump($ex);
	    }



//	    $proxy = $this->getProxyList()[0];
	    
//	    $guzzle_options = array();
//	    $guzzle_options['curl'] = array();
//	    $guzzle_options['curl'][CURLOPT_PROXY] = $proxy;
//	    $guzzle_options['curl'][CURLOPT_PROXYUSERPWD] = 'morfix:dXehM3e7bU';
//	    $guzzle_options['curl'][CURLOPT_RETURNTRANSFER] = 1;



//	    $discoverResponse = $instagram->people->discoverPeople();
//	    dump($discoverResponse);

    }

    public function getProxyList() {
	    return [
		    "104.164.41.0:60000",
		    "104.164.41.2:60000",
		    "104.164.41.4:60000",
		    "104.164.41.6:60000",
		    "104.164.41.11:60000",
		    "104.164.41.13:60000",
		    "104.164.41.15:60000",
		    "104.164.41.17:60000",
		    "104.164.41.19:60000",
		    "104.164.41.21:60000",
		    "104.164.41.26:60000",
		    "104.164.41.28:60000",
		    "104.164.41.30:60000",
		    "104.164.41.32:60000",
		    "104.164.41.34:60000",
		    "104.164.41.36:60000",
		    "104.164.41.38:60000",
		    "104.164.41.43:60000",
		    "104.164.41.45:60000",
		    "104.164.41.47:60000",
		    "104.164.41.49:60000",
		    "104.164.41.51:60000",
		    "104.164.41.53:60000",
		    "104.164.41.58:60000",
		    "104.164.41.60:60000",
		    "104.164.41.62:60000",
		    "104.164.41.64:60000",
		    "104.164.41.66:60000",
		    "104.164.41.68:60000",
		    "104.164.41.70:60000",
		    "104.164.41.75:60000",
		    "104.164.41.77:60000",
		    "104.164.41.79:60000",
		    "104.164.41.81:60000",
		    "104.164.41.83:60000",
		    "104.164.41.85:60000",
		    "104.164.41.90:60000",
		    "104.164.41.92:60000",
		    "104.164.41.94:60000",
		    "104.164.41.96:60000",
		    "104.164.41.98:60000",
		    "104.164.41.100:60000",
		    "104.164.41.107:60000",
		    "104.164.41.109:60000",
		    "104.164.41.111:60000",
		    "104.164.41.113:60000",
		    "104.164.41.115:60000",
		    "104.164.41.117:60000",
		    "104.164.41.122:60000",
		    "104.164.41.124:60000",
		    "104.164.41.126:60000",
		    "104.164.41.128:60000",
		    "104.164.41.130:60000",
		    "104.164.41.132:60000",
		    "104.164.41.139:60000",
		    "104.164.41.141:60000",
		    "104.164.41.143:60000",
		    "104.164.41.145:60000",
		    "104.164.41.147:60000",
		    "104.164.41.149:60000",
		    "104.164.41.154:60000",
		    "104.164.41.156:60000",
		    "104.164.41.158:60000",
		    "104.164.41.160:60000",
		    "104.164.41.162:60000",
		    "104.164.41.164:60000",
		    "104.164.41.171:60000",
		    "104.164.41.173:60000",
		    "104.164.41.175:60000",
		    "104.164.41.177:60000",
		    "104.164.41.179:60000",
		    "104.164.41.181:60000",
		    "104.164.41.186:60000",
		    "104.164.41.188:60000",
		    "104.164.41.190:60000",
		    "104.164.41.192:60000",
		    "104.164.41.194:60000",
		    "104.164.41.196:60000",
		    "104.164.41.203:60000",
		    "104.164.41.205:60000",
		    "104.164.41.207:60000",
		    "104.164.41.209:60000",
		    "104.164.41.211:60000",
		    "104.164.41.213:60000",
		    "104.164.41.218:60000",
		    "104.164.41.220:60000",
		    "104.164.41.222:60000",
		    "104.164.41.224:60000",
		    "104.164.41.226:60000",
		    "104.164.41.228:60000",
		    "104.164.41.230:60000",
		    "104.164.41.235:60000",
		    "104.164.41.237:60000",
		    "104.164.41.239:60000",
		    "104.164.41.241:60000",
		    "104.164.41.243:60000",
		    "104.164.41.245:60000",
		    "104.164.41.250:60000",
		    "104.164.41.252:60000",
		    "104.164.41.254:60000"];
    }
}
