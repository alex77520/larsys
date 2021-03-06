<?php

namespace App\Repositories;

use App\Image;
use App\ImageTag;

class ImageRepository
{

    /**
     * 通过image_id删除图片
     *
     * @param $image_id
     * @return int
     */
    public function delImageBy( $image_id )
    {
        return Image::destroy( $image_id );
    }

    /**
     * 通过图片type获取图片
     *
     * @param $page
     * @param null $type
     * @return mixed
     */
    public function getImagesByType( $page, $type = null )
    {
        if ( is_null( $type ) ) {
            $images = Image::orderBy( 'created_at', 'desc' )->paginate( $page );
        } else {
            $images = Image::orderBy( 'created_at', 'desc' )->where( 'type', $type )->paginate( $page );
        }

        return $images;
    }

    /**
     * 创建新的图片
     *
     * @param $data
     * @return mixed
     */
    public function createImage( $data )
    {
        return Image::create( $data );
    }

    /**
     * 为图片添加新的标签
     *
     * @param $data
     * @return mixed
     */
    public function createTags( $data )
    {
        return ImageTag::create( $data );
    }

    /**
     * 获取图集和图集的标签
     *
     * @param $images
     * @param int $type
     * @return array
     */
    public function findAtlasAndTags( $images, $type = 2 )
    {
        $atlas = [];

        foreach ( $images as $image ) {
            if ( $image->type == $type ) {
                $atlas[] = $this->findAtlasWithTagBy( $image->id );
            }
        }

        return $atlas;
    }

    /**
     * 批量添加图片
     *
     * @param $data
     * @param $model_id
     * @param $model_type
     */
    public function insertImages( $data, $model_id, $model_type )
    {
        foreach ( $data as $key => $value ) {

            if ( ( ! is_null( $value ) ) && ( $value !== '' ) ) {

                $arr['type'] = $this->judgeImage( $key );
                $arr['url'] = $value;
                $arr['model_id'] = $model_id;
                $arr['model_type'] = $model_type;

                $this->createImage( $arr );
            }
        }
    }

    public function addAtlas( $atlas, $tags, $model_id, $model_type )
    {
        foreach ( $atlas as $key => $item ) {
            // 先插入图片
            $arr['type'] = 2;
            $arr['url'] = $item;
            $arr['model_id'] = $model_id;
            $arr['model_type'] = $model_type;
            $image = $this->createImage( $arr );

            // 再给到标签
            $tag['image_id'] = $image->id;
            $tag['name'] = $tags[$key];
            $this->createTags( $tag );
        }
    }

    public function updateImagesByCateId( $data, $atlas, $model_id, $model_type )
    {
        $this->delImagesBy( $model_id );

        $this->insertImages( $data, $model_id, $model_type );

        if ( ! empty( $atlas['atlas'] ) ) {
            $this->addAtlas( $atlas['atlas'], $atlas['ImageTags'], $model_id, $model_type );
        }
    }

    /**
     * @param $request
     * @param $model_type
     * @param $model_id
     */
    public function createImagesWithModelAndRequest( $request, $model_type, $model_id )
    {
        $images = $request->only( [ 'icon', 'banner' ] );

        $this->insertImages( $images, $model_id, $model_type );

        $atlas = $request->only( [ 'atlas', 'ImageTags' ] );

        if ( ! empty( $atlas['atlas'] ) ) {

            $this->addAtlas( $atlas['atlas'], $atlas['ImageTags'], $model_id, $model_type );

        }
    }

    public function updateImagesWithModelAndRequest( $request, $model_type, $model_id )
    {
        $images = $request->only( [ 'icon', 'banner' ] );

        $atlas = $request->only( [ 'atlas', 'ImageTags' ] );

        $this->updateImagesByCateId( $images, $atlas, $model_id, $model_type );
    }

    public function delImagesBy( $model_id )
    {
        return Image::where( 'model_id', $model_id )->delete();
    }

    public function findAtlasWithTagBy( $image_id )
    {
        return Image::with( [ 'tags' ] )->where( 'id', $image_id )->first();
    }

    public function judgeImage( $key )
    {
        switch ( $key ) {
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