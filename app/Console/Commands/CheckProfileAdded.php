<?php

namespace App\Console\Commands;

use App\InstagramProfile;
use App\Mail\IgProfileReminder;
use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CheckProfileAdded extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:profileadded';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $time_start = microtime(true);
        $ytd = Carbon::today()->subDay(1)->toDateTimeString();
        $users = User::whereRaw('email IN (SELECT DISTINCT(email) FROM user_insta_profile)')
            ->where('created_at','>',$ytd)
            ->get();

        foreach($users as $user){
            $ig_profile = InstagramProfile::where('email',$user->email)->first();
            if ($ig_profile === NULL){
                Mail::to($user->email)->send(new IgProfileReminder($user));
                $user->reminder_igprofile = 1;
                $user->save();
            }
        }

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        echo 'Total Execution Time: ' . $execution_time . ' Seconds' . "\n";
    }
}
