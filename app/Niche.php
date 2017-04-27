<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Niche extends Model {
    protected $primaryKey = "niche_id";
    protected $table = "niches";
    protected $connection = "mysql_old";
    
    public function usernames() {
        return DB::table('niche_targets')->where('niche_id', $this->niche_id);
    }
}
