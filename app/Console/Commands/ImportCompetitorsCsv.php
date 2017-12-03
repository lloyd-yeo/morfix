<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class ImportCompetitorsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:competitors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import competitors from Clickfunnels exported csv.';

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
	    $path = storage_path('app/competitors.csv');
	    $file = fopen($path, "r");

	    while (($data = fgetcsv($file, 200, ",")) !== FALSE) {
//	    	dump($data);
		    if (array_key_exists(3, $data)){
			    $email = $data[3];
			    $user = User::where('email', $email)->first();
			    if ($user !== NULL) {
				    $user->is_competitor = 1;
				    $user->save();
			    } else {
				    $this->line($email);
			    }
		    }

	    }

    }
}
