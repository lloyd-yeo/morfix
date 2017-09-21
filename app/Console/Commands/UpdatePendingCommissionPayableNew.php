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
                    ->get();

            $recent_pay_out_date = Carbon::create(2017, 8, 25, 0, 0, 0, 'Asia/Singapore');
            $start_date = "2017-07-31 23:59:59";
            $end_date = "2017-07-31 23:59:59";
            echo "Recent payout date:" . $recent_pay_out_date . "\n";



            foreach ($users as $user) {

//                $referral_charges = DB::select('SELECT * FROM get_referral_charges_of_user WHERE referrer_email = ? AND '
//                                . 'charge_created >= "2017-07-01 00:00:00" AND charge_created <= "2017-07-31 23:59:59" AND charge_refunded = 0 '
//                                . 'ORDER BY charge_created DESC;', [$user->email]);
                $referrals = DB::select('SELECT * FROM insta_affiliate.get_referral_charges_of_user WHERE referrer_email = ?', [$user->email]);

                if ($user->last_pay_out_date = $recent_pay_out_date) {
                    //update last month charges to given if paid this month
                    foreach ($referrals as $referral) {
                        if ($referral->referrer_email == $user->email) {
                            $charges = StripeCharge::where('charge_id', $referral->charge_id)->where('charge_created', '<', $current_month)->get();
                            foreach ($charges as $charge) {
                                $charge->commission_given = 1;
                                echo "update test_commission";
                            }
                        }
//          $referrals = DB::select('SELECT * FROM insta_affiliate.get_referral_charges_of_user WHERE referrer_email = ?', [$user->email]);
//            
//            foreach ($referrals as $referral) {
//                $this->line($referral->charge_id . "\t" . $referral->invoice_id);
//                $charges = StripeCharge::where('charge_id', $referral->charge_id)->where('charge_created', '<', $current_month)->get();
//                foreach ($charges as $charge) {
//                    $charge->commission_given = 1;  
//                    if ($charge->save()) {
//                        $this->line("Updated [" . $charge->charge_id . "] for [" . $user->email . "]");
//                    }
//                }
//            }
//            $user->pending_commission_payable = 0;
//            $user->save();
                    }
                }
            }
        } else {
//            $users = User::whereRaw('email IN (SELECT DISTINCT(email) FROM user_insta_profile WHERE auto_dm_new_follower = 1)')
//                    ->orderBy('user_id', 'desc')
//                    ->get();
//            
//            foreach ($users as $user) {
//                $instagram_profiles = InstagramProfile::where('email', $user->email)
//                        ->get();
//                foreach ($instagram_profiles as $ig_profile) {
//                    $job = new \App\Jobs\GetDm(\App\InstagramProfile::find($ig_profile->id));
//                    $job->onQueue('getdm');
//                    dispatch($job);
//                    $this->line("Queued Profile: " . $ig_profile->insta_username);
//                }
//            }
        }
    }

}
