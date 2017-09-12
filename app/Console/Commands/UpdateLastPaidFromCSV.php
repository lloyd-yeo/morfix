<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\DB;

class UpdateLastPaidFromCSV extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $path = storage_path('app/august-commission.csv');
        $file = fopen($path, "r");

        $current_email = "";
        $last_pay_out_coms = 0;

        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            #$data is one row.
            #$data[0] is first cell so on & so forth.
            
            if ($data[2] > 0) {

                $current_email = $data[0];
                $last_pay_out_coms = $data[3];
                $user = User::where('email', $current_email)->first();

                if ($user !== NULL) {
                    $user->last_pay_out_date = $last_pay_out_coms;
                    $user->save;
                    echo "Updated [$current_email] last pay out coms to [$last_pay_out_coms]\n";
                }
            }
        }
    }

}
