<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'goods';

    protected $guarded = [];

    public function parameters()
    {
        return $this->hasOne('App\GoodsParameters', 'goods_id');
    }
}
