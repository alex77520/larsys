<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cate extends Model
{
    const CATE = 0;
    const PAGE = 1;
    const ARTICLE = 2;
    const SHOP = 3;

    protected $table = 'cate';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function getModelName($model)
    {
        $model_arr = [
            self::CATE => '分类',
            self::PAGE => '单页面',
            self::ARTICLE => '文章列表',
            self::SHOP =>  '产品列表',
        ];

        if ($model !== null) {
            return array_key_exists($model, $model_arr) ? $model_arr[$model] : $model_arr[self::PAGE];
        }

        return $model_arr;
    }
}
