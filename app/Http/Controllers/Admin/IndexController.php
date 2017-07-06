<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AdminLogRepository;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    protected $permission;
    protected $log;

    public function __construct(PermissionRepository $permissionRepository,
                                AdminLogRepository $adminLogRepository)
    {
        $this->permission = $permissionRepository;
        $this->log = $adminLogRepository;
    }

    public function index()
    {
        // 删除过期的日志
        $this->log->delOverdueLog();

        return view('admin.index');
    }

    /**
     * 日志页面展示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function log()
    {
        $logs = $this->log->getAllLogs(10);

        return view('admin/log', compact('logs'));
    }
}
