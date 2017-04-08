<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Niche extends Model {
    protected $primaryKey = "niche_id";
    protected $table = "niche_settings";
    protected $connection = "mysql_old";
}
