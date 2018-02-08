<?php

namespace App\Console\Commands;

use App\AddProfileRequest;
use Illuminate\Console\Command;

class AddProfileLocally extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:add';

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
        while (TRUE){
	        $add_profile_requests = AddProfileRequest::where('working_on', 0)->get();
	        foreach ($add_profile_requests as $add_profile_request) {

		        $add_profile_request->working_on = 1;
		        $add_profile_request->save();

		        $this->call('ig:login', [
			        'ig_username' => $add_profile_request->insta_username,
			        'ig_password' => $add_profile_request->insta_pw,
			        'add_profile_request_id' => $add_profile_request->id,
		        ]);
	        }
        }
    }
}
