<?php

namespace App\Repositories;

use App\Goods;

class GoodsRepository
{

    /**
     * 通过cate_id获取栏目对应的商品
     *
     * @param $cate_id
     * @param $page
     * @return mixed
     */
    public function getGoodsByCateId($cate_id, $page)
    {
        $goods = Goods::where('cate_id', $cate_id)->orderBy('taxis', 'desc')->paginate($page);

        return $goods;
    }
}