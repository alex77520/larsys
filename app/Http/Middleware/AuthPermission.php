<?php

namespace App\Http\Middleware;

use App\Repositories\PermissionRepository;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CacheRepository;
use Illuminate\Support\Facades\Cache;

class AuthPermission
{

    protected $permission;
    protected $cache;

    public function __construct( PermissionRepository $permissionRepository, CacheRepository $cacheRepository )
    {
        $this->permission = $permissionRepository;
        $this->cache = $cacheRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        $this->permission->initMenus();

        // 加入防止伪造url登录的验证规则
        $user_id = Auth::guard( 'admin' )->user()->id;

        $uri = pregReplaceUri( $_SERVER['REQUEST_URI'] );

        if ( env('REDIS_OPEN') === 'on') {
            if ( ! in_array( $uri, unserialize( $this->cache->hashGet( env( 'REDIS_ADMIN_HASH_KEY' ), 'uris_' . $user_id ) ) ) ) {
                return abort( '401' );
            }
        } else {
            if ( ! in_array( $uri, unserialize( Cache::store('file')->get(env( 'REDIS_ADMIN_HASH_KEY' ) . '_uris_' . $user_id) ) ) ) {
                return abort( '401' );
            }
        }

        return $next( $request );
    }
}
