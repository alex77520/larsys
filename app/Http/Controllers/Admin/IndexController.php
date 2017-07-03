<?php

namespace App\Http\Controllers\Admin;

use App\AdminLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function log()
    {
        $logs = AdminLog::orderBy('created_at', 'desc')->paginate(10);

        return view('admin/log', compact('logs'));
    }
}
