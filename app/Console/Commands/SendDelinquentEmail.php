<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\PaymentFailed;
use App\StripeDetail;
use Illuminate\Support\Facades\Mail;
use App\User;

class SendDelinquentEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:delinquent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email to notify delinquent customers.';

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
        
        $delinquent_customers = \Stripe\Customer::all(array('limit'=>100));
        $delinquent_emails = array();
        
        foreach ($delinquent_customers->autoPagingIterator() as $delinquent_customer) {
            $email = $delinquent_customer->email;
            if ($delinquent_customer->delinquent) {
                $delinquent_emails[] = $email;
                $user = User::where('email', $email);
                $user->tier = 1;
                $user->user_tier = 1;
                $user->num_acct = 1;
                $user->trial_activation = 2;
                if ($user->save()) {
                    echo "Downgraded & updated [$email]\n";
                }
            }
//            if (count($delinquent_emails) > 29) {
//                break;
//            }
        }
        
//        foreach ($delinquent_emails as $delinquent_email) {
//            echo $delinquent_email . "\n";
//            Mail::to($delinquent_email)->send(new PaymentFailed());
//            sleep(60*3);
//        }
    }
}
