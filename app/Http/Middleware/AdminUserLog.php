<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use App\AdminLog;

class AdminUserLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data['username'] = Auth::guard('admin')->user()->name;
        $data['uri'] = pregReplaceUri($_SERVER['REQUEST_URI']);
        $data['ip'] = getClientIP();

        $log = new AdminLog();
        $data['name'] = $log->getNameByUri($data['uri']);

        if (! $log->create($data)) return '权限写入失败！';

        return $next($request);
    }
}
