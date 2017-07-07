<?php

namespace  App\Repositories;

use App\Image;

class ImageRepository
{
    public function createImage($data)
    {
        return Image::create($data);
    }

    public function insertImages($data, $model_id, $model_type)
    {
        foreach ($data as $key => $value) {

            if ((! is_null($value)) && ($value !== '')) {

                $arr['type'] = $this->judgeImage($key);
                $arr['url'] = $value;
                $arr['model_id'] = $model_id;
                $arr['model_type'] = $model_type;

                $this->createImage($arr);
            }
        }
    }

    public function updateImagesByCateId($data, $model_id, $model_type)
    {
        $this->delImagesBy($model_id);

        $this->insertImages($data, $model_id, $model_type);
    }

    public function delImagesBy($model_id)
    {
        return Image::where('model_id', $model_id)->delete();
    }

    public function judgeImage($key)
    {
        switch($key) {
            case 'icon':
                $type = 0;
                break;
            case 'banner':
                $type = 1;
                break;
            case 'atla';
                $type = 2;
                break;
            case 'carousel':
                $type = 3;
                break;
            default:
                $type = 1;
                break;
        }

        return $type;
    }

}