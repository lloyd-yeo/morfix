<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\GetReferralChargesOfUser;
use App\StripeCharge;

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
        $recent_pay_out_date = Carbon::create(2017, 8, 25, 0, 0, 0, 'Asia/Singapore');
//        $start_date = Carbon::parse($recent_pay_out_date)->subMonth()->startOfMonth();

        if ($user->last_pay_out_date == $recent_pay_out_date) {

            $end_date = Carbon::parse($recent_pay_out_date)->subMonth()->endOfMonth();
            echo "Recent payout date:" . $recent_pay_out_date . "\n";
            //    echo "Start date for charges:" . $start_date . "\n";
            echo "End date for charges:" . $end_date . "\n";

            // if we paid user recently, grab the charges from the start till the end of month before 
            // the month we paid him

            $referral_charges = GetReferralChargesOfUser::fromView()
                    ->where('referrer_email', $user->email)
                    ->where('charge_created', '<', $end_date)
                    ->where('charge_refunded', 0)
                    ->orderBy('charge_created', 'desc')
                    ->get();


            //update last month charges to given if paid this month

            foreach ($referral_charges as $referral_charge) {

                $charges = StripeCharge::where('charge_id', $referral_charge->charge_id)
                        ->update(['test_commission_given' => 1]);
                //update test_commission_given to commission_given after verifying code 

                echo "updated test_commission: " . $referral_charge->referred_email . "\n";
            }
        } else {

            echo "User not paid in recent payout date \n";
        }
    }

    public function UpdateUserPayableCommissions($user) {
        $this_month = Carbon::now()->startOfMonth();
        $current_comms = 0;

        echo "this month is: " . $this_month . "\n";

        if (is_null($user->last_pay_out_date)) {
            echo "User wasn't paid before";

            $referral_charges = GetReferralChargesOfUser::fromView()
                    ->where('referrer_email', $user->email)
                    ->where('charge_created', '<', $this_month)
                    ->where('charge_refunded', 0)
                    ->orderBy('charge_created', 'desc')
                    ->get();

            echo "start of date of charges is since the start \n";
            echo "end of date of charges is before " . $this_month . "\n";

            foreach ($referral_charges as $referral_charge) {

                if ($referral_charge->subscription_id == "0137") {
                    $current_comms = $current_comms + 20;
                } else if ($referral_charge->subscription_id == "0297") {
                    $current_comms = $current_comms + 50;
                }
            }
            $user->testing_pending_commission_payable = $current_comms;
            //Update testing_pending_commission_payable to pending_commission_payable after verifying code
            $user->save();
        } else {
            $start_of_payout_month = Carbon::parse($user->last_pay_out_date)->startOfMonth();
            echo "User was paid on " . $user->last_pay_out_date . "\n";
            echo "start of date of charges is " . $start_of_payout_month . "\n";
            echo "end of date of charges is before " . $this_month . "\n";

            $referral_charges = GetReferralChargesOfUser::fromView()
                    ->where('referrer_email', $user->email)
                    ->where('charge_created', '>=', $start_of_payout_month)
                    ->where('charge_created', '<', $this_month)
                    ->where('charge_refunded', 0)
                    ->orderBy('charge_created', 'desc')
                    ->get();

            foreach ($referral_charges as $referral_charge) {

                if ($referral_charge->subscription_id == "0137") {
                    $current_comms = $current_comms + 20;
                } else if ($referral_charge->subscription_id == "0297") {
                    $current_comms = $current_comms + 50;
                }
            }
            $user->testing_pending_commission_payable = $current_comms;
            //Update testing_pending_commission_payable to pending_commission_payable after verifying code
            $user->save();
            echo "updated " . $user->email . "payable commissions to " . $current_comms . "\n";
        }
    }

}
