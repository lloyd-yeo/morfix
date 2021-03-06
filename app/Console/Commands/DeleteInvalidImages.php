<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\InstagramProfile;
use App\InstagramProfileMedia;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class DeleteInvalidImages extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ig:invalidateimage {profile?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all invalid images.';

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
        if ($this->argument('profile') === NULL) {
            InstagramProfileMedia::chunk(200, function($medias) {
                foreach ($medias as $media) {
                    $file = $media->image_url;
                    $file_headers = @get_headers($file);
                    var_dump($file_headers);
                    $this->line("\n");
                    if ($file_headers[0] == 'HTTP/1.0 404 Not Found' || $file_headers[0] == 'HTTP/1.0 404 Key Not Found') {
                        $media->delete();
                    }
                }
            });
        } else {
            $medias = InstagramProfileMedia::where('insta_username', $this->argument('profile'))->get();
            foreach ($medias as $media) {
                $file = $media->image_url;
                $file_headers = @get_headers($file);
                var_dump($file_headers);
                $this->line("\n");
                if ($file_headers[0] == 'HTTP/1.0 404 Not Found' || $file_headers[0] == 'HTTP/1.0 404 Key Not Found') {
                    $media->delete();
                }
            }
        }
    }

}
