<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateLastPaidFromCSV extends Command
{
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
        
        while (($data = fgetcsv($file, 200, ",")) !== FALSE) { 
            #$data is one row.
            #$data[0] is first cell so on & so forth.
        }
    }
}
