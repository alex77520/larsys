<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use App\Repositories\ArticleRepository;
use App\Repositories\CateRepository;
use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;

class ArticleController extends Controller
{

    /**
     * @var CateRepository
     */
    private $cateRepository;
    /**
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var ImageRepository
     */
    private $imageRepository;

    public function __construct(CateRepository $cateRepository,
                                ArticleRepository $articleRepository,
                                ImageRepository $imageRepository)
    {
        $this->cateRepository = $cateRepository;
        $this->articleRepository = $articleRepository;
        $this->imageRepository = $imageRepository;
    }

    public function index($cate_id = null)
    {
        // 拿到所有文章列表的分类
        $cates = $this->cateRepository->getCatesByModelId($model_id = 2);

        // 确定第一页的cate_id
        $cate_id = is_null($cate_id) ? $cates[0]->id : $cate_id;

        $articles = $this->articleRepository->getArticlesByCateId($cate_id);

        return view('admin.article', compact('cates', 'articles', 'cate_id'));
    }

    public function add($cate_id)
    {
        return view('admin.addArticle', compact('cate_id'));
    }

    public function doAdd(ArticleRequest $request, $cate_id)
    {
        $data = $request->except(['_token', 'icon', 'banner', 'atlas', 'ImageTags']);
        $data['cate_id'] = $cate_id;

        if ($article = $this->articleRepository->createArticle($data)) {

            $this->imageRepository
                ->createImagesWithModelAndRequest($request, $model_type = 'App\Article', $model_id = $article->id);

            flash('文章添加成功！')->success();
        }

        return redirect('/admin/article/' . $cate_id);

    }

    public function edit($article_id)
    {
        $article = $this->articleRepository->findArticleWithImages($article_id);
        $atlas = $this->imageRepository->findAtlasAndTags($image = $article->images);

        // 拿到所有文章列表的分类
        $cates = $this->cateRepository->getCatesByModelId($model_id = 2);

        return view('admin.editArticle', compact('article', 'atlas', 'article_id', 'cates'));
    }

    public function doEdit(ArticleRequest $request, $article_id)
    {
        $data = $request->except(['icon', 'banner', '_token', 'atlas', 'ImageTags']);

        if ($this->articleRepository->updateArticle($article_id, $data)) {

            $this->imageRepository
                ->updateImagesWithModelAndRequest($request, $model_type = 'App\Article', $model_id = $article_id);

            flash('文章编辑成功！')->success();
        }else {
            flash('文章编辑失败！')->error();
        }

        return redirect('/admin/article/' . $data['cate_id']);
    }

    public function del($article_id)
    {
        if ($this->articleRepository->delArticleBy($article_id))
            flash('删除文章成功！')->success();

        return back();
    }
}
