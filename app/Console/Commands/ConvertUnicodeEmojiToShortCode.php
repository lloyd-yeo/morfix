<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\InstagramProfileCommentLog;
use App\InstagramProfileFollowLog;
use App\InstagramProfileLikeLog;
use Unicodeveloper\Emoji\Emoji;

class ConvertUnicodeEmojiToShortCode extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:emoji {insta_username?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Description';

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
        $insta_username = $this->argument('insta_username');
        $comments = \App\InstagramProfileComment::where("insta_username", $insta_username)->get();
        foreach ($comments as $comment) {
            $this->line($comment->comment);
            $re = '/:(\S+):/im';
            $str = $comment->comment;
            preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
            var_dump($matches);
            
            if (count($matches) == 0) {
                continue;
            }
            
            $emoji = new Emoji();
            try {
                foreach ($matches as $match) {
                    $alias = $match[1];
                    $unicode = $emoji->findByAlias($alias);
                    $this->line("EMOJI UNICODE: " . $unicode);
                    $replaced_str = preg_replace("/$match[0]/im", $unicode, $str);
                    $this->line($replaced_str);
                    $comment->comment = $replaced_str;
                }
                $comment->save();
            } catch (\Unicodeveloper\Emoji\Exceptions\UnknownEmoji $ex) {
                $this->error($ex->getMessage());
            }
        }
    }

}
