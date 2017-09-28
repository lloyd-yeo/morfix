<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use App\PaypalCharges;

class UpdateLastPaidFromCsv2 extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:lastpaid2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset payable to 0, add payable to all time coms';

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
        $path = app_path('august-commission.csv');
        $file = fopen($path, "r");

        $current_email = "";
        $recent_pay_out_date = Carbon::create(2017, 8, 25, 0, 0, 0, 'Asia/Singapore');
        $end_date = Carbon::parse($recent_pay_out_date)->subMonth()->endOfMonth();
        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            $current_email = $data[0];
            $user = User::where('email', $current_email)->first();
            if ($user !== NULL) {
                if ($data[5] == "Paypal") {
                    echo "Recent payout date:" . $recent_pay_out_date . "\n";

                    echo "End date for charges:" . $end_date . "\n";
                     $referral_paypal_charges = PaypalCharges::where('referrer_email', $user->email)
                    ->where('time_stamp', '<', $end_date)
                    ->where('status', 'Completed')
                    ->where('status', '!=', 'Refunded')
                    ->orderBy('time_stamp', 'desc')
                    ->update(['testing_commission_given' => 1]);
                     echo "Updated for charges paid for " . $current_email;
                }
            }
        }
    }

}
