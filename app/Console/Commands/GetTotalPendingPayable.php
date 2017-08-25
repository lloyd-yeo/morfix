<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\StripeDetail;
use App\User;

class GetTotalPendingPayable extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:totalpayable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the total commission for each user.';

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
        $path = storage_path('app/may-affiliate-comms2.csv');
        $path = storage_path('app/may-affiliate-comms3.csv');
        $path = storage_path('app/august-commission.csv');
        $file = fopen($path, "r");
        $current_referrer = "";
        $row = 0;
        $commission = 0;

        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            $row = $row + 1;
            
            if ($row == 1) {
                continue;
            }
            
//            var_dump($data);
//            echo "ROW: " . $row."\n";
            
            if ($row == 1) {
                
                $current_referrer = $data[1];
                $commission = 0;
                if ($data[6] == "Yes") {
                    $commission += $data[5];
                }
                
            } else if ($current_referrer != $data[1]) {
                
                //output current
                $paypal_email = "NULL";

                $user = User::where('email', $current_referrer)->first();
                if ($user->paypal_email !== NULL) {
                    $paypal_email = $user->paypal_email;
                }
                
                echo $current_referrer . "," . $paypal_email . "," . $commission . "\n";
                
                //reset
                $commission = 0;

                $current_referrer = $data[1];

                if ($data[6] == "Yes") {
                    $commission += $data[5];
                }
                
            } else {
                if ($data[6] == "Yes") {
                    $commission += $data[5];
                }
            }
        }
    }

}
