<?php

namespace App\Http\Middleware;

use App\Repositories\PermissionRepository;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class AuthPermission
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
        $this->permission->initMenus();

        // 加入防止伪造url登录的验证规则
        $user_id = Auth::guard('admin')->user()->id;

        $uri = preg_replace('/(((\?)(\w|=)+)|(\/\d+))/', '', $_SERVER['REQUEST_URI']);

        if (! in_array($uri, unserialize(Redis::get(env('ADMIN_URIS_PREFIX') . $user_id))))
            return abort('401');

        return $next($request);
    }
}
