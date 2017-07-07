<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminCateRequest;
use App\Repositories\CateRepository;
use App\Repositories\ImageRepository;
use App\Http\Controllers\Controller;

class CateController extends Controller
{
    protected $cate_repository;
    protected $image_repository;

    public function __construct(CateRepository $cateRepository,
                                ImageRepository $imageRepository)
    {
        $this->cate_repository = $cateRepository;
        $this->image_repository = $imageRepository;
    }

    public function index()
    {
        $cates = $this->cate_repository->getAllCates();

        return view('admin.category', compact('cates'));
    }

    public function add()
    {
        // 获取两个目录下全部文件
        $self_temps = getTemps(resource_path('views/home/page'));
        $content_temps = getTemps(resource_path('views/home/content'));

        $cates = $this->cate_repository->getAllCates();

        return view('admin.addCate', compact('cates', 'self_temps', 'content_temps'));
    }

    public function doAdd(AdminCateRequest $request)
    {
        $data = $request->except(['icon', 'banner']);

        if($cate = $this->cate_repository->createCate($data)) {

            $images = $request->only(['icon', 'banner']);

            $this->image_repository->insertImages($images, $cate->id, 'App\Cate');

            flash('栏目添加成功！')->success();

        } else {
            flash('栏目添加失败！')->error();
        }

        return redirect('admin/cate');
    }

    public function edit($cate_id)
    {
        // 获取两个目录下全部文件
        $self_temps = getTemps(resource_path('views/home/page'));
        $content_temps = getTemps(resource_path('views/home/content'));

        $cates = $this->cate_repository->getAllCates();

        $my_cate = $this->cate_repository->findCateBy($cate_id);

        return view('admin.editCate', compact('self_temps', 'content_temps', 'cates', 'my_cate'));
    }

    public function doEdit(AdminCateRequest $request, $cate_id)
    {
        $data = $request->except(['icon', 'banner', '_token']);

        if($cate = $this->cate_repository->updateCate($cate_id, $data)) {

            $images = $request->only(['icon', 'banner']);

            $this->image_repository->updateImagesByCateId($images, $cate_id, 'App\Cate');

            flash('栏目编辑成功！')->success();

        } else {
            flash('栏目编辑失败！')->error();
        }

        return redirect('admin/cate');
    }

    public function del($cate_id)
    {
        if($this->cate_repository->delCateBy($cate_id)) flash('删除栏目成功！')->success();

        return redirect('admin/cate');
    }
}
