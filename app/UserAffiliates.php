<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAffiliates extends Model {

    protected $table = 'user_affiliates';
    protected $connection = 'mysql_old';
}
