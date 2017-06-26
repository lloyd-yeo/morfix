<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
class UpdatePendingCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:updatepending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update pending commission of users.';

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
        $path = storage_path('app/may-payout-final.csv');
        $file = fopen($path, "r");
        
        $current_email = "";
        $current_comms = 0;
        
        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            
            if ($data[4] == "Yes") {
                
                if ($current_email != $data[0]) {
                    $current_email = $data[0];
                    $current_comms = 0;
                }
                
                $referral_charges = DB::select('SELECT * FROM get_referral_charges_of_user WHERE referrer_email = ? AND '
                        . 'charge_created >= "2017-06-01 00:00:00" AND charge_created <= "2017-06-31 23:59:59" AND charge_refunded = 0 '
                        . 'ORDER BY charge_created DESC;', [$data[0]]);
                
                foreach ($referral_charges as $referral_charge) {
                    
                    if ($referral_charge->subscription_id == "0137") {
                        $current_comms = $current_comms + 20;
                    } else if ($referral_charge->subscription_id == "0297") {
                        $current_comms = $current_comms + 50;
                    }
                    
                    echo $referral_charge->referrer_email . "\t" . $referral_charge->referred_email . "\t"  . $referral_charge->subscription_id . " [$current_comms]\n";
                }
            }
            
        }
    }
}
