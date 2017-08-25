<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
use Carbon\Carbon;
use Stripe\Stripe;

class GetUsersWithPendingCommisions extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:pendingcommission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List users with Pending Commission.';

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
        $users = User::where('pending_commission', '>', 0)->get();
        
        foreach ($users as $user) {
//            $paypal_email = "NULL";
//            if ($user->paypal_email !== NULL) {
//                $paypal_email = $user->paypal_email;
//            }
//            $last_pay_out_date = "NULL";
//            if ($user->last_pay_out_date !== NULL) {
//                $last_pay_out_date = $user->last_pay_out_date;
//            }
//            echo $user->email . "," . $paypal_email . "," . $user->pending_commission . "," . $user->user_id . "," . $last_pay_out_date . "\n";
            $dt = NULL;

            if ($user->last_pay_out_date !== NULL) {
                $dt = Carbon::parse($user->last_pay_out_date);
//                echo "mth: " . $dt->month . "\n";
            }
            
            $referral_charges = NULL;

            if ($user->last_pay_out_date === NULL) {
                $referral_charges = DB::select('SELECT * FROM '
                                . 'get_referral_charges_of_user '
                                . 'WHERE charge_refunded = 0 '
                                . 'AND charge_created >= "2017-01-01 00:00:00" '
                                . 'AND charge_created <= "2017-07-31 23:59:59" AND referrer_email = ?', [$user->email]);
            } else if ($user->last_pay_out_date !== NULL) {
                $month = $dt->month;
                $referral_charges = DB::select('SELECT * FROM '
                                . 'get_referral_charges_of_user '
                                . 'WHERE charge_refunded = 0 '
                                . 'AND charge_created >= "2017-0' . $month . '-01 00:00:00" '
                                . 'AND charge_created <= "2017-07-31 23:59:59" AND referrer_email = ?', [$user->email]);
            }

            \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
            
            $active_subscriptions = array();
            
            $subscriptions = \Stripe\Subscription::all(array('limit' => 100, 'customer' => $user->stripe_id));

            foreach ($subscriptions->autoPagingIterator() as $subscription) {
//                echo $subscription . "\n\n\n\n";
                $stripe_id = $subscription->customer;
                $items = $subscription->items->data;
                foreach ($items as $item) {
                    $plan = $item->plan;
                    $plan_id = $plan->id;
                    
                    if ($subscription->status == "active" || $subscription->status == "trialing") {
                        $active_subscriptions[] = $plan_id;
                    }
                }
            }


            foreach ($referral_charges as $referral_charge) {

                $eligibility = "Not Eligible";
                if ($user->vip == 1) {
                    $eligibility = "Eligible";
                } else {
                    if (in_array($referral_charge->subscription_id, $active_subscriptions)) {
                        $eligibility = "Eligible";
                    } else if (in_array("0197", $active_subscriptions)) {
                        if ($referral_charge->subscription_id == "0137") {
                            $eligibility = "Eligible";
                        } else if ($referral_charge->subscription_id == "0297") {
                            $eligibility = "Eligible";
                        }  else if ($referral_charge->subscription_id == "0167") {
                            $eligibility = "Eligible";
                        } else if ($referral_charge->subscription_id == "0197") {
                            $eligibility = "Eligible";
                        }
                    } else if (in_array("0167", $active_subscriptions)) {
                        if ($referral_charge->subscription_id == "0137") {
                            $eligibility = "Eligible";
                        } else if ($referral_charge->subscription_id == "0297") {
                            $eligibility = "Eligible";
                        }
                    } else if (in_array("0297", $active_subscriptions) && $user->email == "Yongshaokoko@gmail.com") {
                        if ($referral_charge->subscription_id == "0137") {
                            $eligibility = "Eligible";
                        }
                    } else if (in_array("MX370", $active_subscriptions)) {
                        if ($referral_charge->subscription_id == "0137") {
                            $eligibility = "Eligible";
                        }
                    }
                }


                $refunded = "NOT REFUNDED";
                if ($referral_charge->charge_refunded) {
                    $refunded = "REFUNDED";
                }

                $charge_paid = "Not Paid";
                if ($referral_charge->charge_paid) {
                    $charge_paid = "Paid";
                }

                echo $referral_charge->referrer_email . "," . $referral_charge->referred_email . "," .
                $referral_charge->subscription_id . "," . $refunded . "," . $referral_charge->invoice_id . "," . $charge_paid . "," . $eligibility . "\n";
            }
        }
    }

}
