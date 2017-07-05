<?php

namespace App\Repositories;

use App\Cate;

class CateRepository
{
    public function getAllCates()
    {
        $cates = Cate::select('id', 'name', 'model', 'pid', 'level', 'taxis', 'created_at')->orderBy('taxis')->get();

        /*foreach ($cates as $cate) {
            $cate->model = $cate->getModelName($cate->model);
        }*/

        return $cates = setDropDownMenu($cates);
    }
}