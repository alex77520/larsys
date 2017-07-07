<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'image_tags';
    
    protected $primaryKey = 'id';
    
    protected $guarded = [];
}
