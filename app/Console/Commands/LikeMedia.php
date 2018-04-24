<?php

namespace App\Console\Commands;

use App\InstagramHelper;
use App\InstagramProfile;
use Illuminate\Console\Command;

class LikeMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:like {insta_username?} {media_id_type?} {media_id?}';

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
        $profile = InstagramProfile::where('insta_username', $this->argument('insta_username'))->first();
        if ($profile != NULL) {
			$instagram = InstagramHelper::initInstagram(TRUE, $profile);
			if (InstagramHelper::login($instagram, $profile)) {
				$media_id = $this->argument('media_id');
				if ($this->argument('media_id_type') == 'code') {
					$media_id = \InstagramAPI\InstagramID::fromCode($media_id);
				}

				dump($media_id);
			}
        }
    }
}
