<?php

namespace App\Jobs;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use AWeberAPI;
use App\User;
use App\Mail\NewPassword;

class NewFreeTrialUser implements ShouldQueue
{
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
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = new User;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->trial_activation = 1;
        $user->trial_end_date = \Carbon\Carbon::now()->addWeek();
        $user->password = str_random(8);
        $user->num_acct = 1;
        $user->active = 1;
        $user->verification_token = str_random(20);
        $user->user_tier = 1;
        $user->tier = 1;
        
        if ($user->save()) {
            
            Mail::to($user->email)->send(new NewPassword($user));
            
            $consumerKey = "AkAxBcK3kI1q0yEfgw4R4c77";
            $consumerSecret = "DEchWOGoptnjNSqtwPz3fgZg6wkMpOTWTYCJcgBF";

            $aweber = new AWeberAPI($consumerKey, $consumerSecret);
            $account = $aweber->getAccount("AgI2J88WjcAhUkFlCn3OwzLx", "wdX1JHuuhIFm9AEiJt3SVUdM5S7Z8lAE7UKmP29P");

            foreach ($account->lists as $offset => $list) {
                $list_id = $list->id;
                
                if ($list_id != 4485376) {
                    continue;
                }

                # create a subscriber
                $params = array(
                    'email' => $request->input('contact.email'),
                    'name' => $request->input('contact.name'),
                    'ip_address' => \Request::ip(),
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
        }
    }
}
