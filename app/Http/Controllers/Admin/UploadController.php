<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\UploadRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{

    /**
     * @var UploadRepository
     */
    protected $uploadRepository;

    /**
     * UploadController constructor.
     * @param UploadRepository $uploadRepository
     */
    public function __construct(UploadRepository $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImg(Request $request)
    {
        if ($file = $this->uploadRepository->uploadImg($request)) {

            return response()->json(['msg' => '/storage/' . $file]);
        };

        return response()->json(['msg' => '上传失败']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request)
    {
        if ($file = $this->uploadRepository->uploadFile($request)) {

            return response()->json(['msg' => '/storage/' . $file]);
        };

        return response()->json(['msg' => '上传失败']);
    }
}
