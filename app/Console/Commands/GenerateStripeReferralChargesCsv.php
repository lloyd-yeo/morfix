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
        $user_payout_comms = array();
        $user_payouts = array();
        $referral_charges = DB::select('SELECT 
                                u.last_pay_out_date, rc.charge_created, rc.referrer_email, u.paypal_email, 
                                u.tier, rc.referred_email, 
                                rc.charge_id, rc.invoice_id, 
                                rc.subscription_id, rc.charge_paid, rc.charge_refunded,
                                rc.commission_calc, rc.commission_given, u.vip
                                FROM `user` u, get_referral_charges_of_user rc 
                                WHERE rc.referrer_email = u.email
                                AND rc.charge_created < "2017-09-01 00:00:00"
                                ORDER BY referrer_email ASC, charge_created DESC;');
        foreach ($referral_charges as $referral_charge) {
            $referrer_email = $referral_charge->referrer_email;
            $referrer_last_payout_date = \Carbon\Carbon::now()->subYear();
            $charge_created_date = \Carbon\Carbon::parse($referral_charge->charge_created);
            if ($referral_charge->last_pay_out_date !== NULL) {
                $referrer_last_payout_date = \Carbon\Carbon::parse($referral_charge->last_pay_out_date);
                if ($charge_created_date->year < $referrer_last_payout_date->year) {
//                $this->warn("Charge created Year is less than referrer's last pay out.");
                    continue;
                }
                if ($charge_created_date->month < $referrer_last_payout_date->month) {
//                $this->warn("Charge created Month is less than referrer's last pay out.");
                    continue;
                }
            }

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
                        $users[$referrer_email]["vip"] = 1;
                    } else {
                        $subs = StripeActiveSubscription::where('stripe_id', $stripe_detail->stripe_id)->get();
                        foreach ($subs as $sub) {
                            if ($sub->status == "active" || $sub->status == "trialing") {
                                if ($sub->subscription_id == "0137") {
                                    $users[$referrer_email]["premium"] = 1;
                                } else if ($sub->subscription_id == "0297" && $referrer_email == "Yongshaokoko@gmail.com") {
                                    $users[$referrer_email]["premium"] = 1;
                                    $users[$referrer_email]["business"] = 1;
                                } else if ($sub->subscription_id == "0297") {
                                    $users[$referrer_email]["business"] = 1;
                                } else if ($sub->subscription_id == "MX370" || $sub->subscription_id == "MX297") {
                                    $users[$referrer_email]["pro"] = 1;
                                }
                            }
                        }
                    }
                }
            }

            $eligible = "No";
            if ($users[$referrer_email]["vip"] === 1) {
                $eligible = "Yes";
            } else {
                if ($referral_charge->subscription_id == "0137" && ($users[$referrer_email]["premium"] == 1 || $users[$referrer_email]["pro"] == 1)) {
                    $eligible = "Yes";
                } else if ($referral_charge->subscription_id == "0297" && ($users[$referrer_email]["business"] == 1)) {
                    $eligible = "Yes";
                } else if ($referral_charge->subscription_id == "MX370" && ($users[$referrer_email]["pro"] == 1)) {
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
                    $amt_to_payout . "," .
                    $referral_charge->charge_created . "," .
                    $referral_charge->charge_paid . "," .
                    $referral_charge->charge_refunded . "," .
                    $eligible);
            
            $comms_row = array();
            $comms_row[$referrer_email][0] = $referral_charge->referred_email;
            $comms_row[$referrer_email][1] = $referral_charge->subscription_id;
            $comms_row[$referrer_email][2] = $amt_to_payout;
            $comms_row[$referrer_email][3] = $referral_charge->charge_created;
            $comms_row[$referrer_email][4] = $referral_charge->charge_paid;
            $comms_row[$referrer_email][5] = $referral_charge->charge_refunded;
            $comms_row[$referrer_email][6] = $eligible;
            
            $user_payout_comms[] = $comms_row;
        }
        
        foreach ($user_payout_comms as $referrer_email => $comms_row) {
            if (!array_has($user_payouts, $referrer_email)) {
                $referrer_user = User::where("email", $referrer_email)->first();
                if ($referrer_user !== NULL) {
                    $user_payouts[$referrer_email]['paypal_email'] = $referrer_user->paypal_email;
                    $user_payouts[$referrer_email]['payout_amt'] = 0;
                }
            }
            
            if ($comms_row[5] == 0) {
                continue;
            }
            
            if ($comms_row[6] == "Yes") {
                $user_payouts[$referrer_email]['payout_amt'] = $user_payouts[$referrer_email]['payout_amt'] + $comms_row[2];
            }
        }
        
        foreach ($user_payouts as $referrer_email => $user_payout) {
            if ($user_payout["payout_amt"] < 50) {
                $this->line($referrer_email . "," . $user_payout['paypal_email'] . "," . $user_payout["payout_amt"] . "Eligible");
            } else {
                $this->line($referrer_email . "," . $user_payout['paypal_email'] . "," . $user_payout["payout_amt"] . "Not Eligible");
            }
        }
    }

}
