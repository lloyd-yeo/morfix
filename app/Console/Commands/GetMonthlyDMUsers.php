<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\User;
use App\InstagramProfile;
use App\DmJob;

class GetMonthlyDMUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dm:monthlyusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the monthly users for DM';

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
        $dm_jobs = DmJob::select('insta_username')->distinct()->where('time_to_send', '>=', '2018-01-01')
	        ->where('time_to_send', '<=', '2018-01-31')->get();

        $emails = collect();
        foreach ($dm_jobs as $dm_job) {
        	$this->line($dm_job->insta_username);
        	$users = InstagramProfile::where('insta_username', $dm_job->insta_username)->get();
        	foreach ($users as $user) {
		        $emails->push($user->email);
	        }
        }
		$unique_emails = $emails->unique();

        foreach ($unique_emails as $email) {
			$this->line($email);
        }
        $this->line($unique_emails->count());
    }
}
