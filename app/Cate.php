<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cate extends Model
{
    const CATE = 0;
    const PAGE = 1;
    const ARTICLE = 2;
    const SHOP = 3;
    const LINK = 4;

    protected $table = 'cate';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function getModelName($model)
    {
        $model_arr = [
            self::CATE => '分类',
            self::PAGE => '单页',
            self::ARTICLE => '文章',
            self::SHOP =>  '商品',
            self::LINK => '链接'
        ];

        if ($model !== null) {
            return array_key_exists($model, $model_arr) ? $model_arr[$model] : $model_arr[self::PAGE];
        }

        return '未知';
    }
}
