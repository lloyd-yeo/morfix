<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use App\InstagramProfile;
use App\DmJob;

class MigrateDmJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:dm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate DM Job';

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
        foreach (InstagramProfile::all() as $instagram_profile) {
            
            $this->line("Migrating logs for [" . $instagram_profile->insta_username . "]");
            
            if (DmJob::where('insta_username', $instagram_profile->insta_username)->first() !== NULL) {
                $this->info("Migrated logs before for [" . $instagram_profile->insta_username . "]");
                continue;
            }
            
            $master_dm_jobs = DB::connection('mysql_master')
                    ->table('dm_job')
                    ->where('insta_username', $instagram_profile->insta_username)
                    ->get();
            
            foreach ($master_dm_jobs as  $master_dm_job) {
                $dm_job = new DmJob;
                $dm_job->insta_username = $master_dm_job->insta_username;
                $dm_job->recipient_username = $master_dm_job->recipient_username;
                $dm_job->recipient_insta_id = $master_dm_job->recipient_insta_id;
                $dm_job->recipient_fullname = $master_dm_job->recipient_fullname;
                $dm_job->time_to_send = $master_dm_job->time_to_send;
                $dm_job->fulfilled = $master_dm_job->fulfilled;
                $dm_job->message = $master_dm_job->message;
                $dm_job->date_job_inserted = $master_dm_job->date_job_inserted;
                $dm_job->follow_up_order = $master_dm_job->follow_up_order;
                $dm_job->error_msg = $master_dm_job->error_msg;
                $dm_job->success_msg = $master_dm_job->success_msg;
                $dm_job->updated_at = $master_dm_job->updated_at;
                try {
                    $dm_job->save();
                } catch (Illuminate\Database\QueryException $query_ex) {
                    continue;
                }
            }
            
            $this->line("Finished migrating logs for [" . $instagram_profile->insta_username . "]");
            
        }
    }
}
