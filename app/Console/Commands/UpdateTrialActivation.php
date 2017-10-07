<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateTrialActivation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:trialactivation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update trial activation and set the modes correctly.';

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
        DB::table('user')->where('tier', '>', 1)->update([
            'trial_activation' => 2,
        ]);
        
        DB::table('user')->where('trial_activation', 0)->update([
            'trial_activation' => 1,
            'trial_end_date' => \Carbon\Carbon::now()->addWeek()->toDateTimeString()
        ]);
    }
}
