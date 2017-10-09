<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\InstagramProfile;
use App\InstagramHelper;

class TestInteractionGenderFilter extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:gender {insta_username} {query}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Gender Filter for Instagram Profiles';

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
        $ig_profile = InstagramProfile::where('insta_username', $this->argument('insta_username'))->first();
        if ($ig_profile !== NULL) {
            $instagram = InstagramHelper::initInstagram();
            if (InstagramHelper::login($instagram, $ig_profile)) {
                dump($instagram->people->getInfoByName($this->argument('query')));
            }
        }
    }

}
