<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\UserAffiliates;
use Carbon\Carbon;

class ReadPaypalAgreementCsv extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:paypalagreement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the paypal agreement CSV';

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
        $path = storage_path('app/august-paypal.csv');
        $file = fopen($path, "r");
        $all_data = array();
        $row_count = 0;

        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            
            if ($row_count == 0) {
                $row_count++;
                continue;
            }

            $morfix_email = $data[0];
            $referred_user = User::where('email', $morfix_email)->first();
            
            if ($referred_user !== NULL) {
                $user_affiliate = UserAffiliates::where('referred', $referred_user->user_id)->first();
                if ($user_affiliate !== NULL && $user_affiliate->referrer !== NULL) {
                    $referrer_user = User::where('user_id', $user_affiliate->referrer)->first();
                    $plan = "137";
                    if ($data[3] == "Business") {
                        $plan = "297";
                    } else if ($data[3] == "Pro") {
                        $plan = "MX370";
                    }
                    
                    $valid = 0;
                    if ($data[4] == 1) {
                        echo 
                        $referrer_user->email . "," 
                                . $referred_user->email 
                                . "," . $data[2] 
                                . "," . $plan
                                . "," . $valid
                                . "\n";
                    }
                    
                    
                }
            }
        }
    }

}
