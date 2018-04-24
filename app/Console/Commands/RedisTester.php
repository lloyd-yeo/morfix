<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisTester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redistester:save';

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
        $pk = "test:profile:123456";
        $response_array = (array("name" => "abc", "full_name" => "long_name", "is_verified" => "false", "new" => "haha"));
        echo "This is follower response \n";
        $response_array = json_encode($response_array, JSON_PRETTY_PRINT);
        echo ($response_array);

        Redis::hmset(
//            $pk, [$response_array]
            $pk, $response_array

        );
    }
}
