<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;

class ReadLastPaidCsv extends Command
{
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
        $path = storage_path('app/june-payout.csv');
        $file = fopen($path, "r");
        $all_data = array();
        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            
            $email = $data[0];
            $paypal_email = $data[1];
            $last_paid_out = $data[2];
            
            echo Carbon::parse($last_paid_out) . "\n";
            
//            $referral_charges = DB::select('SELECT * FROM '
//                    . 'get_referral_charges_of_user '
//                    . 'WHERE charge_refunded = 0 AND charge_created >= "2017-05-01 00:00:00" '
//                    . 'AND charge_created <= "2017-05-31 23:59:59" AND referrer_email = ?', [$user->email]);
        }
        
        
    }
}
