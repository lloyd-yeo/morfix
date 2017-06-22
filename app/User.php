<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable as Billable;
use App\StripeActiveSubscription;

class User extends Authenticatable {
    
    use Billable;
    use Notifiable;
    
    protected $primaryKey = 'user_id'; 
    protected $table = 'user';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verification_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function stripeDetails() {
        return StripeDetail::where('email', $this->email)->get();
    }
    
    public function deleteStripeSubscriptions() {
        //Remove al active subscription
        $deleted_subscriptions = StripeActiveSubscription::where('stripe_id', Auth::user()->stripe_id)->delete();
    }
    
    public function updateStripeSubscriptions() {
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        $subscriptions_listings = \Stripe\Subscription::all(array('customer' => Auth::user()->stripe_id));
        $subscriptions = $subscriptions_listings->data;

        $this->deleteStripeSubscriptions();

        $invoices = array();

        foreach ($subscriptions as $subscription) {
            //The Invoices under this subscription
            $invoice_listings = \Stripe\Invoice::all(array("subscription" => $subscription->id));
            $stripe_id = $subscription->customer;

            $invoices[$subscription->id] = $invoice_listings->data[0];

            $items = $subscription->items->data;
            foreach ($items as $item) {
                $plan = $item->plan;
                $plan_id = $plan->id;
                $active_subscription = new StripeActiveSubscription;
                $active_subscription->stripe_id = $stripe_id;
                $active_subscription->subscription_id = $plan_id;
                $active_subscription->status = $subscription->status;
                $active_subscription->start_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_start);
                $active_subscription->end_date = \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
                $active_subscription->stripe_subscription_id = $subscription->id;
                $active_subscription->save();
            }
        }
    }

}
