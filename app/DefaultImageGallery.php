<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultImageGallery extends Model {
    protected $primaryKey = 'image_id'; 
    protected $table = 'default_image_gallery';
}
