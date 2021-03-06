<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use Illuminate\Support\Facades\Redis;
use \Redis;

class RedisTester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redistester:save {host}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save sample array to redis db';

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
    	try {
    		$host = $this->argument('host');
		    $redis = new Redis();
		    $success = $redis->connect($host, 6379, 2.5, NULL, 0);
		    $this->line($success);
		    $redis->getHost();
		    dump($redis->info());
		    $redis->close();
	    } catch (\RedisException $redisException) {
		    dump($redisException);
	    }
    }
}
