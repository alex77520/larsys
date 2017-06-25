<?php

namespace App\Http\Middleware;

use App\Repositories\PermissionRepository;
use Closure;
use Illuminate\Support\Facades\Redis;

class AuthAdmin
{
    protected $permission;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permission = $permissionRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guard('admin')->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/login');
            }
        }

        // 加入防止伪造url登录的验证规则
        // going on...
        $this->permission->initMenus();

        $uri = $_SERVER['REQUEST_URI'];
        if (! in_array($uri, unserialize(Redis::get('admin_uris_2')))) return response('Unauthorized.', 401);

        return $next($request);
    }
}
