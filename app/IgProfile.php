<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IgProfile extends Model
{
    protected $primaryKey = 'id'; 
    protected $table = "user_insta_profile";
    protected $connection = 'mysql_old';
}
