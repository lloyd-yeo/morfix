<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\NicheTarget;
use App\NicheTargetHashtag;

class Niche extends Model {
    
    protected $primaryKey = "niche_id";
    protected $table = "niches";
    
    public function targetUsernames() {
        return NicheTarget::where('niche_id', $this->niche_id)->inRandomOrder()->get();
    }
    
    public function targetHashtags() {
        return NicheTargetHashtag::where('niche_id', $this->niche_id)->inRandomOrder()->get();
    }
}
