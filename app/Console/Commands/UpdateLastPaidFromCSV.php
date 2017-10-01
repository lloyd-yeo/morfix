<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\GetReferralChargesOfUser;
use App\StripeCharge;
use App\PaypalCharges;

class UpdateLastPaidFromCSV extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:lastpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'updating when the user last paid from a csv file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $path = app_path('september-payout.csv');
        $file = fopen($path, "r");

        $current_email = "";
        $last_pay_out_coms_date = "2017-09-25 00:00:00";

        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            #$data is one row.
            #$data[0] is first cell so on & so forth.
            $current_email = $data[0];

            $user = User::where('email', $current_email)->first();
            if ($user !== NULL) {
                if ($data[3] > 50 && !empty($data[1]) && $data[4] == 'Eligible') {
                    $user->paid_amount = $data[3];
                    $this->CalculateUserPendingCommissions($user);
                    $user->testing_last_pay_out_date = $last_pay_out_coms_date;
                    $this->UpdateUserChargesPaid($user);
                    $user->testing_pending_commission_payable = 0;
                    $user->testing_all_time_commission = $user->all_time_commission + $data[3];
                    $user->save();
                    echo "Updated [$current_email] last pay out date to [$last_pay_out_coms_date]\n";
                    echo "Updated [$current_email] last pay out amount to [$data[3]]\n";
                    echo "Updated [$current_email] pending commission to to [$user->testing_pending_commission]\n";
                }
                else {
                    echo $user->email . "is not eligible for current payout\n";
                }
            }
        }
    }

    public function UpdateUserChargesPaid($user) {
        $recent_pay_out_date = Carbon::create(2017, 9, 25, 0, 0, 0, 'Asia/Singapore');
//        $start_date = Carbon::parse($recent_pay_out_date)->subMonth()->startOfMonth();

        if ($user->testing_last_pay_out_date == $recent_pay_out_date) {

            $end_date = Carbon::parse($recent_pay_out_date)->subMonth()->endOfMonth();
            echo "Recent payout date:" . $recent_pay_out_date . "\n";
            //    echo "Start date for charges:" . $start_date . "\n";
            echo "End date for charges:" . $end_date . "\n";

            // if we paid user recently, grab the charges from the start till the end of month before 
            // the month we paid him

            $referral_stripe_charges = GetReferralChargesOfUser::fromView()
                    ->where('referrer_email', $user->email)
                    ->where('charge_created', '<', $end_date)
                    ->where('charge_refunded', 0)
                    ->orderBy('charge_created', 'desc')
                    ->get();
            //if paid, assume all charges before payout month given
            //update last month charges to given if paid this month

            foreach ($referral_stripe_charges as $referral_stripe_charge) {

                $charges = StripeCharge::where('charge_id', $referral_stripe_charge->charge_id)
                        ->update(['testing_commission_given' => 1]);
                //update test_commission_given to commission_given after verifying code 

                echo "updated testing_commission: " . $referral_stripe_charge->referrer_email . "\n";
            }

            $referral_paypal_charges = PaypalCharges::where('referrer_email', $user->email)
                    ->where('time_stamp', '<', $end_date)
                    ->where('status', 'Completed')
                    ->where('status', '!=', 'Refunded')
                    ->orderBy('time_stamp', 'desc')
                    ->update(['testing_commission_given' => 1]);
        } else {

            echo "User not paid in recent payout date \n";
        }
    }

    public function CalculateUserPendingCommissions($user) {
        $now = Carbon::now();
        $now = $now->toDateTimeString();
        $current_comms_stripe = 0;
        $current_comms_paypal = 0;

        echo "Time now is: " . $now . "\n";

        $referral_stripe1_charges = GetReferralChargesOfUser::fromView()
                ->where('referrer_email', $user->email)
                ->where('charge_created', '<', $now)
                ->where('charge_refunded', 0)
                ->where('commission_given', 0)
                ->orderBy('charge_created', 'desc')
                ->get();

        echo "start of date of charges is since the start \n";
        echo "end of date of charges is before " . $now . "\n";

        foreach ($referral_stripe1_charges as $referral_stripe1_charge) {

            if ($referral_stripe1_charge->subscription_id == "0137" && $user->tier >= 2) {
                $current_comms_stripe = $current_comms_stripe + 20;
            }
            if ($referral_stripe1_charge->subscription_id == "0297" && $user->tier >= 12) {
                $current_comms_stripe = $current_comms_stripe + 50;
            }
            if ($referral_stripe1_charge->subscription_id == "MX370" && ($user->tier == 3 || $user->tier == 13)) {
                $current_comms_stripe = $current_comms_stripe + 200;
            }
            if ($referral_stripe1_charge->subscription_id == "MX670" && $user->tier >= 22) {
                $current_comms_stripe = $current_comms_stripe + 268;
            }
            if ($referral_stripe1_charge->subscription_id == "MX970"  && $user->tier >= 22) {
                $current_comms_stripe = $current_comms_stripe + 500;
            } else if ($referral_stripe1_charge->subscription_id == "MX297" && ($user->tier == 3 || $user->tier == 13)) {
                $current_comms_stripe = $current_comms_stripe + 118.8;
            }
        }
        echo "current_comms_stripe = " . $current_comms_stripe . "\n";

        $referral_paypal1_charges = PaypalCharges::where('referrer_email', $user->email)
                ->where('testing_commission_given', 0)
                ->where('status', "Completed")
                ->orderBy('time_stamp', 'desc')
                ->get();
        foreach ($referral_paypal1_charges as $referral_paypal1_charge) {

            if ($referral_paypal1_charge->subscription == "0137" && $user->tier >= 2) {
                if ($referral_paypal1_charge->amount == "37.0000") {
                    $current_comms_paypal = $current_comms_paypal + 20;
                } elseif ($referral_paypal1_charge->amount == "74.0000" && $user->tier >= 2) {
                    $current_comms_paypal = $current_comms_paypal + 40;
                }
            } if ($referral_paypal1_charge->subscription == "0297" && $user->tier >= 12) {
                $current_comms_paypal = $current_comms_paypal + 50;
            }
            if ($referral_paypal1_charge->subscription_id == "MX370" && ($user->tier == 3 || $user->tier == 13)) {
                $current_comms_paypal = $current_comms_paypal + 200;
            }
            if ($referral_paypal1_charge->subscription_id == "MX670"  && $user->tier >= 22) {
                $current_comms_paypal = $current_comms_paypal + 268;
            }
            if ($referral_paypal1_charge->subscription_id == "MX970"  && $user->tier >= 22) {
                $current_comms_paypal = $current_comms_paypal + 500;
            } else if ($referral_paypal1_charge->subscription_id == "MX297" && ($user->tier == 3 || $user->tier == 13)) {
                $current_comms_paypal = $current_comms_paypal + 118.8;
            }
        }

        $user->testing_pending_commission = $current_comms_stripe + $current_comms_paypal - $user->paid;
        $user->save();
        echo "current_comms_paypal = " . $current_comms_stripe . "\n";
        echo "Updated testing_pending_commission to: " . $user->testing_pending_commission . "\n";
    }

}
