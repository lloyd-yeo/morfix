<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Niche extends Model {
    
    protected $primaryKey = "niche_id";
    protected $table = "niches";
    
    public function targetUsernames() {
        return NicheTarget::where('niche_id', $this->niche_id)->inRandomOrder()->get();
    }
}
