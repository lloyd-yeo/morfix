<?php

namespace App\Console\Commands;

use App\StripeDetail;
use App\UserAffiliates;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;

class UpdatePendingCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:updatepending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update pending commission of users.';

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
    	$users = User::where('tier', '>', 2)->where('last_pay_out_date', '2017-12-25 00:00:00')->get();
    	$this->alert($users->count() . " users");
    	foreach ($users as $user) {
    		$pending_comms = 0;
			$this->line($user->email);

			$user_affiliates = UserAffiliates::where('referrer', $user->user_id);

			foreach ($user_affiliates as $user_affiliate) {
				$affiliate = User::find($user_affiliate->referred);
				if ($affiliate == NULL) {
					continue;
				} else {
					$stripe_details = StripeDetail::where('email', $affiliate->email)->first();
					if ($stripe_details != NULL) {

					} else {

					}
				}
			}
	    }

//        $path = storage_path('app/may-payout-final.csv');
//        $file = fopen($path, "r");
//
//        $current_email = "";
//        $current_comms = 0;
//
//        while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
//
//            if ($data[4] == "Yes") {
//
//                if ($current_email != $data[0]) {
//
//                    if ($current_email != "") {
//                        $user = User::where('email', $current_email)->first();
//                        if ($user !== NULL) {
//                            $user->pending_commission = $current_comms;
//                            if($user->save()) {
//                                echo "Updated [$current_email] pending comms to [$current_comms]\n";
//                            }
//                        }
//                    }
//
//                    $current_email = $data[0];
//                    $current_comms = 0;
//                }
//
//                $referral_charges = DB::select('SELECT * FROM get_referral_charges_of_user WHERE referrer_email = ? AND '
//                        . 'charge_created >= "2017-06-01 00:00:00" AND charge_created <= "2017-06-31 23:59:59" AND charge_refunded = 0 '
//                        . 'ORDER BY charge_created DESC;', [$data[0]]);
//
//                foreach ($referral_charges as $referral_charge) {
//
//                    if ($referral_charge->subscription_id == "0137") {
//                        $current_comms = $current_comms + 20;
//                    } else if ($referral_charge->subscription_id == "0297") {
//                        $current_comms = $current_comms + 50;
//                    }
//
//                    echo $referral_charge->referrer_email . "\t" . $referral_charge->referred_email . "\t"  . $referral_charge->subscription_id . " [$current_comms] \n";
//                }
//            }
//        }
    }
}
