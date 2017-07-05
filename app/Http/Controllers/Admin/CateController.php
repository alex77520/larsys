<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\CateRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CateController extends Controller
{
    protected $cate_repository;

    public function __construct(CateRepository $cateRepository)
    {
        $this->cate_repository = $cateRepository;
    }

    public function index()
    {
        $cates = $this->cate_repository->getAllCates();

        return view('admin.category', compact('cates'));
    }

    public function add()
    {
        $cates = $this->cate_repository->getAllCates();

        return view('admin.addCate', compact('cates'));
    }
}
