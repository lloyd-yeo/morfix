<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CombinePayoutCSV extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'csv:combinepayout';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Combine the payout in CSV';

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

		$path      = app_path('december-payout.csv');
		$file      = fopen($path, "r");
		$all_data  = [];
		$row_count = 0;

		$current_referrer   = "";
		$current_paypal = "";
		$current_commission = 0;

		while (($data = fgetcsv($file, 0, ",")) !== FALSE) {

			if ($row_count == 0) {
				$row_count++;
				continue;
			}

			if ($current_referrer != $data[0]) {
				if ($current_referrer == "") { //if empty, init
					$current_referrer = $data[0];
					$current_paypal = $data[1];
				} else {
					echo $current_referrer . "," . $current_paypal . "," . $current_commission . "\n";
					$current_referrer = $data[0];
					$current_paypal = $data[1];
				}
				$current_commission = 0;
			}
			$current_commission += $data[2];
		}
	}
}
