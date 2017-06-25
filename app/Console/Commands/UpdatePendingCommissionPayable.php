<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

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
            $user = User::where('paypal_email', trim($data[0]))->first();
            echo $user->user_id . "\t" . $user->name . "\n";
            $user->pending_commission_payable = 0;
            $user->save();
        }
    }

}
