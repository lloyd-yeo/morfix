<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;

class ReadLastPaidCsv extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:unpaid';

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
        $path = storage_path('app/june-payment.csv');
        $file = fopen($path, "r");
        $all_data = array();
        $row_count = 0;

        $current_referrer = "";
        $current_commission = 0;

        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            if ($row_count == 0) {
                $row_count++;
                continue;
            }

            if ($current_referrer != $data[0]) {
                if ($current_referrer == "") {
                    $current_referrer = $data[0];
//                    echo $current_referrer . "\n";
                } else {

                    $user = User::where("email", $current_referrer)->first();
                    $paypal_email = $user->paypal_email;
                    
                    echo $user->email . "," . $user->paypal_email . "," . $current_commission . "\n";
                    
                    //reset
                    $current_referrer = $data[0];
                    $current_commission = 0;
                }
            }

            if ($data[3] == "NOT REFUNDED" && $data[5] == "Paid" && $data[6] == "Eligible") {
                if ($data[2] == "137") {
                    $current_commission += 20;
                } else if ($data[2] == "0297") {
                    $current_commission += 50;
                } else if ($data[2] == "0167") {
                    $current_commission += 26.8;
                } else if ($data[2] == "0197") {
                    $current_commission += 38.8;
                } else if ($data[2] == "MX370") {
                    $current_commission += 200;
                }
            }
        }
    }

}
