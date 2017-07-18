<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ImageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{

    /**
     * @var ImageRepository
     */
    protected $imageRepository;

    /**
     * ImageController constructor.
     * @param ImageRepository $imageRepository
     */
    public function __construct( ImageRepository $imageRepository )
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * @param null $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( $type = null )
    {
        $images = $this->imageRepository->getImagesByType( $page = 5, $type );

        return view( 'admin.image', compact( 'images' ) );
    }

    /**
     * @param Request $request
     * @param $model_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit( Request $request, $model_id )
    {
        $model = strtolower( $request->get( 'model' ) );

        return redirect( '/admin/' . $model . '/' . $model_id . '/edit' );
    }

    /**
     * @param $image_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function del( $image_id )
    {
        if ( $this->imageRepository->delImageBy( $image_id ) ) {
            flash( '删除图片成功！' )->success();
        }

        return redirect()->back();
    }
}
