<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class UpdateNextInteractionTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:interactiontime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the next interaction time fields for profiles.';

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
        //Update the last_sent_dm field.
        DB::table('user_insta_profile')->whereNull('last_sent_dm')->update([
            'last_sent_dm' => Carbon::now()->toDateTimeString(),
        ]);
        //Update the next_follow_time IF it's NULL
        DB::table('user_insta_profile')->whereNull('next_follow_time')->update([
            'next_follow_time' => Carbon::now()->toDateTimeString(),
        ]);
        //Update the next_comment_time IF it's NULL
        DB::table('user_insta_profile')->whereNull('next_comment_time')->update([
            'next_comment_time' => Carbon::now()->toDateTimeString(),
        ]);
        //Update the next_like_time IF it's NULL
        DB::table('user_insta_profile')->whereNull('next_like_time')->update([
            'next_like_time' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
