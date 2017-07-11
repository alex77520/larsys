<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminCateRequest;
use App\Repositories\CateRepository;
use App\Repositories\ImageRepository;
use App\Http\Controllers\Controller;

class CateController extends Controller
{

    protected $cateRepository;
    protected $imageRepository;

    /**
     * CateController constructor.
     * @param CateRepository $cateRepository
     * @param ImageRepository $imageRepository
     */
    public function __construct(CateRepository $cateRepository,
                                ImageRepository $imageRepository)
    {
        $this->cateRepository = $cateRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $cates = $this->cateRepository->getAllCates();

        return view('admin.category', compact('cates'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        // 获取两个目录下全部文件
        $self_temps = getTemps(resource_path('views/home/page'));
        $content_temps = getTemps(resource_path('views/home/content'));

        $cates = $this->cateRepository->getAllCates();

        return view('admin.addCate', compact('cates', 'self_temps', 'content_temps'));
    }

    /**
     * @param AdminCateRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doAdd(AdminCateRequest $request)
    {
        $data = $request->except(['icon', 'banner', 'atlas', 'tags']);

        if ($cate = $this->cateRepository->createCate($data))
        {
            $images = $request->only(['icon', 'banner']);

            $this->imageRepository->insertImages($images, $cate->id, 'App\Cate');

            $atlas = $request->only(['atlas', 'tags']);

            if (! empty($atlas['atlas']))
            {
                $this->imageRepository->addAtlas($atlas['atlas'], $atlas['tags'], $cate->id, 'App\Cate');
            }
            flash('栏目添加成功！')->success();

        } else {

            flash('栏目添加失败！')->error();
        }

        return redirect('admin/cate');
    }

    /**
     * @param $cate_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($cate_id)
    {
        // 获取两个目录下全部文件
        $self_temps = getTemps(resource_path('views/home/page'));
        $content_temps = getTemps(resource_path('views/home/content'));

        $cates = $this->cateRepository->getAllCates();

        $my_cate = $this->cate_repository->findCateBy($cate_id);
        $atlas = $this->cate_repository->findImagesAndTags($my_cate->images, $type = 2);

        return view('admin.editCate', compact('self_temps', 'content_temps', 'cates', 'my_cate', 'atlas'));
    }

    /**
     * @param AdminCateRequest $request
     * @param $cate_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doEdit(AdminCateRequest $request, $cate_id)
    {
        $data = $request->except(['icon', 'banner', '_token', 'atlas', 'tags']);

        if ($cate = $this->cate_repository->updateCate($cate_id, $data))
        {
            $images = $request->only(['icon', 'banner']);

            $atlas = $request->only(['atlas', 'tags']);

            $this->image_repository->updateImagesByCateId($images, $cate_id, 'App\Cate', $atlas);

            flash('栏目编辑成功！')->success();

        } else {

            flash('栏目编辑失败！')->error();
        }

        return redirect('admin/cate');
    }

    /**
     * @param $cate_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function del($cate_id)
    {
        if ($this->cate_repository->delCateBy($cate_id)) flash('删除栏目成功！')->success();

        return redirect('admin/cate');
    }
}
