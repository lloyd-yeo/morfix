<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class DeleteFailedJobsOnRedis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:flushhorizon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all failed jobs on Horizon';

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
        $failed_jobs = Redis::get('horizon:failed_jobs');
        dump($failed_jobs);
    }
}
