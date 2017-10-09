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
use App\InstagramProfileTargetUsername;
use App\InstagramProfileTargetHashtag;
use App\EngagementJob;
use App\BlacklistedUsername;
use App\InstagramProfileLikeLog;
use App\LikeLogsArchive;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;
use App\Niche;
use App\InstagramProfileMedia;
use App\InstagramHelper;

class RefreshIgProfile implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

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
    public $timeout = 180;
    
    protected $profile;
    protected $instagram;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(InstagramProfile $profile) {
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        DB::reconnect();
        
        echo($this->profile->insta_username . "\t" . $this->profile->insta_pw . "\n\n");
        
        if (!$this->initInstagramAPI($this->profile)){
            return;
        }
        
        try {
            
            $instagram_user = InstagramHelper::getUserInfo($this->instagram, $this->profile);

            $this->profile->insta_username = $instagram_user->getUsername();
            $this->profile->profile_full_name = $instagram_user->getFullName();
            $this->profile->updated_at = \Carbon\Carbon::now();
            $this->profile->follower_count = $instagram_user->getFollowerCount();
            $this->profile->num_posts = $instagram_user->getMediaCount();
            $this->profile->insta_user_id = $instagram_user->getPk();
            $this->profile->profile_pic_url = $instagram_user->getProfilePicUrl();
            $this->profile->save();

            $items = $this->instagram->timeline->getSelfUserFeed()->getItems();

            foreach ($items as $item) {
                
                try {
                    $image_url = "";
                    if (is_null($item->getImageVersions2())) {
                        //is carousel media
                        $image_url = $item->getCarouselMedia()[0]->getImageVersions2()->getCandidates()[0]->getUrl();
                    } else {
                        $image_url = $item->getImageVersions2()->getCandidates()[0]->getUrl();
                    }

                    try {
                        $new_profile_post = new InstagramProfileMedia;
                        $new_profile_post->insta_username = $this->profile->insta_username;
                        $new_profile_post->media_id = $item->getPk();
                        $new_profile_post->image_url = $image_url;
                        $new_profile_post->code = $item->getCode();
                        $new_profile_post->created_at = \Carbon\Carbon::createFromTimestamp($item->getTakenAt());
                        $new_profile_post->save();
                    } catch (\Exception $ex) {
//                        echo $ex->getMessage();
                    }
                    
                } catch (\ErrorException $e) {
                    $this->profile->error_msg = $e->getMessage();
                    $this->profile->save();
                }
            }
        } catch (\InstagramAPI\Exception\CheckpointRequiredException $checkpoint_ex) {
            $this->profile->checkpoint_required = 1;
        } catch (\InstagramAPI\Exception\NetworkException $network_ex) {
            $this->profile->error_msg = $network_ex->getMessage();
        } catch (\InstagramAPI\Exception\EndpointException $endpoint_ex) {
            if (stripos(trim($endpoint_ex->getMessage()), "The username you entered doesn't appear to belong to an account. Please check your username and try again.") !== false) {
                $this->profile->invalid_user = 1;
            } else {
                $this->profile->error_msg = $endpoint_ex->getMessage();
            }
        } catch (\InstagramAPI\Exception\IncorrectPasswordException $incorrectpw_ex) {
            $this->profile->incorrect_pw = 1;
        } catch (\InstagramAPI\Exception\AccountDisabledException $accountdisabled_ex) {
            $this->profile->account_disabled = 1;
        } catch (\InstagramAPI\Exception\RequestException $request_ex) {
            $this->profile->error_msg = $request_ex->getMessage();
        }
        $this->profile->save();
    }

    public function initInstagramAPI($ig_profile) {
        $this->instagram = InstagramHelper::initInstagram();
        return InstagramHelper::login($this->instagram, $ig_profile);
    }

}
