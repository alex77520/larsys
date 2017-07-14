<?php

namespace App\Repositories;

use App\Article;

class ArticleRepository
{

    public function getArticlesByCateId($cate_id, $page = '')
    {
        if ($page !== '') {
            $articles = Article::where('cate_id', $cate_id)->orderBy('taxis', 'desc')->paginate($page);
        } else {
            $articles = Article::where('cate_id', $cate_id)->orderBy('taxis', 'desc')->get();
        }

        return $articles;
    }

    public function createArticle($data)
    {
        return Article::create($data);
    }

    public function delArticleBy($article_id)
    {
        return Article::destroy($article_id);
    }

    public function findArticleWithImages($article_id)
    {
        return Article::with(['images' => function($query) {
            return $query->orderBy('type', 'asc');
        }])->find($article_id);
    }

    public function updateArticle($article_id, $data)
    {
        return Article::where('id', $article_id)->update($data);
    }
}