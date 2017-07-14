<?php

namespace App\Repositories;

use App\Article;

class ArticleRepository
{

    /**
     * 通过cate_id获取所有文章（可分页）
     *
     * @param $cate_id
     * @param string $page
     * @return mixed
     */
    public function getArticlesByCateId($cate_id, $page = '')
    {
        if ($page !== '') {
            $articles = Article::where('cate_id', $cate_id)->orderBy('taxis', 'desc')->paginate($page);
        } else {
            $articles = Article::where('cate_id', $cate_id)->orderBy('taxis', 'desc')->get();
        }

        return $articles;
    }

    /**
     * 创建新的文章
     *
     * @param $data
     * @return mixed
     */
    public function createArticle($data)
    {
        return Article::create($data);
    }

    /**
     * 通过article_id删除对应文章
     *
     * @param $article_id
     * @return int
     */
    public function delArticleBy($article_id)
    {
        return Article::destroy($article_id);
    }

    /**
     * 获取文章并带上文章图片
     *
     * @param $article_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findArticleWithImages($article_id)
    {
        return Article::with(['images' => function($query) {
            return $query->orderBy('type', 'asc');
        }])->find($article_id);
    }

    /**
     * 更新文章
     *
     * @param $article_id
     * @param $data
     * @return mixed
     */
    public function updateArticle($article_id, $data)
    {
        return Article::where('id', $article_id)->update($data);
    }
}