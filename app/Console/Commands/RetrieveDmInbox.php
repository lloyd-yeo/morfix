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
            echo "Please enter an email address as an argument.".PHP_EOL;
        }
        else{
            $instagram_profiles = InstagramProfile::where('email', $this->argument("email"))
                            ->get();

            if(sizeof($instagram_profiles) > 0){
                $ig_profile = $instagram_profiles[0];
                $instagram = InstagramHelper::initInstagram();

                if (InstagramHelper::login($instagram, $ig_profile)) {
                    $test = 2;
                    switch ($test) {
                        case 1:
                            $response = $instagram->direct->getInbox();
                            $this->manageInboxResponse($response);
                            break;
                        case 2:
                             $response = $instagram->direct->getVisualInbox();
                             $this->manageVisualInboxResponse($response);
                            break;
                        
                        default:
                            # code...
                            break;
                    }        
                }
            }
            else
                echo "Email Address was not found.".PHP_EOL;
        }
    }

    public function manageInboxResponse($response){
        if(sizeof($response) > 0){
            $inbox = $response->inbox;
            $threads = $inbox->threads;

            $i = 0;
            foreach ($threads as $thread) {
                $i++;
                echo "\nThread: [$i] \n".json_encode($thread)."\n\n";
                $this->users($thread->users);

            }
        }
        else{
            echo "Inbox is empty".PHP_EOL;
        }
    }

    public function manageVisualInboxResponse($response){
        if(sizeof($response) > 0){
            echo json_encode($response).PHP_EOL;
        }
        else{
            echo "Inbox is empty".PHP_EOL;
        }
    }

    public function users($users){
        $i = 0;
        foreach ($users as $user) {
            $i++;
            echo "\t Username: $user->username \n\n"."\t".json_encode($user)."\n";
        }
    }
}
