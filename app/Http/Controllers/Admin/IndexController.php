<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AdminLogRepository;

class IndexController extends Controller
{

    /**
     * @var AdminLogRepository
     */
    protected $adminLogRepository;

    /**
     * IndexController constructor.
     * @param AdminLogRepository $adminLogRepository
     */
    public function __construct( AdminLogRepository $adminLogRepository )
    {
        $this->adminLogRepository = $adminLogRepository;
    }

    public function index()
    {
        // 删除过期的日志
        $this->adminLogRepository->delOverdueLog();

        return view( 'admin.index' );
    }

    /**
     * 日志页面展示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function log()
    {
        $logs = $this->adminLogRepository->getAllLogs( 10 );

        return view( 'admin/log', compact( 'logs' ) );
    }
}
