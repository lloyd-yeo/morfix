<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdatePendingCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:updatepending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update pending commission of users.';

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
            
            if ($data[4] == "Yes") {
                $pending_comms = $data[2];
                $user = User::where('email', $data[0])->first();
                if ($user !== NULL) {
                    $user->pending_commission_payable = $pending_comms;
                    if ($user->save()) {
                        echo "Updated [" . $user->email . "] pending comms to " . $pending_comms . "\n";
                    }
                } else {
                    echo "Can't find user: " . $data[0] . "\n";
                }
            }
            
        }
    }
}
