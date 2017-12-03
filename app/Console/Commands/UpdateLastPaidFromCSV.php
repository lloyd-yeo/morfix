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
    protected $description = '(STEP 1) updating when the user last paid from a csv file';

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
        $path = app_path('november-payout.csv');
        $file = fopen($path, "r");
        $current_email = "";
        $last_pay_out_coms_date = "2017-11-25 00:00:00"; //edit here every month
        $paid_amount =0;
        $tier = 0;
            
        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
//            #$data is one row.
//            #$data[0] is first cell so on & so forth.
            $current_email = $data[0];
            $user = User::where('email', $current_email)->first();
            if ($user !== NULL) {
                if ($data[2] > 50 && !empty($data[1]) && $data[4] == 'PAID') {      //edit here every month
                    $tier = $user->tier;
                    $user->last_pay_out_date = $last_pay_out_coms_date;
                    $this->UpdateUserChargesPaid($user);
                    $this->CalculateUserPendingCommissions($user,$paid_amount,$tier);
                    $user->paid_amount = $data[2];
                    $user->pending_commission_payable = 0;
                    $user->all_time_commission = $user->all_time_commission + $data[2];
                    $user->save();
                    echo "Updated [$current_email] last pay out date to [$last_pay_out_coms_date]\n";
                    echo "Updated [$current_email] last pay out amount to [$data[2]]\n";
                    echo "Updated [$current_email] pending commission to to [$user->pending_commission]\n";
                    $paid_amount = 0;
                    $tier = 0;
                } else {
                    echo $user->email . "is not eligible for current payout\n";
                }
            }
        }
    }

    public function UpdateUserChargesPaid($user) {
        $recent_pay_out_date = Carbon::create(2017, 11, 25, 0, 0, 0, 'Asia/Singapore'); // edit here every month
//        $start_date = Carbon::parse($recent_pay_out_date)->subMonth()->startOfMonth();

        if ($user->last_pay_out_date == $recent_pay_out_date) {

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
                        ->update(['commission_given' => 1]);
                //update commission_given to commission_given after verifying code 

             //   echo "updated commission: " . $referral_stripe_charge->referrer_email . "\n";
            }

            $referral_paypal_charges = PaypalCharges::where('referrer_email', $user->email)
                    ->where('time_stamp', '<', $end_date)
                    ->where('status', 'Completed')
                    ->where('status', '!=', 'Refunded')
                    ->orderBy('time_stamp', 'desc')
                    ->update(['commission_given' => 1]);
        } else {

            echo "User not paid in recent payout date \n";
        }
    }

    public function CalculateUserPendingCommissions($user,$paid_amount,$tier) {
        $now = Carbon::now();
        $now = $now->toDateTimeString();
        $current_comms_stripe = 0;
        $current_comms_paypal = 0;
        $final_comms =0;

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

            if ($referral_stripe1_charge->subscription_id == "0137" && $tier >= 2) {
                $current_comms_stripe = $current_comms_stripe + 20;
            }
            if ($referral_stripe1_charge->subscription_id == "0297" && $tier >= 12) {
                $current_comms_stripe = $current_comms_stripe + 50;
            }
            if ($referral_stripe1_charge->subscription_id == "MX370" && ($tier == 3 || $tier == 13)) {
                $current_comms_stripe = $current_comms_stripe + 200;
            }
            if ($referral_stripe1_charge->subscription_id == "MX670" && $tier >= 22) {
                $current_comms_stripe = $current_comms_stripe + 268;
            }
            if ($referral_stripe1_charge->subscription_id == "MX970" && $tier >= 22) {
                $current_comms_stripe = $current_comms_stripe + 500;
            } else if ($referral_stripe1_charge->subscription_id == "MX297" && ($tier == 3 || $tier == 13)) {
                $current_comms_stripe = $current_comms_stripe + 120;
            }
        }
        echo "current_comms_stripe = " . $current_comms_stripe . "\n";

        $referral_paypal1_charges = PaypalCharges::where('referrer_email', $user->email)
                ->where('commission_given',0)
                ->where('status', "Completed")
                ->where('time_stamp' , '<', $now)
                ->orderBy('time_stamp', 'desc')
                ->get();
        
        foreach ($referral_paypal1_charges as $referral_paypal1_charge) {

            if ($referral_paypal1_charge->subscription_id == "0137" && $tier >= 2) {
                if ($referral_paypal1_charge->amount == "37.0000") {
                    $current_comms_paypal = $current_comms_paypal + 20;
                } elseif ($referral_paypal1_charge->amount == "74.0000" && $tier >= 2) {
                    $current_comms_paypal = $current_comms_paypal + 40;
                }
            }
            if ($referral_paypal1_charge->subscription_id == "0297" && $tier >= 12) {
                $current_comms_paypal = $current_comms_paypal + 50;
            }
            if ($referral_paypal1_charge->subscription_id == "MX370" && ($tier == 3 || $tier == 13)) {
                $current_comms_paypal = $current_comms_paypal + 200;
            }
            if ($referral_paypal1_charge->subscription_id == "MX670" && $tier >= 22) {
                $current_comms_paypal = $current_comms_paypal + 268;
            }
            if ($referral_paypal1_charge->subscription_id == "MX970" && $tier >= 22) {
                $current_comms_paypal = $current_comms_paypal + 500;
            } else if ($referral_paypal1_charge->subscription_id == "MX297" && ($tier == 3 || $tier == 13)) {
                $current_comms_paypal = $current_comms_paypal + 120;
            }
        }

        $final_comms = $current_comms_stripe + $current_comms_paypal;
        $user->pending_commission = $final_comms;
        echo "Updated pending_commission to: " . $final_comms . "\n";
        $user->save();
        echo "current_comms_paypal = " . $current_comms_paypal . "\n";
    }

}
