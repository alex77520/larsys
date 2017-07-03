<?php

namespace App\Repositories;

use App\Cate;

class CateRepository
{
    public function getAllCates()
    {
        $cates = Cate::orderBy('taxis')->get();

        foreach ($cates as $cate) {
            $cate->model = $cate->getModelName($cate->model);
        }

        return $cates = buildTree($cates->toArray());
    }
}