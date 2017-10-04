<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use InstagramAPI\Instagram as Instagram;
use App\InstagramHelper;
use App\InstagramProfile;

class RetrieveDmInbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:dm {email?}';

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
        if($this->argument("email") === NULL){
            echo "Please enter an email address as an argument.";
        }
        else{
            $instagram_profiles = InstagramProfile::where('email', $this->argument("email"))
                            ->get();

            if(sizeof($instagram_profiles) > 0){
                $ig_profile = $instagram_profiles[0];
                $instagram = InstagramHelper::initInstagram();

                if (InstagramHelper::login($instagram, $ig_profile)) {
                    $inbox = $instagram->direct->getInbox();

                    if(sizeof($inbox) > 0){
                        echo json_encode($inbox[0]);
                    }
                    else{
                        echo "Inbox is empty";
                    }
                }
            }
            else
                echo "Email Address was not found.";
        }
    }
}
