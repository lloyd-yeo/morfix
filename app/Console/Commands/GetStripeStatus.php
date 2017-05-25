<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\DefaultImageGallery;
use App\UserImages;
use App\InstagramProfilePhotoPostSchedule;
use App\StripeDetail;
use Stripe\Stripe as Stripe;

class GetStripeStatus extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:checkchargespaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        $rows = DB::connection('mysql_old')->select("SELECT referrer_vip, referred_email, referrer_email, subscription_id, charge_id, invoice_id
                            FROM insta_affiliate.get_referral_charges_of_user 
                            WHERE charge_created >= \"2017-04-01 00:00:00\"
                            AND charge_created <= \"2017-04-31 23:59:59\"
                            ORDER BY referrer_email ASC 
                            LIMIT 10000;");
        $file = fopen('export.csv', 'w');

        foreach ($rows as $row) {
            $invalid = false;
            $eligible = false;
            $invoice = \Stripe\Invoice::retrieve($row->invoice_id);
            $charge = \Stripe\Charge::retrieve($invoice->charge);
            $refunded = 0;
            if ($charge->refunded != 1) {
                $refunded = 0;
            } else {
                $refunded = 1;
            }

            if ($refunded == 1) {
                $invalid = true;
            } else if ($invoice->paid == 0) {
                $invalid = true;
            }

            if (!$invalid) {
                if ($row->referrer_vip == 1) {
                    $eligible = true;
                } else {
                    $subscription_status_rows = DB::connection('mysql_old')->select('SELECT email, subscription_id, status FROM insta_affiliate.get_user_subscription_status WHERE email = ?', [$row->referrer_email]);
                    foreach ($subscription_status_rows as $subscription_status_row) {
                        $subscription_is_active = false;
                        if ($subscription_status_row->status == "active" || $subscription_status_row->status == "trailing") {
                            $subscription_is_active = true;
                        }
                        
                        if ($row->subscription_id == $subscription_status_row->subscription_id) {
                            $eligible = true;
                            break;
                        } else if ($subscription_status_row->subscription_id == "MX370") {
                            if ($row->subscription_id == "0137") {
                                $eligible = true;
                                break;
                            }
                        } else if ($subscription_status_row->subscription_id == "MX970") {
                            if ($row->subscription_id == "0297") {
                                $eligible = true;
                                break;
                            }
                        } else if ($subscription_status_row->subscription_id == "0167") {
                            if ($row->subscription_id == "0137") {
                                $eligible = true;
                                break;
                            } else if ($row->subscription_id == "0297") {
                                $eligible = true;
                                break;
                            }
                        } else if ($subscription_status_row->subscription_id == "0297" && $row->referrer_email == "Yongshaokoko@gmail.com") {
                            if ($row->subscription_id == "0137") {
                                $eligible = true;
                                break;
                            }
                        }
                    }
                }
            }
            
            fwrite($file, $row->referrer_email . "," . $row->referred_email . "," . $row->subscription_id . "," . $invoice->id . "," . $invoice->paid . ',' . $refunded . "," . $eligible . "\n");
            $this->line($row->referrer_email . "," . $row->referred_email . "," . $row->subscription_id . "," . $invoice->id . "," . $invoice->paid . ',' . $refunded. "," . $eligible);

        }

        fclose($file);
//        try {
//            $referrers_commission = array();
//            $referrer_stripe_ids = array();
//            $referrer_active_subscription = array();
//            $referrer_referred_rows = DB::connection('mysql_old')->select("SELECT referrer, referred FROM get_referral_for_user ORDER BY referrer;");
//            $referrer_stripe_id_rows = DB::connection('mysql_old')->select("SELECT email, stripe_id FROM user_stripe_details;");
            //init referrer & their stripe_id array
//            foreach ($referrer_stripe_id_rows as $referrer_stripe_id_row) {
//                if (array_key_exists($referrer_stripe_id_row->email, $referrer_stripe_ids)) {
//                    $referrer_stripe_ids[$referrer_stripe_id_row->email] = array();
//                    $referrer_active_subscription[$referrer_stripe_id_row->email] = array();
//                }
//                $referrer_stripe_ids[$referrer_stripe_id_row->email][] = $referrer_stripe_id_row->stripe_id;
//            }
            //init commission array
//            foreach ($referrer_referred_rows as $referrer_referred_row) {
//                $referrers[$referrer_referred_row->referrer] = 0;
//            }
            //init referrers with their subscription tier &status
//            foreach ($referrer_stripe_ids as $referrer_email => $referrer_stripe_ids) {
//                foreach ($referrer_stripe_ids as $referrer_stripe_id) {
//
//                    if ($referrer_stripe_id === NULL || $referrer_stripe_id == "") {
//                        continue;
//                    }
//
//                    #echo $referrer_email . " " . $referrer_stripe_id . "\n";
//
//                    $customer = \Stripe\Customer::retrieve($referrer_stripe_id);
//                    $subscriptions = $customer->subscriptions;
//
//                    foreach ($subscriptions->data as $subscription) {
//                        $subscription_id = $subscription->id;
//                        $subscription_obj = \Stripe\Subscription::retrieve($subscription_id);
//                        
//                        
//                        
//                        
//                        #$subscription_plan_id = $subscription_obj->plan->id;
//                        #echo $subscription_plan_id . "\t" . $subscription_obj->status . "\n";
//                        $subscription_items = $subscription_obj->items->data;
//                        foreach ($subscription_items as $subscription_item) {
//                            $subscription_plan_id = $subscription_item->plan->id;
//                            DB::connection('mysql_old')->insert('INSERT IGNORE INTO insta_affiliate.user_stripe_active_subscription (stripe_id, subscription_id, status)
//                                                                VALUES (?,?,?);', [$referrer_stripe_id, $subscription_plan_id, $subscription_obj->status]);
//                            #echo $subscription_plan_id . "\t" . $subscription_obj->status . "\n";
//                        }
//                    }
//                }
//            }
//            foreach ($referrer_referred_rows as $referrer_referred_row) {
//                $referrer_email = $referrer_referred_row->referrer;
//                $referred_email = $referrer_referred_row->referred;
//
//                foreach ($referrer_stripe_ids[$referred_email] as $stripe_ids) {
//                    foreach ($stripe_ids as $stripe_id) {
//                        $referred_invoices = \Stripe\Invoice::all(array("customer" => $stripe_id));
//                        foreach ($referred_invoices->data as $referred_invoice) {
//                            $charge = \Stripe\Charge::retrieve($referred_invoice->charge);
//                            $date = \Carbon\Carbon::createFromTimestamp($charge->date)->toDateTimeString();
//                            foreach ($referred_invoice->lines->data as $referred_invoice_data) {
//                                $plan_id = $referred_invoice_data->plan->id;
//                                $refunded = 0;
//
//                                if ($charge->refunded != 1) {
//                                    $refunded = 0;
//                                } else {
//                                    $refunded = 1;
//                                }
//
//                                #fwrite($file, $row->referrer_email . "," . $row->referred_email . "," . $row->subscription_id . "," . $invoice->id . "," . $invoice->paid . ',' . $refunded . "\n");
//                                $this->line($referrer_email . "," . $referred_email . "," . $plan_id . "," . $referred_invoice->id . "," . $referred_invoice->paid . ',' . $refunded . "," . $date . "," . serialize($referrer_active_subscription[$referrer_email] . "\n"));
//                            }
//                        }
//                    }
//                }
//            }
//        } catch (\Exception $ex) {
//            echo $ex->getMessage() . "\n" . $ex->getLine() . "\n" . $ex->getTraceAsString();
//        }
    }

}
