<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Database\Query\Builder;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileComment;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class InteractionComment extends Command {

    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interaction:comment {email?} {partition?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comment on target user\'s photos.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        if ($this->argument("email") == "slave") {

            $partition = $this->argument("partition");

            $this->info("[Comment Interaction] queueing jobs for slave " . $partition);


            foreach (User::where("partition", $partition)->cursor() as $user) {
                
                $this->info("[" . $user->email . "] retrieving profiles...");
                if ($user->tier > 1) {

                    $instagram_profiles = array();

                    $instagram_profiles = InstagramProfile::where('auto_comment', true)
                            ->where('email', $user->email)
                            ->where('incorrect_pw', false)
                            ->get();

                    foreach ($instagram_profiles as $ig_profile) {
                        if (\Carbon\Carbon::now()->gte(new \Carbon\Carbon($ig_profile->next_comment_time))) {
                            dispatch((new \App\Jobs\InteractionComment(\App\InstagramProfile::find($ig_profile->id)))->onQueue('comments'));
                            $this->line("queued profile: " . $ig_profile->insta_username);
                            continue;
                        }
                    }
                }
                
            }
            
        } else if (NULL !== $this->argument("email")) {

            $this->info("[Comment Interaction] trying jobs for single email: " . $this->argument("email"));

            $user = User::where("email", $this->argument("email"))->first();

            $instagram_profiles = InstagramProfile::where('auto_comment', true)
                    ->where('email', $user->email)
                    ->where('incorrect_pw', false)
                    ->get();

            $this->jobHandle($instagram_profiles);
            
        } else {
            
            $this->info("[Comment Interaction] queueing jobs for all users in db.");
            
            foreach (User::cursor() as $user) {
                if ($user->tier > 2) {
                    $instagram_profiles = array();

                    $instagram_profiles = InstagramProfile::where('auto_comment', true)
                            ->where('email', $user->email)
                            ->where('incorrect_pw', false)
                            ->where('partition', 0)
                            ->get();

                    if (count($instagram_profiles) > 0) {
                        foreach ($instagram_profiles as $ig_profile) {
                            if (\Carbon\Carbon::now()->gte(new \Carbon\Carbon($ig_profile->next_comment_time))) {
                                dispatch((new \App\Jobs\InteractionComment(\App\InstagramProfile::find($ig_profile->id)))->onQueue('comments'));
                                $this->line("queued profile: " . $ig_profile->insta_username);
                                continue;
                            }
                        }
                    }
                }
            }
        }
    }

}
