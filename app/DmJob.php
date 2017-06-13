<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DmJob extends Model
{
    protected $table ='dm_job';
    protected $primaryKey = 'job_id'; 
    public $timestamps = false;
}
