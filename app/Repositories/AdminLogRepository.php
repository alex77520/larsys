<?php

namespace App\Repositories;

use App\AdminLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminLogRepository
{
    /**
     * 写入日志到数据库
     *
     * @return mixed
     */
    public function putLogInDatabase()
    {
        $data['username'] = Auth::guard('admin')->user()->name;
        $data['uri'] = pregReplaceUri($_SERVER['REQUEST_URI']);
        $data['ip'] = getClientIP();
        $data['expired_at'] = Carbon::parse('+5 day')->toDateTimeString();

        $log = new AdminLog();
        $data['name'] = $log->getNameByUri($data['uri']);

        return $log->create($data);
    }

    /**
     * 删除过期的日志数据
     */
    public function delOverdueLog()
    {
        $now = Carbon::now()->toDateTimeString();

        AdminLog::where('expired_at', '<', $now)->delete();
    }

    public function getAllLogs($page)
    {
        return AdminLog::orderBy('created_at', 'desc')->paginate($page);
    }
}