<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;

class GetUserWithOutstandingPayable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:payable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get users with outstanding payable.';

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
       $users = User::where('pending_commission_payable', '=', 0)->get();
       foreach ($users as $user) {
           $referral_charges = DB::select('SELECT * FROM get_referral_charges_of_user WHERE charge_refunded = 0 AND charge_created >= "2017-05-01 00:00:00" AND charge_created <= "2017-05-31 23:59:59" AND referrer_email = ?', [$user->email]);
           foreach ($referral_charges as $referral_charge) {
               $plan = "Nil";
               if ($referral_charge->subscription_id == "0137") {
                   $plan = "Premium";
               }
               else if ($referral_charge->subscription_id == "0297") {
                   $plan = "Business";
               }
               else if ($referral_charge->subscription_id == "MX370") {
                   $plan = "Pro";
               }
               else if ($referral_charge->subscription_id == "MX970") {
                   $plan = "Mastermind";
               }
               else if ($referral_charge->subscription_id == "0197") {
                   $plan = "Business - Round 1";
               }
               else if ($referral_charge->subscription_id == "0167") {
                   $plan = "Business - Round 2";
               }
               echo $referral_charge->referred_email . "," . $referral_charge->referrer_email . "," .  $referral_charge->subscription_id . "," . $plan . "," . $referral_charge->invoice_id . "\n";
           }
           #echo $user->email . "\t" . $user->paypal_email . "\n";
       }
    }
}
