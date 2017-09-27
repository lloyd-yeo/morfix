<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateLastPaidFromCSV extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:lastpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'updating when the user last paid from a csv file';

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
        $path = app_path('september-payout.csv');
        $file = fopen($path, "r");

        $current_email = "";
        $last_pay_out_coms_date = "2017-09-25 00:00:00";

        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            #$data is one row.
            #$data[0] is first cell so on & so forth.
            $current_email = $data[0];
            $user = User::where('email', $current_email)->first();
            if ($user !== NULL) {
                if ($data[3] > 50 && !empty($data[1]) && $data[4] == 'Eligible') {
                $user->testing_last_pay_out_date = $last_pay_out_coms_date;
                $user->save();
                echo "Updated [$current_email] last pay out coms to [$last_pay_out_coms_date]\n";
            }
        }
    }
}

}
