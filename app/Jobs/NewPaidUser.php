<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use AWeberAPI;
use App\User;
use App\ReferrerIp;
use App\UserAffiliates;
use App\Mail\NewPremium;
use App\Mail\NewPremiumAffiliate;

class NewPaidUser implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 60;
    protected $email;
    protected $name;
    protected $ip;
    protected $plan_id;
    protected $subscription_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $name, $ip, $plan_id, $subscription_id, $ip) {
        $this->email = $email;
        $this->name = $name;
        $this->ip = $ip;
        $this->plan_id = $plan_id;
        $this->subscription_id = $subscription_id;
        $this->ip = $ip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        $user = new User;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->trial_activation = 2;
        $user->trial_end_date = \Carbon\Carbon::now();
        $user->password = str_random(8);
        $user->num_acct = 1;
        $user->active = 1;
        $user->verification_token = str_random(20);
        $user->user_tier = 1;
        $user->tier = 1;

        if ($this->plan_id == "0137") {
            //Premium
            $user->tier += 1;
        } else if ($this->plan_id == "0297") {
            //Business
            $user->tier += 10;
        } else if ($this->plan_id == "MX370") {
            //Pro
            $user->tier += 2;
        } else if ($this->plan_id == "MX970") {
            //Mastermind
            $user->tier += 20;
        } else if ($this->plan_id == "MX670") {
            //Mastermind OTO
            $user->tier += 20;
        } else if ($this->plan_id == "MX297") {
            //Pro OTO
            $user->tier += 2;
        }

        if ($user->save()) {

            $referrer_ip = ReferrerIp::where('ip', $this->ip)->first();

            if ($referrer_ip !== NULL) {
                $referrer = $referrer_ip->referrer;
                
                if (UserAffiliates::where('referred', $user->user_id)->first() === NULL) {
                    $user_affiliate = new UserAffiliates;
                    $user_affiliate->referrer = $referrer;
                    $user_affiliate->referred = $user->user_id;
                    $user_affiliate->save();
                    $referrer_user = User::where('user_id', $referrer)->first();
                    if ($referrer_user !== NULL) {
                        Mail::to($user->email)->send(new NewPremiumAffiliate($referrer_user, $user));
                    }
                }
            }

            echo $user;

            if ($this->plan_id == "0137") {
                //Premium
                Mail::to($user->email)->send(new NewPremium($user));
            } else if ($this->plan_id == "0297") {
                //Business
                Mail::to($user->email)->send(new NewPremium($user));
            } else if ($this->plan_id == "MX370") {
                //Pro
                Mail::to($user->email)->send(new NewPremium($user));
            } else if ($this->plan_id == "MX970") {
                //Mastermind
                Mail::to($user->email)->send(new NewPremium($user));
            } else if ($this->plan_id == "MX670") {
                //Mastermind OTO
                Mail::to($user->email)->send(new NewPremium($user));
            } else if ($this->plan_id == "MX297") {
                //Pro OTO
                Mail::to($user->email)->send(new NewPremium($user));
            }

            $consumerKey = "AkAxBcK3kI1q0yEfgw4R4c77";
            $consumerSecret = "DEchWOGoptnjNSqtwPz3fgZg6wkMpOTWTYCJcgBF";

            $aweber = new AWeberAPI($consumerKey, $consumerSecret);
            $account = $aweber->getAccount("AgI2J88WjcAhUkFlCn3OwzLx", "wdX1JHuuhIFm9AEiJt3SVUdM5S7Z8lAE7UKmP29P");

            foreach ($account->lists as $offset => $list) {
                $list_id = $list->id;

                if ($list_id != 4485376 OR $list_id != 4631962) {
                    continue;
                }

                # create a subscriber
                $params = array(
                    'email' => $this->email,
                    'name' => $this->name,
                    'ip_address' => $this->ip,
                    'ad_tracking' => 'morfix_registration',
                    'last_followup_message_number_sent' => 1,
                    'misc_notes' => 'MorifX Registration Page'
                );

                try {
                    $subscribers = $list->subscribers;
                    $new_subscriber = $subscribers->create($params);
                } catch (\AWeberAPIException $ex) {
                    echo $ex->getMessage();
                    #return response('[' . $request->input('contact.email') . '] Free Trial Customer Updated & Registered Before in the List!', 200);
                }
            }
        } else {
            echo "Unable to save user: " . $this->email . "\n\n";
        }
    }

}
