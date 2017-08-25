<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class ReadCommissionList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:commissionlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read the list of commission and spit out a list of users.';

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
        $path = storage_path('app/august-commission.csv');
        $file = fopen($path, "r");
        $all_data = array();
        $row_count = 0;

        while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
            
            if ($row_count == 0) {
                $row_count++;
                continue;
            }
            
            
            
        }
    }
}
