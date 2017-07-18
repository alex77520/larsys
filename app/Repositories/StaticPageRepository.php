<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;

class StaticPageRepository
{

    /**
     * @var CateRepository
     */
    private $cateRepository;

    public function __construct( CateRepository $cateRepository )
    {

        $this->cateRepository = $cateRepository;
    }

    /**
     * 将静态页面文件写入本地磁盘
     *
     * @param $page
     * @param string $folder
     * @param string $id
     * @return bool
     */
    public function create( $page, $folder = '', $id = '' )
    {
        return $file = Storage::disk( 'public' )->put( 'logs/' . $folder . '/' . $id . '.html', $page );
    }

    /**
     * 生成单个栏目静态页面
     *
     * @param $cate_id
     */
    public function createSingleCatePage( $cate_id )
    {
        // 获取当前栏目在前台的url
        $url = $this->cateRepository->createUrlByCateId( $cate_id );

        // 跳过无意义的url，防止批量生成时中断
        if ( $url !== 'javascript:void(0);' ) {
            try {
                $page = file_get_contents( $url );

            } catch ( \Exception $e ) {
                $code = $e->getCode();

                if ( $code === 0 ) {
                    abort( 404 );
                }
            }

            $this->create( $page, $folder = 'categories', $filename = $cate_id );
        }
    }

    /**
     * 批量生成栏目静态页
     */
    public function batchCatePageCreate()
    {
        $categories = $this->cateRepository->getAllCates();

        foreach ( $categories as $category ) {
            $this->createSingleCatePage( $category['id'] );
        }
    }

    /**
     * 生成单个文章静态页面
     *
     * @param $article_id
     */
    public function createSingleArticlePage( $article_id )
    {
        try {
            // web.php => /article/{article_id}
            $page = file_get_contents( url( '/article/' . $article_id ) );
        } catch ( \Exception $e ) {
            $code = $e->getCode();

            if ( $code === 0 ) {
                abort( 404 );
            }
        }

        $this->staticPageRepository->create( $page, $folder = 'article', $filename = $article_id );
    }

    /**
     * 批量生成文章静态页
     *
     * @param $cate_id
     */
    public function batchArticlePageCreate( $cate_id )
    {
        $articles = $this->cateRepository->getCateArticlesBy( $cate_id );

        foreach ( $articles as $article ) {

            $this->createSingleArticlePage( $article->id );
        }
    }
}