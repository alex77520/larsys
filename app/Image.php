<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    const ICON = 0;
    const BANNER = 1;
    const ATLAS = 2;
    const CAROUSEL = 3;

    protected $table = 'images';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function model()
    {
        return $this->morphTo();
    }

    public function tags()
    {
        return $this->hasOne('App\ImageTag', 'image_id');
    }

    public function getImgType($type)
    {
        $types = [
            self::ICON => '图标',
            self::BANNER => '展示图',
            self::ATLAS => '图集',
            self::CAROUSEL => '轮播图'
        ];

        return judgeType($types, $type);
    }
}
