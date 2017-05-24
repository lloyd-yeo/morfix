<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaqTopic extends Model {

    protected $primaryKey = 'id';
    protected $table = "morfix_topics";
    protected $connection = 'mysql_old';
    public $timestamps = false;

}
