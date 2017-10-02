<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\GetReferralChargesOfUser;
use App\StripeCharge;
use App\PaypalCharges;

class UpdatePendingCommissionPayableNew extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:payablenew {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payable commissions for everyone in the csv, and everyone not in';

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
        $users = array();
        if (NULL !== $this->argument("email")) {
            $time_start = microtime(true);
            $users = User::where('email', $this->argument("email"))
                    ->orderBy('user_id', 'desc')
                    ->get();
            //get this user


            foreach ($users as $user) {


                echo "Retrieved user [" . $user->email . "] [" . $user->tier . "]\n";
                Log::info("Retrieved user [" . $user->email . "] [" . $user->tier . "]\n");
                $this->UpdateUserChargesPaid($user);    //update charges to paid

                $this->UpdateUserPayableCommissions($user); //update user pending_commissions_payable
            }

            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo 'Total Execution Time: ' . $execution_time . ' Seconds' . "\n";
        } else {
            $time_start = microtime(true);

            $users = User::whereRaw('email IN (SELECT DISTINCT(email) FROM user)')
                    ->orderBy('last_pay_out_date', 'desc')
//                    ->take(3)
                    ->get();
            //Remove ->take(3) after verifying code

            foreach ($users as $user) {

                echo "Retrieved user [" . $user->email . "] [" . $user->tier . "]\n";

                $this->UpdateUserChargesPaid($user);    //update charges to paid

                $this->UpdateUserPayableCommissions($user); //update user pending_commissions_payable
            }

            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);
            echo 'Total Execution Time: ' . $execution_time . ' Seconds' . "\n";
        }
    }

    public function UpdateUserChargesPaid($user) {
        //$recent_pay_out_date = Carbon::create(2017, 9, 25, 0, 0, 0, 'Asia/Singapore');
//        $start_date = Carbon::parse($recent_pay_out_date)->subMonth()->startOfMonth();

        if ($user->last_pay_out_date !== NULL) {

            $end_date = Carbon::parse($user->last_pay_out_date)->subMonth()->endOfMonth();
            //    echo "Start date for charges:" . $start_date . "\n";
            echo "End date for charges:" . $end_date . "\n";

            // if we paid user recently, grab the charges from the start till the end of month before 
            // the month we paid him

            $referral_charges = GetReferralChargesOfUser::fromView()
                    ->where('referrer_email', $user->email)
                    ->where('charge_created', '<', $end_date)
                    ->where('commission_given', 0)
                    ->where('charge_refunded', 0)
                    ->orderBy('charge_created', 'desc')
                    ->get();


            //update last month charges to given if paid this month

            foreach ($referral_charges as $referral_charge) {

                $charges = StripeCharge::where('charge_id', $referral_charge->charge_id)
                        ->update(['testing_commission_given' => 1]);
                //update test_commission_given to commission_given after verifying code 
            }
            echo "updated testing_commission: " . $user->email . " till $end_date \n";

            $referral_paypal_charges = PaypalCharges::where('referrer_email', $user->email)
                    ->where('time_stamp', '<', $end_date)
                    ->where('status', 'Completed')
                    ->orderBy('time_stamp', 'desc')
                    ->update(['testing_commission_given' => 1]);
        } else {

            echo "$user->email not paid in recent payout date \n";
        }
    }

    public function UpdateUserPayableCommissions($user) {
        $this_month = Carbon::now()->startOfMonth();
        $current_comms_stripe = 0;
        $current_comms_paypal = 0;
        $final_comms = 0;

        echo "this month is: " . $this_month . "\n";

        if (is_null($user->last_pay_out_date)) {
            echo "User wasn't paid before";

            $referral_stripe_charges = GetReferralChargesOfUser::fromView()
                    ->where('referrer_email', $user->email)
                    ->where('charge_created', '<', $this_month)
                    ->where('charge_refunded', 0)
                    ->where('commission_given', 0)
                    ->orderBy('charge_created', 'desc')
                    ->get();

            echo "start of date of charges is since the start \n";
            echo "end of date of charges is before " . $this_month . "\n";

            foreach ($referral_stripe_charges as $referral_stripe_charge) {

                if ($referral_stripe_charge->subscription_id == "0137" && $tier >= 2) {
                    $current_comms_stripe = $current_comms_stripe + 20;
                }
                if ($referral_stripe_charge->subscription_id == "0297" && $tier >= 12) {
                    $current_comms_stripe = $current_comms_stripe + 50;
                }
                if ($referral_stripe_charge->subscription_id == "MX370" && ($tier == 3 || $tier == 13)) {
                    $current_comms_stripe = $current_comms_stripe + 200;
                }
                if ($referral_stripe_charge->subscription_id == "MX670" && $tier >= 22) {
                    $current_comms_stripe = $current_comms_stripe + 268;
                }
                if ($referral_stripe_charge->subscription_id == "MX970" && $tier >= 22) {
                    $current_comms_stripe = $current_comms_stripe + 500;
                } else if ($referral_stripe_charge->subscription_id == "MX297" && ($tier == 3 || $tier == 13)) {
                    $current_comms_stripe = $current_comms_stripe + 118.8;
                }
            }
            echo "current_comms_stripe = " . $current_comms_stripe . "\n";

            $referral_paypal1_charges = PaypalCharges::where('referrer_email', $user->email)
                    ->where('commission_given', 0)
                    ->where('status', "Completed")
                    ->where('time_stamp', '<', $this_month)
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
                    $current_comms_paypal = $current_comms_paypal + 118.8;
                }
            }

            $final_comms = $current_comms_stripe + $current_comms_paypal;

            $user->testing_pending_commission_payable = $final_comms;
            //Update testing_pending_commission_payable to pending_commission_payable after verifying code
            $user->save();
            echo "updated " . $user->email . "payable commissions to " . $final_comms . "\n";
        } else {
            $start_of_payout_month = Carbon::parse($user->last_pay_out_date)->startOfMonth();
            echo "User was paid on " . $user->last_pay_out_date . "\n";
            echo "start of date of charges is " . $start_of_payout_month . "\n";
            echo "end of date of charges is before " . $this_month . "\n";

            $referral_stripe_charges = GetReferralChargesOfUser::fromView()
                    ->where('referrer_email', $user->email)
                    ->where('charge_created', '>=', $start_of_payout_month)
                    ->where('charge_created', '<', $this_month)
                    ->where('commission_given', 0)
                    ->where('charge_refunded', 0)
                    ->orderBy('charge_created', 'desc')
                    ->get();

            foreach ($referral_stripe_charges as $referral_stripe_charge) {

                if ($referral_stripe_charge->subscription_id == "0137" && $tier >= 2) {
                    $current_comms_stripe = $current_comms_stripe + 20;
                }
                if ($referral_stripe_charge->subscription_id == "0297" && $tier >= 12) {
                    $current_comms_stripe = $current_comms_stripe + 50;
                }
                if ($referral_stripe_charge->subscription_id == "MX370" && ($tier == 3 || $tier == 13)) {
                    $current_comms_stripe = $current_comms_stripe + 200;
                }
                if ($referral_stripe_charge->subscription_id == "MX670" && $tier >= 22) {
                    $current_comms_stripe = $current_comms_stripe + 268;
                }
                if ($referral_stripe_charge->subscription_id == "MX970" && $tier >= 22) {
                    $current_comms_stripe = $current_comms_stripe + 500;
                } else if ($referral_stripe_charge->subscription_id == "MX297" && ($tier == 3 || $tier == 13)) {
                    $current_comms_stripe = $current_comms_stripe + 118.8;
                }
            }
            echo "current_comms_stripe = " . $current_comms_stripe . "\n";

            $referral_paypal1_charges = PaypalCharges::where('referrer_email', $user->email)
                    ->where('commission_given', 0)
                    ->where('status', "Completed")
                    ->where('time_stamp', '>=', $start_of_payout_month)
                    ->where('time_stamp', '<', $this_month)
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
                    $current_comms_paypal = $current_comms_paypal + 118.8;
                }
            }

            $final_comms = $current_comms_stripe + $current_comms_paypal;

            $user->testing_pending_commission_payable = $final_comms;
            //Update testing_pending_commission_payable to pending_commission_payable after verifying code
            $user->save();

            echo "updated " . $user->email . "payable commissions to " . $final_comms . "\n";
        }
    }

}
