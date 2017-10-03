<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\InstagramProfile;
use App\InstagramHelper;

class ReassignProxy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proxy:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh all invalid proxies.';

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
        $profiles = InstagramProfile::where('invalid_proxy', 1)->get();
        foreach ($profiles as $profile) {
            InstagramHelper::verifyAndReassignProxy($profile);
        }
    }
}
