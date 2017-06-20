<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable as Billable;

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

}
