<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileFollowLog;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\Niche;
use App\NicheTarget;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\InstagramHelper;
use App\InteractionFollowHelper;

class InteractionFollow implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $profile;
    protected $instagram;
    
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 480;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\InstagramProfile $profile) {
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        DB::reconnect();
        
        $follow_mode = InteractionFollowHelper::setFollowMode($this->profile);
        
        echo "[" . $this->profile->insta_username . "] Niche: " . $this->profile->niche . 
                " Auto_Follow: " . $this->profile->auto_follow . 
                " Auto_Unfollow: " . $this->profile->auto_unfollow . "\n";
        
        if ($follow_mode > 0) { //unfollow segment
        
            //check quota first
            if ($this->profile->unfollow_quota > 0) {
                
                echo "[" . $this->profile->insta_username . "] beginning unfollowing sequence.\n";
                $this->initInstagramAPI($this->profile);
                
                if ($follow_mode === 2) { //forced unfollow, add users to unfollow
                    
                }
                
                $users_to_unfollow = InstagramProfileFollowLog::where('insta_username', $this->profile->insta_username)
                        ->where('unfollowed', false)
                        ->where('follow', true)
                        ->orderBy('date_inserted', 'asc')
                        ->take(2)
                        ->get();
                
                foreach ($users_to_unfollow as $user_to_unfollow) {
                    
                    echo "[" . $this->profile->insta_username . "] retrieved: " 
                            . $user_to_unfollow->follower_username . "\n";
                    
                    $unfollowed = InteractionFollowHelper::unfollow($this->profile, $this->instagram, $user_to_unfollow);
                    
                    if ($unfollowed === 2) {
                        continue;
                    } else if ($unfollowed <= 1) {
                        break;
                    }
                }
                
            } else {
                echo "[" . $this->profile->insta_username . "] does not have enough <unfollow_quota> left. \n\n";
            }
            
        } else if ($follow_mode === 0) { //follow segment
            
            //check quota first
            if ($this->profile->follow_quota > 0) {
                
                echo "[" . $insta_username . "] beginning following sequence.\n";
                $this->initInstagramAPI($this->profile);
                
                
                
                
            } else {
                echo "[" . $this->profile->insta_username . "] does not have enough <follow_quota> left. \n\n";
            }
            
        }
    }
    
    public function initInstagramAPI($ig_profile) {
        $this->instagram = InstagramHelper::initInstagram();
        if (!InstagramHelper::login($this->instagram, $ig_profile)) {
            exit();
        }
    }

}
