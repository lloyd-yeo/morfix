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
    protected $signature = 'archive:like';

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
        foreach (InstagramProfileLikeLog::where('date_liked', '>=', '2017-04-26 20:42:12')
                ->where('date_liked', '<', '2017-05-07 00:00:00')
                ->cursor() as $like_log) {
            
            $archive = new LikeLogsArchive;
            $archive->insta_username = $like_log->insta_username;
            $archive->target_username = $like_log->target_username;
            $archive->target_media = $like_log->target_media;
            $archive->target_media_code = $like_log->target_media_code;
            $archive->log = $like_log->log;
            $archive->date_liked = $like_log->date_liked;
            $archive->save();
            
            $like_log->delete();
        }
        
    }
}
