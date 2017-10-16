<?php

namespace App\Console\Commands;

use App\InstagramProfile;
use Illuminate\Console\Command;
use Notification;
use App\UserInteractionFailed;
use App\Notifications\InteractionsFailed;

class TelegramTester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:tester';

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
        $failed_profiles = UserInteractionFailed::take(6)->get();
        $failed_profiles_chunks = $failed_profiles->chunk(2);

        foreach ($failed_profiles_chunks as $failed_profiles_chunk) {

            foreach ($failed_profiles_chunk as $failed_profile) {
                $userss[] = $failed_profile->insta_username;
//
            }

            $users = implode("\n", $userss);
            Notification::send($users, new InteractionsFailed($users));

            unset($userss);

        }
//		$userss = array();
//		$testers = UserInteractionFailed::take(6)->get();
//		foreach ($testers as $tester) {
//			$userss[] = $tester->email;
//		}
//
//		$user_email_collection = collect($userss);
//		$user_emails_chunks = $user_email_collection->chunk(2);
//
//		foreach ($user_emails_chunks as $user_emails) {
//			$this->line("NEW CHUNK");
//			foreach ($user_emails as $user_email) {
//				$this->line($user_email);
//			}
//		}


        //        if (count($userss) < 30) {
        //            $users = implode("\n", $userss);
        //            Notification::send($users, new InteractionsFailed($users));
        //        }
        //        else{
        //            if ($i % 30 == 0){
        //                $userssliced = array_slice($userss,0,30);
        //                $users = implode("\n", $userss);
        //                Notification::send($users, new InteractionsFailed($users));
        //                $i += 1;
        //            }
        //        }
    }
}
