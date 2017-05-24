<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaqQna extends Model {

    protected $primaryKey = 'id';
    protected $table = "morfix_qna";
    protected $connection = 'mysql_old';
    public $timestamps = false;

}
