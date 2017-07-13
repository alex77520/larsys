<?php

namespace App\Repositories;

use App\Goods;

class GoodsRepository
{

    public function getGoodsByCateId($cate_id, $page)
    {
        $goods = Goods::where('cate_id', $cate_id)->orderBy('taxis', 'desc')->paginate($page);

        return $goods;
    }
}