<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use App\StripeDetail;
use App\StripeActiveSubscription;

class GenerateStripeReferralChargesCsv extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:generatestripereferralcharges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Stripe Referrals Charges CSV';

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

        $referral_charges = DB::select('SELECT 
                                u.last_pay_out_date, rc.charge_created, rc.referrer_email, u.paypal_email, 
                                u.tier, rc.referred_email, 
                                rc.charge_id, rc.invoice_id, 
                                rc.subscription_id, rc.charge_paid, rc.charge_refunded,
                                rc.commission_calc, rc.commission_given, u.vip
                                FROM `user` u, get_referral_charges_of_user rc 
                                WHERE pending_commission > 0
                                AND rc.referrer_email = u.email
                                AND rc.charge_created < "2017-09-01 00:00:00"
                                ORDER BY referrer_email ASC, charge_created DESC;');
        foreach ($referral_charges as $referral_charge) {
            $referrer_email = $referral_charge->referrer_email;

            if (!array_has($users, $referrer_email)) {
                $users[$referrer_email] = array();
                $users[$referrer_email]["premium"] = 0;
                $users[$referrer_email]["business"] = 0;
                $users[$referrer_email]["pro"] = 0;
                $users[$referrer_email]["mastermind"] = 0;
                $users[$referrer_email]["vip"] = 0;

                $stripe_details = StripeDetail::where('email', $referrer_email)->get();
                foreach ($stripe_details as $stripe_detail) {
                    if ($referral_charge->vip == 1) {
                        $users[$stripe_detail->email]["vip"] = 1;
                    } else {
                        $subs = StripeActiveSubscription::where('stripe_id', $stripe_detail->stripe_id)->get();
                        foreach ($subs as $sub) {
                            if ($sub->status == "active" || $sub->status == "trialing") {
                                if ($sub->subscription_id == "0137") {
                                    $users[$stripe_detail->email]["premium"] = 1;
                                } else if ($sub->subscription_id == "0297" && $stripe_detail->email == "yongshaokoko@gmail.com") {
                                    $users[$stripe_detail->email]["premium"] = 1;
                                    $users[$stripe_detail->email]["business"] = 1;
                                } else if ($sub->subscription_id == "0297") {
                                    $users[$stripe_detail->email]["business"] = 1;
                                } else if ($sub->subscription_id == "MX370" || $sub->subscription_id == "MX297") {
                                    $users[$stripe_detail->email]["pro"] = 1;
                                }
                            }
                        }
                    }
                }
            }



            $eligible = "No";
            if ($users[$stripe_detail->email]["vip"] === 1) {
                $eligible = "Yes";
            } else {
                if ($stripe_detail->subscription_id == "0137" && ($users[$stripe_detail->email]["premium"] == 1 || $users[$stripe_detail->email]["pro"] == 1)) {
                    $eligible = "Yes";
                } else if ($stripe_detail->subscription_id == "0297" && ($users[$stripe_detail->email]["business"] == 1)) {
                    $eligible = "Yes";
                } else if ($stripe_detail->subscription_id == "MX370" && ($users[$stripe_detail->email]["pro"] == 1)) {
                    $eligible = "Yes";
                }
            }

            $amt_to_payout = 0;
            if ($referral_charge->subscription_id == "0137") {
                $amt_to_payout = 20;
            } else if ($referral_charge->subscription_id == "0297") {
                $amt_to_payout = 50;
            } else if ($referral_charge->subscription_id == "MX370") {
                $amt_to_payout = 200;
            }

            $this->line($referrer_email . "," .
                    $referral_charge->referred_email . "," .
                    $referral_charge->subscription_id . "," .
                    $referral_charge->charge_created . "," .
                    $referral_charge->charge_paid . "," .
                    $referral_charge->charge_refunded . "," .
                    $eligible);
        }
    }

}
