<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $table = 'contents';

    protected $guarded = [];

    public function images()
    {
        return $this->morphMany( 'App\Image', 'model' );
    }
}
