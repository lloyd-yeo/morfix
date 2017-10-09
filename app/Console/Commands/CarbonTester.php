<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CarbonTester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:carbon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test our Carbon for formatting dates.';

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
        #$date = "2017-09-06T12:52:51Z";
        #echo \Carbon\Carbon::createFromTimestamp($date)->toDateTimeString();
        #echo \Carbon\Carbon::createFromTimestamp(1326853478);
    }
}
