<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Extensions\AuthenticatesLogout;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // 这里使用自定义的trait实现注销，避免将前后台用户的会话全部删除
    use AuthenticatesUsers, AuthenticatesLogout {
        AuthenticatesLogout::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware( 'guest.admin', [ 'except' => 'logout' ] );
    }

    /**
     * 显示后台登录模板
     */
    public function showLoginForm()
    {
        return view( 'admin.login' );
    }

    /**
     * 使用 admin guard
     */
    protected function guard()
    {
        return auth()->guard( 'admin' );
    }

    /**
     * 重写验证时使用的用户名字段
     */
    public function username()
    {
        return 'name';
    }

    /**
     * 验证该用户是否被锁
     *
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated( $user )
    {
        return $user->status === 1 ? true : false;
    }

    /**
     * 重写登录返回信息
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse( Request $request )
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts( $request );

        if ( $this->authenticated( $this->guard()->user() ) === false ) {
            // 若用户被锁，则将该用户的会话信息删除，阻止用户登录
            $request->session()->forget( $this->guard()->getName() );

            flash( '您的账号已被冻结' )->error();

            return view( 'admin.login' );
        }

        return redirect()->intended( $this->redirectPath() );
    }
}
