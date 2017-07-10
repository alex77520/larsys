<?php

namespace App\Repositories;

use App\Cate;

class CateRepository
{
    public function getAllCates()
    {
        $cates = Cate::select('id', 'name', 'status', 'model', 'pid', 'level', 'taxis', 'created_at')->orderBy('taxis')->get();

        /*foreach ($cates as $cate) {
            $cate->model = $cate->getModelName($cate->model);
        }*/

        return $cates = setDropDownMenu($cates);
    }

    public function createCate($data)
    {
        return Cate::create($data);
    }

    public function delCateBy($cate_id)
    {
        return Cate::destroy($cate_id);
    }

    public function updateCate($cate_id, $data)
    {
        return Cate::where('id', $cate_id)->update($data);
    }

    public function findImagesAndTags($images, $type = 2)
    {
        $atlas = [];
        $imageRepository = new ImageRepository();

        foreach ($images as $image)
        {
            if ($image->type == $type)
            {
                $atlas[] = $imageRepository->findAtlasWithTagBy($image->id);
            }
        }

        return $atlas;
    }

    public function findCateBy($cate_id)
    {
        return Cate::with(['images' => function($query) {
            return $query->orderBy('type', 'asc');
        }])->find($cate_id);
    }

}