<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\YourlsUrl;
use App\StripeDetail;
use App\User;
use Response;

class GetUserSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:list {stripe_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get subscription of user';

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
        $stripe_id = $this->argument('stripe_id');
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        
        $subscriptions = \Stripe\Subscription::all(array('limit'=>100, 'status'=>'all', 'customer'=>$stripe_id));
        foreach ($subscriptions->autoPagingIterator() as $subscription) {
            echo $subscription->id . " [" . $subscription->status . "]\t" . $subscription->plan->id . "\n";
        }
    }
}
