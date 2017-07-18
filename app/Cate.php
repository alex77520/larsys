<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cate extends Model
{

    const CATE    = 0;
    const PAGE    = 1;
    const ARTICLE = 2;
    const SHOP    = 3;

    protected $table = 'cate';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function images()
    {
        return $this->morphMany( 'App\Image', 'model' );
    }

    public function articles()
    {
        return $this->hasMany( 'App\Article', 'cate_id' );
    }

    public function goods()
    {
        return $this->hasMany( 'App\Goods', 'cate_id' );
    }

    public function getModelName( $model )
    {
        $model_arr = [
            self::CATE    => '分类',
            self::PAGE    => '单页面',
            self::ARTICLE => '文章列表',
            self::SHOP    => '产品列表',
        ];

        return judgeType( $model_arr, $model );
    }
}
