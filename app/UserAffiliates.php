<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAffiliates extends Model {

    protected $table = 'user_affiliate';
    protected $connection = 'mysql_old';
}
