<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\StaticPageRepository;

class StaticPageController extends Controller
{

    /**
     * @var StaticPageRepository
     */
    private $staticPageRepository;

    /**
     * StaticPageController constructor.
     * @param StaticPageRepository $staticPageRepository
     */
    public function __construct( StaticPageRepository $staticPageRepository )
    {

        $this->staticPageRepository = $staticPageRepository;
    }

    /**
     * @param $cate_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function catePageCreate( $cate_id )
    {
        $this->staticPageRepository->createSingleCatePage( $cate_id );

        flash( '静态页生成成功！' )->success();

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function allCatePageCreate()
    {
        $this->staticPageRepository->batchCatePageCreate();

        flash( '批量生成栏目静态页成功！' )->success();

        return redirect()->back();
    }

    /**
     * @param $article_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function singleArticlePageCreate( $article_id )
    {
        $this->staticPageRepository->createSingleArticlePage( $article_id );

        flash( '静态页生成成功！' )->success();

        return redirect()->back();
    }

    public function allArticlePageCreate( $cate_id )
    {
        $this->staticPageRepository->batchArticlePageCreate( $cate_id );

        flash( '批量生成文章静态页成功！' )->success();

        return redirect()->back();
    }
}
