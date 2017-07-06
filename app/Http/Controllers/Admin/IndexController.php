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

        /*$page = view('admin/log', compact('logs'))->render();

        Storage::disk('public')->put('log/1.html', $page);

        return redirect('/storage/log/1.html');*/

        return view('admin/log', compact('logs'));
    }
}
