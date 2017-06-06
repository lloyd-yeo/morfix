<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LikeLogsArchive extends Model
{
    protected $table = 'user_insta_profile_like_log_archive';
    protected $primaryKey = 'log_id';
}
