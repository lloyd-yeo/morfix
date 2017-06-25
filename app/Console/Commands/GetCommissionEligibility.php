<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\StripeDetail;
use App\User;


class GetCommissionEligibility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:eligibility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Eligibility of Commission';

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
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        $path = storage_path('app/may-affiliate-comms.csv');
        $file = fopen($path, "r");
        $current_referrer = "";
        $current_subscriptions = NULL;
        $row = 0;
        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            if ($row == 0) {
                $row++;
                continue;
            }
            
//            echo $data[1] . "\tCURRENT \n";
            
            if ($current_referrer != $data[1]) {
                
                $current_referrer = $data[1];
                
//                echo $current_referrer . "\tNEW \n";
                
//                $stripe_details = StripeDetail::where('email', $current_referrer)->get();
//                foreach ($stripe_details as $stripe_detail) {
//                    $subscriptions = \Stripe\Subscription::all(array('limit'=>100, 'customer'=>$stripe_detail->stripe_id));
//                    $current_subscriptions = $subscriptions;
//                }
                
                $user = User::where('email', $current_referrer)->first();
                $subscriptions = \Stripe\Subscription::all(array('limit' => 100, 'customer' => $user->stripe_id));
                
                $current_subscriptions = $subscriptions;
                
                
                
            }
            
            $plan__ = $data[2];
            
            $eligible = false;
            
//            echo $user->email . "\tSubscriptions Count: " . count($current_subscriptions) . "\n";
            
            $commission = 0;
            if ($plan__ == "137") {
                $commission = 20;
            } else if ($plan__ == "297") {
                $commission = 50;
            } else if ($plan__ == "MX370") {
                $commission = 200;
            } else if ($plan__ == "MX970") {
                $commission = 500;
            }
            
            foreach ($current_subscriptions->data as $subscription) {
                
//                echo $current_referrer . "\t" . $subscription->status . "\t" . $subscription->plan->id . "\n";
                
                $subscription_items = $subscription->items->data;
                
                if ($subscription->status == "active" || $subscription->status == "trialing") {
                    
                    foreach ($subscription_items as $subscription_item) {
                        
                        $plan = $subscription_item->plan;
                        $plan_id = $plan->id;
                        
                        if ($plan__ == "137") {
                            if ($plan_id == "MX370" || $plan_id == "0137" || $plan_id == "0197" || $plan_id = "0167") {
                                $eligible = true;
                            }
                        } else if ($plan__ == "297") {
                            if ($plan_id == "MX970" || $plan_id == "0297" || $plan_id == "0197" || $plan_id = "0167") {
                                $eligible = true;
                            }
                        } else if ($plan__ == "MX370") {
                            if ($plan_id == "MX370") {
                                $eligible = true;
                            }
                        } else if ($plan__ == "MX970") {
                            if ($plan_id == "MX970") {
                                $eligible = true;
                            }
                        }

                    }
                }
            }
            
//            if ($eligible == false) {
////                echo $current_referrer . "\t" . $plan__ . "\t" . "No\n";
//                echo "No\n";
//            } else if ($eligible == true) {
////                echo $current_referrer . "\t" . $plan__ . "\t" . "Yes\n";
//                echo "Yes\n";
//            }
            echo $commission . "\n";
        }
    }
}
