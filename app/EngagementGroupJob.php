<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EngagementGroupJob extends Model {

    protected $table = 'engagement_group_job';
    protected $connection = 'mysql_old';
    protected $primaryKey = 'media_id'; 
    public $timestamps = false;
    
}
