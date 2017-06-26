<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class UpdateUserCommissionPayable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:payablecsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a user\'s payable commission from a csv.';

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
        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            if ($data[4] == "No") {
                $pending_comms = $data[2];
                $user = User::where($data[0])->first();
                if ($user !== NULL) {
                    $user->pending_commission_payable = $pending_comms;
                    if ($user->save()) {
                        echo "Updated [" . $user->email . "] pending comms to " . $pending_comms . "\n";
                    }
                }
            }
        }
    }
}
