<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\StripeCharge;

class UpdatePendingCommissionPayableNew extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:payablenew {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payable commissions for everyone in the csv, and everyone not in';

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
        $users = array();
        if (NULL !== $this->argument("email")) {
            $users = User::where('email', $this->argument("email"))
                    ->orderBy('user_id', 'desc')
                    ->get(); //get this user

            $recent_pay_out_date = Carbon::create(2017, 8, 25, 0, 0, 0, 'Asia/Singapore');
            $start_date = "2017-07-01 00:00:00";
            $end_date = "2017-09-31 23:59:59";
            echo "Recent payout date:" . $recent_pay_out_date . "\n";
            echo "Start date for charges:" . $start_date . "\n";
            echo "End date for charges:" . $end_date . "\n"; 
            //initialize dates


            foreach ($users as $user) {
                $time_start = microtime(true);
//                $referral_charges = DB::select('SELECT * FROM get_referral_charges_of_user WHERE referrer_email = ? AND '
//                                . 'charge_created >= "2017-07-01 00:00:00" AND charge_created <= "2017-07-31 23:59:59" AND charge_refunded = 0 '
//                                . 'ORDER BY charge_created DESC;', [$user->email]);
                $current_email = $user->email;
                if ($user->last_pay_out_date == $recent_pay_out_date) {
                    
                    $referral_charges = DB::select('SELECT * FROM insta_affiliate.get_referral_charges_of_user WHERE referrer_email = ? ', [$current_email]);
                    // if we paid user recently, grab the charges from the particular month under him
               
                    //update last month charges to given if paid this month
                    var_dump($referral_charges);
//                    foreach ($referrals as $referral) {
//
//                        $charges = StripeCharge::where('charge_id', $referral->charge_id)
//                                ->get();
//                            foreach ($charges as $charge) {
//                             $charge->test_commission_given = 1;
//                              $charge->save();
//                              echo "updated test_commission";
//                            }
//                    }
                }
                $time_end = microtime(true);
                $execution_time = ($time_end - $time_start);
                echo 'Total Execution Time: ' . $execution_time . ' Seconds' . "\n";
            }
        }
    }

}
