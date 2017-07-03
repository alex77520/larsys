<?php

namespace App\Http\Controllers\Admin;

use App\AdminLog;
use App\Http\Controllers\Controller;
use App\Repositories\PermissionRepository;

class IndexController extends Controller
{
    protected $permission;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permission = $permissionRepository;
    }

    public function index()
    {
        return view('admin.index');
    }

    /**
     * 日志页面展示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function log()
    {
        $logs = AdminLog::orderBy('created_at', 'desc')->paginate(10);

        return view('admin/log', compact('logs'));
    }
}
