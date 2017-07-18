<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\AdminLogRepository;

class AdminUserLog
{

    protected $log;

    public function __construct( AdminLogRepository $adminLogRepository )
    {
        $this->log = $adminLogRepository;
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

        if ( ! $this->log->putLogInDatabase() ) {
            return '权限写入失败！';
        }

        return $next( $request );
    }
}
