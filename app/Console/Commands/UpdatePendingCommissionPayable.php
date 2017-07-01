<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
use App\StripeCharge;

class UpdatePendingCommissionPayable extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:payable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the payable comission for users paid in a certain excel sheet.';

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
        $path = storage_path('app/may-payment.csv');
        $file = fopen($path, "r");
        $all_data = array();
        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            echo $data[0] . "\n";
            $user = User::where('email', trim($data[0]))->first();
            $current_month = \Carbon\Carbon::now()->startOfMonth();
            echo $user->user_id . "\t" . $user->name . "\t$current_month\n";
            
            $referrals = DB::select('SELECT * FROM insta_affiliate.get_referral_charges_of_user WHERE referrer_email = ?', [$user->email]);
            
            foreach ($referrals as $referral) {
                $this->line($referral->charge_id . "\t" . $referral->invoice_id);
                $charge = StripeCharge::where('charge_id', $referral->charge_id)->where('charge_created', '<', $current_month)->first();
                echo $charge;
                #$charge->commission_paid = 1;
                #if ($charge->save()) {
                #    $this->line("Updated [" . $charge->charge_id . "] for [" . $user->email . "]");
                #}
            }
            
//            $user->pending_commission_payable = 0;
//            $user->save();
        }
    }

}
