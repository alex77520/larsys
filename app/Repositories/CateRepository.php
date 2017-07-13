<?php

namespace App\Repositories;

use App\Cate;

class CateRepository
{

    public function getCatesByModel($model)
    {
        $cates = Cate::where('model', $model)->orderBy('taxis')->get();

        return $cates;
    }

    /**
     * @return array
     */
    public function getAllCates()
    {
        $cates = Cate::select('id', 'name', 'status', 'model', 'pid', 'level', 'taxis', 'created_at')
            ->orderBy('taxis')
            ->get();

        return $cates = setDropDownMenu($cates);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createCate($data)
    {
        return Cate::create($data);
    }

    /**
     * @param $cate_id
     * @return int
     */
    public function delCateBy($cate_id)
    {
        return Cate::destroy($cate_id);
    }

    /**
     * @param $cate_id
     * @param $data
     * @return mixed
     */
    public function updateCate($cate_id, $data)
    {
        return Cate::where('id', $cate_id)->update($data);
    }

    /**
     * @param $images
     * @param int $type
     * @return array
     */
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

    /**
     * @param $cate_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findCateWithImagesBy($cate_id)
    {
        return Cate::with(['images' => function($query) {
            return $query->orderBy('type', 'asc');
        }])->find($cate_id);
    }

}