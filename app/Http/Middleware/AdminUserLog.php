<?php

namespace App\Http\Middleware;

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
        $data['uri'] = pregReplaceUri($_SERVER['REQUEST_URI']);
        $data['ip'] = getClientIP();

        $log = new AdminLog();
        $data['name'] = $log->getNameByUri($data['uri']);

        if (! $log->create($data)) return '权限写入失败！';

        return $next($request);
    }
}
