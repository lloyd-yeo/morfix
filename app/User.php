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
        $deleted_subscriptions = StripeActiveSubscription::where('stripe_id', $this->stripe_id)->delete();
    }
    
    public function updateUserTier() {
        $user_tier = 1;
        foreach ($this->stripeDetails() as $stripe_detail) {
            $stripe_id = $stripe_detail->stripe_id;
            $user_active_subscriptions = StripeActiveSubscription::where('stripe_id', $stripe_id)->whereRaw('(status = \'active\' OR status=\'trialing\')')->get();
            foreach ($user_active_subscriptions as $active_sub) {
                $plan = $active_sub->subscription_id;
                if ($plan == "0137") {
                    $user_tier = $user_tier + 1;
                } else if ($plan == "0297") {
                    $user_tier = $user_tier + 10;
                } else if ($plan == "MX370") {
                    $user_tier = $user_tier + 2;
                } else if ($plan == "MX970") {
                    $user_tier = $user_tier + 20;
                } else if ($plan == "0167") {
                    $user_tier = $user_tier + 11;
                } else if ($plan == "0197") {
                    $user_tier = $user_tier + 11;
                }
            }
        }

        $this->tier = $user_tier;
        if ($user_tier > 1) {
            $this->save();
        }
    }

}
