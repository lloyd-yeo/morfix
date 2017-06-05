<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EngagementJob extends Model
{
    protected $table = 'engagement_job_queue';
    protected $connection = 'mysql_old';
    protected $primaryKey = 'job_id'; 
    public $timestamps = false;
}
