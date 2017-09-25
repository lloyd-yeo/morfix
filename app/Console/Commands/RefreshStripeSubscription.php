<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\StripeActiveSubscription;

class RefreshStripeSubscription extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:refreshstripesubscription';

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
        $subscriptions = \Stripe\Subscription::all(array('limit' => 100, 'status' => 'all'));
        foreach ($subscriptions->autoPagingIterator() as $subscription) {
            dump($subscription);
            $active_sub = new StripeActiveSubscription;
            $active_sub->stripe_id = $subscription->customer;
            if ($subscription->plan !== NULL) {
                $active_sub->subscription_id = $subscription->plan->id;
                $active_sub->status = $subscription->status;
                $active_sub->start_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_start);
                $active_sub->end_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
                $active_sub->stripe_subscription_id = $subscription->id;
                if ($active_sub->save()) {
                    echo $subscription->id . " [" . $subscription->status . "]\t" . $subscription->plan->id . "\n";
                }
            }
        }
    }

}
