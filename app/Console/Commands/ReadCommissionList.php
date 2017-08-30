<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class ReadCommissionList extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:commissionlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the list of commission and spit out a list of users.';

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
        $path = storage_path('app/august-commission.csv');
        $file = fopen($path, "r");
        $all_data = array();
        $row_count = 0;

        $referrers = array();
        $referrer_eligiblity = array();
        $referrer_paypal_email = array();

        $bartu_referral = array();

        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {

            if ($row_count == 0) {
                $row_count++;
                continue;
            }

            $referrer_email = $data[0];

            if ($referrer_email == "gabriel@instaffiliates.com") {
                continue;
            }

            if (key_exists($referrer_email, $referrers)) {
                $current_comms = $referrers[$referrer_email];

                if ($data[7] == "Eligible") {
                    if ($data[2] == "137") {
                        $current_comms += 20;
                        $referrer_eligiblity[$referrer_email] = "Eligible";
                    } else if ($data[2] == "297") {
                        $current_comms += 50;
                    }
                    $referrers[$referrer_email] = $current_comms;
                    if ($referrer_email == "thelifeofwinners@gmail.com") {
                        $bartu_referral[] = $data[1] . "," . $data[2] . "," . $data[5];
                    }
                }
            } else {
                $user = User::where('email', $referrer_email)->first();
                if ($user !== NULL) {

                    $current_comms = 0;
                    $referrers[$referrer_email] = $current_comms;
                    $referrer_paypal_email[$referrer_email] = $user->paypal_email;
                    $referrer_eligiblity[$referrer_email] = "Not Eligible";
                    if ($data[7] == "Eligible") {
                        if ($data[2] == "137") {
                            $current_comms = 20;
                            $referrer_eligiblity[$referrer_email] = "Eligible";
                        } else if ($data[2] == "297") {
                            $current_comms = 50;
                        }
                        $referrers[$referrer_email] = $current_comms;
                        if ($referrer_email == "thelifeofwinners@gmail.com") {
                            $bartu_referral[] = $data[1] . "," . $data[2] . "," . $data[5];
                        }
                    }
                }
            }
        }

//        foreach ($referrers as $referrer_email => $comms) {
//            if ($comms < 50) {
//                $referrer_eligiblity[$referrer_email] = "Not Eligible";
//            }
//            echo $referrer_email . "," . $referrer_paypal_email[$referrer_email] . "," . $comms . "," . $referrer_eligiblity[$referrer_email] . "\n";
//        }

        foreach ($bartu_referral as $referral) {
            echo $referral . "\n";
        }
    }

}
