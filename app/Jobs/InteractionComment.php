<?php

namespace App\Jobs;

use App\InstagramHelper;
use App\InstagramProfile;
use App\InteractionCommentHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use LazyJsonMapper\Exception\LazyUserOptionException;

class InteractionComment implements ShouldQueue {

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
    public $timeout = 80;

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

        $ig_profile = $this->profile;

        echo($ig_profile->insta_username . "\t" . $ig_profile->insta_pw . "\n");

        $this->instagram = InstagramHelper::initInstagram();
	    $this->instagram = InstagramHelper::setProxy($this->instagram, $ig_profile, 1);

        if (InstagramHelper::login($this->instagram, $ig_profile)) {
            try {
	            InteractionCommentHelper::unengaged($ig_profile, $this->instagram);
            } catch (LazyUserOptionException $ex) {
            	dump($ex);
            }
        }
    }

	/**
	 * The job failed to process.
	 *
	 * @param  Exception $exception
	 *
	 * @return void
	 */
	public function failed()
	{
		unset($this->instagram);
	}
}
