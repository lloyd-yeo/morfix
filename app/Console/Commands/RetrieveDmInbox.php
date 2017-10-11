<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use InstagramAPI\Instagram as Instagram;
use App\InstagramHelper;
use App\InstagramProfile;
use App\DmInboxHelper;

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
                       //$inbox = DmInboxHelper::retrieve($instagram);
                    $inboxResponse = $instagram->direct->getInbox();
                    echo json_encode($inboxResponse);
                }
            }
            else
                echo "Email Address was not found.".PHP_EOL;
        }
    }

    public function manageInboxResponse1($response){
        if(sizeof($response) > 0){
            echo json_encode($response).PHP_EOL;
        }
        else{
            echo "Inbox is empty".PHP_EOL;
        }
    }
    public function manageInboxResponse($response, Instagram $instagram){
        if(sizeof($response) > 0){
            $inbox = $response->inbox;
            $threads = $inbox->threads;

            $i = 0;
            foreach ($threads as $thread) {
                $i++;
                $threadResponse = $instagram->direct->getThread($thread->thread_id);
                //$this->manageThread($threadResponse->thread);   
                $this->manageItems($threadResponse->thread);
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

    public function manageShareInbox($response){
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

    public function manageThread($thread){
        $newThread = (array)$thread;
        $i = 0;
        foreach ($thread as $key => $value) {
            if(sizeof($newThread[$key]) >= 1){
                if($i == 0){
                   echo "\t".$key."\n"; 
                }
                $subObject = (object)$newThread[$key];
                $subArray = (array)$subObject;
                $j = 0;
                foreach ($subObject as $k1 => $v1) {
                    if(sizeof($subArray[$k1]) > 0){
                        if($j == 0){
                           echo "\t".$key."\n"; 
                        }
                        $subObject1 = (object) $subArray[$k1];
                        $subArray1  = (array)$subObject1;
                        foreach ($subObject1 as $k2 => $v2) {
                            if(sizeof($subArray1[$k2]) > 1){
                                //
                            }
                            else{
                                echo "\t\t\t".$k2." => ".json_encode($v2)."\n";
                            }
                        }

                        echo "\n";
                    }
                    else{
                        echo  "\t\t".$k1." => ".json_encode($v1)."\n";
                    }
                    $j++;
                }
                echo "\n";
            }
            else{
                echo "\t".$key." => ".json_encode($value)."\n";
            }

            $i++;
        }
    }

    public function manageItems($thread){
        $items = DmInboxHelper::extractItems($thread);
        if(sizeof($items) > 0){
            foreach ($items as $item) {
                if($item->item_type == "text"){
                    echo "item_id => ".$item->item_id."\n";
                    echo "\t".$item->text."\n";
                    echo "\t".date("d/m/Y h:i:s A", $item->timestamp)."\n\n";
                }
            }
        }
        else{
            echo "Items is empty. \n";
        }
    }
}
