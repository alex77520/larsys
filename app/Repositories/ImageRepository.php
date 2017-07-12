<?php

namespace App\Repositories;

use App\Image;
use App\ImageTag;

class ImageRepository
{

    public function delImageBy($image_id)
    {
        return Image::destroy($image_id);
    }

    public function getImagesByType($page, $type = null)
    {
        if (is_null($type)) {
            $images = Image::orderBy('created_at', 'desc')->paginate($page);
        } else {
            $images = Image::orderBy('created_at', 'desc')->where('type', $type)->paginate($page);
        }

        return $images;
    }

    public function createImage($data)
    {
        return Image::create($data);
    }

    public function createTags($data)
    {
        return ImageTag::create($data);
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

    public function addAtlas($atlas, $tags, $model_id, $model_type)
    {
        foreach ($atlas as $key => $item) {
            // 先插入图片
            $arr['type'] = 2;
            $arr['url'] = $item;
            $arr['model_id'] = $model_id;
            $arr['model_type'] = $model_type;
            $image = $this->createImage($arr);

            // 再给到标签
            $tag['image_id'] = $image->id;
            $tag['name'] = $tags[$key];
            $this->createTags($tag);
        }
    }

    public function updateImagesByCateId($data, $model_id, $model_type, $atlas)
    {
        $this->delImagesBy($model_id);

        $this->insertImages($data, $model_id, $model_type);

        $this->addAtlas($atlas['atlas'], $atlas['tags'], $model_id, $model_type);
    }

    public function delImagesBy($model_id)
    {
        return Image::where('model_id', $model_id)->delete();
    }

    public function findAtlasWithTagBy($image_id)
    {
        return Image::with(['tags'])->where('id', $image_id)->first();
    }

    public function judgeImage($key)
    {
        switch ($key) {
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