<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use App\StripeDetail;
use App\StripeActiveSubscription;
use App\PaypalCharges;

class GenerateStripeReferralChargesCsv extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:generatestripereferralcharges {email?} {debug?}';

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
    	$date_to_retrieve_from = "2018-01-01 00:00:00";;
        $users = array();
        $user_payout_comms = array();
        $user_payouts = array();

	    $sql_stmt = "SELECT 
                                u.last_pay_out_date, rc.charge_created, rc.referrer_email, u.paypal_email, 
                                u.tier, rc.referred_email, 
                                rc.charge_id, rc.invoice_id, 
                                rc.subscription_id, rc.charge_paid, rc.charge_refunded,
                                rc.commission_calc, rc.commission_given, u.vip
                                FROM `user` u, get_referral_charges_of_user rc 
                                WHERE rc.referrer_email = u.email
                                AND rc.charge_created < '$date_to_retrieve_from'
                                ORDER BY referrer_email ASC, charge_created DESC;";

        if ($this->argument('email') !== NULL) {
        	$email = $this->argument('email');
	        $sql_stmt = "SELECT 
                                u.last_pay_out_date, rc.charge_created, rc.referrer_email, u.paypal_email, 
                                u.tier, rc.referred_email, 
                                rc.charge_id, rc.invoice_id, 
                                rc.subscription_id, rc.charge_paid, rc.charge_refunded,
                                rc.commission_calc, rc.commission_given, u.vip
                                FROM `user` u, get_referral_charges_of_user rc 
                                WHERE rc.referrer_email = u.email
                                AND rc.referrer_email = '$email'
                                AND rc.charge_created < '$date_to_retrieve_from'
                                ORDER BY referrer_email ASC, charge_created DESC;";
        }

        $referral_charges = DB::select($sql_stmt);

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

                if ($referral_charge->vip == 1) {
	                $users[$referrer_email]["premium"] = 1;
	                $users[$referrer_email]["business"] = 1;
	                $users[$referrer_email]["pro"] = 1;
	                $users[$referrer_email]["mastermind"] = 1;
	                $users[$referrer_email]["vip"] = 1;
                }

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
                                } else if ($sub->subscription_id == "0167") {
                                    $users[$referrer_email]["premium"] = 1;
                                    $users[$referrer_email]["business"] = 1;
                                } else if ($sub->subscription_id == "0197") {
                                    $users[$referrer_email]["premium"] = 1;
                                    $users[$referrer_email]["business"] = 1;
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

                $paypal_charges_for_referrer = PaypalCharges::where('email', $referrer_email)
                                ->where('status', 'Completed')->where('time_stamp', '<', "2018-01-01 00:00:00")->get();
                foreach ($paypal_charges_for_referrer as $paypal_charge_for_referrer) {
                    if ($paypal_charge_for_referrer->subscription_id == "0137") {
                        $users[$referrer_email]["premium"] = 1;
                    } else if ($paypal_charge_for_referrer->subscription_id == "0297" && $referrer_email == "Yongshaokoko@gmail.com") {
                        $users[$referrer_email]["premium"] = 1;
                        $users[$referrer_email]["business"] = 1;
                    } else if ($paypal_charge_for_referrer->subscription_id == "0297") {
                        $users[$referrer_email]["business"] = 1;
                    } else if ($paypal_charge_for_referrer->subscription_id == "MX370" || $paypal_charge_for_referrer->subscription_id == "MX297") {
                        $users[$referrer_email]["pro"] = 1;
                    }
                }
				if ($this->argument("debug") !== NULL) {
                    dump($users[$referrer_email]);
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
                } else if ($referral_charge->subscription_id == "MX297" && ($users[$referrer_email]["pro"] == 1)) {
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
            } else if ($referral_charge->subscription_id == "MX297") {
	            $amt_to_payout = 120;
            }

            $this->line($referrer_email . "," .
                    $referral_charge->referred_email . "," .
                    $referral_charge->subscription_id . "," .
                    $amt_to_payout . "," .
                    $referral_charge->charge_created . "," .
                    $referral_charge->charge_paid . "," .
                    $referral_charge->charge_refunded . "," .
                    "Stripe," .
                    $referral_charge->charge_id . "," .
                    $eligible);

            $comms_row = array();
            $comms_row[0] = $referral_charge->referred_email;
            $comms_row[1] = $referral_charge->subscription_id;
            $comms_row[2] = $amt_to_payout;
            $comms_row[3] = $referral_charge->charge_created;
            $comms_row[4] = $referral_charge->charge_paid;
            $comms_row[5] = $referral_charge->charge_refunded;
            $comms_row[6] = $eligible;
            $comms_row[7] = $referrer_email;
            $comms_row[8] = "Stripe";
            $comms_row[9] = $referral_charge->charge_id;

            $user_payout_comms[] = $comms_row;
        }

        $paypal_charges = PaypalCharges::where('status', 'Completed')
                ->where('time_stamp', '<', $date_to_retrieve_from)
                ->orderBy('email', 'desc')
                ->get();

        foreach ($paypal_charges as $paypal_charge) {
            if ($paypal_charge->referrer_email === NULL) {
                continue;
            }
            
            $user = User::where('email', $paypal_charge->referrer_email)->first();

            if ($user !== NULL) {
                if (!array_has($users, $paypal_charge->referrer_email)) {
                    $users[$paypal_charge->referrer_email] = array();
                    $users[$paypal_charge->referrer_email]["premium"] = 0;
                    $users[$paypal_charge->referrer_email]["business"] = 0;
                    $users[$paypal_charge->referrer_email]["pro"] = 0;
                    $users[$paypal_charge->referrer_email]["mastermind"] = 0;
                    $users[$paypal_charge->referrer_email]["vip"] = 0;
                    $referrer_email = $paypal_charge->referrer_email;
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
                                    } else if ($sub->subscription_id == "0167") {
                                        $users[$referrer_email]["premium"] = 1;
                                        $users[$referrer_email]["business"] = 1;
                                    } else if ($sub->subscription_id == "0197") {
                                        $users[$referrer_email]["premium"] = 1;
                                        $users[$referrer_email]["business"] = 1;
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

                    $paypal_charges_for_referrer = PaypalCharges::where('email', $referrer_email)
                                    ->where('status', 'Completed')->where('time_stamp', '<', $date_to_retrieve_from)->get();
                    foreach ($paypal_charges_for_referrer as $paypal_charge_for_referrer) {
                        if ($paypal_charge_for_referrer->subscription_id == "0137") {
                            $users[$referrer_email]["premium"] = 1;
                        } else if ($paypal_charge_for_referrer->subscription_id == "0297" && $referrer_email == "Yongshaokoko@gmail.com") {
                            $users[$referrer_email]["premium"] = 1;
                            $users[$referrer_email]["business"] = 1;
                        } else if ($paypal_charge_for_referrer->subscription_id == "0297") {
                            $users[$referrer_email]["business"] = 1;
                        } else if ($paypal_charge_for_referrer->subscription_id == "MX370" || $paypal_charge_for_referrer->subscription_id == "MX297") {
                            $users[$referrer_email]["pro"] = 1;
                        }
                    }
                }

                $referrer_last_payout_date = \Carbon\Carbon::parse($user->last_pay_out_date);
                $charge_created_date = \Carbon\Carbon::parse($paypal_charge->time_stamp);

                if ($user->last_pay_out_date !== NULL) {
                    $referrer_last_payout_date = $referrer_last_payout_date->startOfMonth();

                    if ($charge_created_date->lt($referrer_last_payout_date)) {
                        continue;
                    }

//                    if ($user->email == "thelifeofwinners@gmail.com") {
//                        dump($referrer_last_payout_date);
//                        dump($charge_created_date);
//                    }
//                    if ($charge_created_date->year < $referrer_last_payout_date->year) {
//                $this->warn("Charge created Year is less than referrer's last pay out.");
//                        continue;
//                    }
//                    if ($charge_created_date->month < $referrer_last_payout_date->month) {
//                $this->warn("Charge created Month is less than referrer's last pay out.");
//                        continue;
//                    }
                }

                $amt_to_payout = 0;
                if ($paypal_charge->subscription_id == "0137") {
                    $amt_to_payout = 20;
                } else if ($paypal_charge->subscription_id == "0297") {
                    $amt_to_payout = 50;
                } else if ($paypal_charge->subscription_id == "MX370") {
                    $amt_to_payout = 200;
                } else if ($paypal_charge->subscription_id === NULL && $paypal_charge->amount == 74.00) {
                    $amt_to_payout = 40;
                }

                $eligible = "No";
                if ($users[$paypal_charge->referrer_email]["vip"] === 1) {
                    $eligible = "Yes";
                } else {
                    if ($paypal_charge->subscription_id == "0137" && ($users[$paypal_charge->referrer_email]["premium"] == 1 || $users[$paypal_charge->referrer_email]["pro"] == 1)) {
                        $eligible = "Yes";
                    } else if ($paypal_charge->subscription_id == "0297" && ($users[$paypal_charge->referrer_email]["business"] == 1)) {
                        $eligible = "Yes";
                    } else if ($paypal_charge->subscription_id == "MX370" && ($users[$paypal_charge->referrer_email]["pro"] == 1)) {
                        $eligible = "Yes";
                    } else if ($users[$paypal_charge->referrer_email]["premium"] == 1 && $paypal_charge->subscription_id === NULL && $paypal_charge->amount == 74.00) {
                        $eligible = "Yes";
                    }
                }

                $comms_row = array();
                $comms_row[0] = $paypal_charge->email;
                $comms_row[1] = $paypal_charge->subscription_id;
                $comms_row[2] = $amt_to_payout;
                $comms_row[3] = $paypal_charge->time_stamp;
                $comms_row[4] = 1;
                $comms_row[5] = 0;
                $comms_row[6] = $eligible;
                $comms_row[7] = $paypal_charge->referrer_email;
                $comms_row[8] = "Paypal";
                $comms_row[9] = $paypal_charge->transaction_id;

                $this->line($paypal_charge->referrer_email . "," .
                        $paypal_charge->email . "," .
                        $paypal_charge->subscription_id . "," .
                        $amt_to_payout . "," .
                        $paypal_charge->time_stamp . "," .
                        1 . "," .
                        0 . "," .
                        "Paypal," .
                        $paypal_charge->transaction_id . "," .
                        $eligible);
                
                $user_payout_comms[] = $comms_row;
            }
        }

        foreach ($user_payout_comms as $comms_row) {
            $referrer_email = $comms_row[7];
            if (!array_has($user_payouts, $referrer_email)) {
                $referrer_user = User::where("email", $referrer_email)->first();
                if ($referrer_user !== NULL) {
                    $user_payouts[$referrer_email]['paypal_email'] = $referrer_user->paypal_email;
                    $user_payouts[$referrer_email]['payout_amt'] = 0;
                }
            }

            if ($comms_row[5] == 1) {
                continue;
            }

            if ($comms_row[6] == "Yes") {
                $user_payouts[$referrer_email]['payout_amt'] = $user_payouts[$referrer_email]['payout_amt'] + $comms_row[2];
            }
        }

        foreach ($user_payouts as $referrer_email => $user_payout) {
            if ($user_payout["payout_amt"] < 50) {
                $this->line($referrer_email . "," . $user_payout['paypal_email'] . "," . $user_payout["payout_amt"] . ",Not Eligible");
            } else {
                $this->line($referrer_email . "," . $user_payout['paypal_email'] . "," . $user_payout["payout_amt"] . ",Eligible");
            }
        }
    }

}
