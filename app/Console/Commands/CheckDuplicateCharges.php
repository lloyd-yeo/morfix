<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use App\GetReferralChargesOfUser;

class CheckDuplicateCharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'charges:getduplicate';

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
        $referral_charges = DB::table('get_referral_charges_of_user')->where('charge_refunded', 0)
                ->where('charge_created', '>', '2017-06-01 00:00:00')->get();
        $initial_dir = array();
        $duplicate_dir = array();
        //month same
        //plan same
        //email same
        foreach ($referral_charges as $referral_charge) {
            $month = \Carbon\Carbon::parse($referral_charge->charge_created)->month;
            $plan = $referral_charge->subscription_id;
            $email = $referral_charge->referred_email;
            $unique_key = $month . $plan . "-" . $email;
            if (array_key_exists($unique_key, $initial_dir)) {
                $duplicate_dir[$unique_key] = 1;
            } else {
                $initial_dir[$unique_key] = 1;
            }
        }
        
        foreach ($duplicate_dir as $duplicate_key => $duplicate) {
            echo $duplicate_key . "\n";
        }
    }
}
