<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\CateRepository;
use App\Repositories\GoodsRepository;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{

    /**
     * @var CateRepository
     */
    private $cateRepository;
    /**
     * @var GoodsRepository
     */
    private $goodsRepository;

    public function __construct(CateRepository $cateRepository,
                                GoodsRepository $goodsRepository)
    {

        $this->cateRepository = $cateRepository;
        $this->goodsRepository = $goodsRepository;
    }

    public function index($cate_id = null)
    {
        // 拿到产品页列表
        $cates = $this->cateRepository->getCatesByModel($model = 3);

        // 确定第一页的cate_id
        $cate_id = is_null($cate_id) ? $cates[0]->id : $cate_id;

        $goods = $this->goodsRepository->getGoodsByCateId($cate_id, $page = 10);

        return view('admin.goods', compact('cates', 'cate_id', 'goods'));
    }
}
