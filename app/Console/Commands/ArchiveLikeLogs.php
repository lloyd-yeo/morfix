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
        echo "Archiving results with log_id <= $size...\n";
        DB::insert('INSERT IGNORE INTO user_insta_profile_like_log_archive (insta_username, target_username, 
                target_media, target_media_code, log, date_liked) 
                SELECT insta_username, target_username, target_media, target_media_code, log, date_liked 
                FROM user_insta_profile_like_log 
                WHERE log_id <= ?;', [$size]);
        echo "Archiving success!\n";
        
        echo "Deleting from current table...\n";
        
        InstagramProfileLikeLog::chunk(200, function ($like_logs) {
            foreach ($like_logs as $like_log) {
                if($like_log->delete()) {
                    echo "Removed: " . $like_log->id . "\n";
                }
            }
        });
        
        echo "Deleting complete!\n";
        
        #DB::table('user_insta_profile_like_log')->where('date_liked', '<=', $date)->delete();
    }
}
