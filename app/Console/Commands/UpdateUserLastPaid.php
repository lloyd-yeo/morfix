<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;

class UpdateUserLastPaid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'affiliate:updatelastpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update last paid of affiliates.';

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
        $referral_charges_users = DB::select('SELECT DISTINCT(referrer_email) FROM insta_affiliate.get_referral_charges_of_user LIMIT 1000');
        foreach ($referral_charges_users as $referral_charges_user) {
            $user = User::where('email', $referral_charges_user->referrer_email)->first();
            if ($user !== NULL && $user->paypal_email !== NULL) {
                echo $referral_charges_user->referrer_email . "\t" . $user->paypal_email . "\n";
            }
        }
        
    }
}
