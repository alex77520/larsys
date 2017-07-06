<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\UploadRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    protected $upload;

    public function __construct(UploadRepository $uploadRepository)
    {
        $this->upload = $uploadRepository;
    }

    public function uploadImg(Request $request)
    {
        if ($file = $this->upload->uploadImg($request)) {

            return response()->json(['msg' => '/storage/' . $file]);
        };

        return response()->json(['msg' => '上传失败']);
    }
}
