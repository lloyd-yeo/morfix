<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;

class UpdateUserLastPaid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'affiliate:updatelastpaid';

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
        $referral_charges_users = DB::select('SELECT DISTINCT(referrer_email) FROM insta_affiliate.get_referral_charges_of_user LIMIT 1000');
        foreach ($referral_charges_users as $referral_charges_user) {
            echo $referral_charges_user->referrer_email . "\n";
        }
        
    }
}
