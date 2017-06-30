<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DmJob extends Model
{
    protected $table ='dm_job';
    protected $primaryKey = 'job_id'; 
    public $timestamps = false;
    
    public static function getAllFulfilledJobs() {
        //return all fulfilled jobs
        //class-specific
    }
    
    public function changeTimeToSend($timestamp) {
        //change timestamp of this dm job
        //modifies this instance or it is instance-specific.
    }
}

Math::squareRoot($arg1, $arg2); //class

//squareRoot is a function of Math

$dm_a = new DmJob(); //instance
$dm_a->changeTimeToSend($timestamp);
//changeTimeToSend is a normal function (i.e. instance-specific)
DmJob::getAllFulfilledJobs(); //method to return all jobs.
DmJob::changeTimeToSend($timestamp); //invalid, cuz no instance to modify.
$dm_a->getAllFulfilledJobs();

//:: is a static call.
//-> is a instance call.

