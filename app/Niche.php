<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\NicheTarget;
use App\NicheTargetHashtag;

class Niche extends Model {
    
    protected $primaryKey = "niche_id";
    protected $table = "niches";
    
    public function targetUsernames() {
        if ($this->niche_id > 0) {
            return NicheTarget::where('niche_id', $this->niche_id)->inRandomOrder()->get();
        } else {
            return array();
        }
    }
    
    public function targetHashtags() {
        if ($this->niche_id > 0) {
            return NicheTargetHashtag::where('niche_id', $this->niche_id)->inRandomOrder()->get();
        } else {
            return array();
        }
    }
}
