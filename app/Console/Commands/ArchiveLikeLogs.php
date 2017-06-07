<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use InstagramAPI\Instagram as Instagram;
use InstagramAPI\SettingsAdapter as SettingsAdapter;
use InstagramAPI\InstagramException as InstagramException;
use App\User;
use App\InstagramProfile;
use App\InstagramProfileComment;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use App\LikeLogsArchive;
use App\CreateInstagramProfileLog;
use App\Proxy;
use App\DmJob;

class ArchiveLikeLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:like {size}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do up an archive of the like logs.';

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
        $size = $this->argument('size');
        DB::insert('INSERT INTO user_insta_profile_like_log_archive (insta_username, target_username, target_media, target_media_code, log, date_liked)'
                . 'SELECT insta_username, target_username, target_media, target_media_code, log, date_liked FROM user_insta_profile_like_log WHERE log_id < ' . $size . ';');
        DB::table('user_insta_profile_like_log')->where('log_id', '<', $size)->delete();
    }
}
