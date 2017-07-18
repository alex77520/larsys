<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminUserRequest;
use App\Repositories\CacheRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var CacheRepository
     */
    protected $cacheRepository;
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     * @param CacheRepository $cacheRepository
     * @param RoleRepository $roleRepository
     */
    public function __construct( UserRepository $userRepository,
                                 CacheRepository $cacheRepository,
                                 RoleRepository $roleRepository )
    {
        $this->userRepository = $userRepository;
        $this->cacheRepository = $cacheRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * 展示用户列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = $this->userRepository->getAllUsersWithRoleName( $page = 5 );

        return view( 'admin.user', compact( 'users' ) );
    }

    /**
     * 展示添加用户页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        $roles = $this->roleRepository->getAllRolesIdAndName();

        return view( 'admin.addUser', compact( 'roles' ) );
    }

    /**
     * 执行添加用户操作
     *
     * @param AdminUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doAdd( AdminUserRequest $request )
    {
        $data = $request->except( 'roles' );
        $data['password'] = bcrypt( $request->input( 'password' ) );

        $user = $this->userRepository->createUser( $data );

        if ( ( ! $request->exists( 'is_admin' ) ) && ( $request->exists( 'roles' ) ) ) {
            $roles = $request->input( 'roles' );

            $this->userRepository->allotRolesFor( $user, $roles );
        }

        return redirect( '/admin/user' );
    }

    /**
     * 展示编辑用户页面
     *
     * @param $user_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit( $user_id )
    {
        $user = $this->userRepository->findUserWithRoleIdAndName( $user_id );

        $roles = $this->userRepository->getAllRolesIdAndName();

        $user_roles_id = $this->userRepository->getUserRolesIdBy( $user );

        return view( 'admin.editUser', compact( 'user', 'roles', 'user_roles_id' ) );
    }

    /**
     * 执行用户编辑
     *
     * @param AdminUserRequest $request
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doEdit( AdminUserRequest $request, $user_id )
    {
        $user = $this->userRepository->findUserBy( $user_id );

        $user->name = $request->input( 'name' );
        $user->email = $request->input( 'email' );
        $user->password = bcrypt( $request->input( 'password' ) );
        $user->is_admin = $request->exists( 'is_admin' ) ? $request->input( 'is_admin' ) : 0;
        $user->save();

        if ( ( ! $request->exists( 'is_admin' ) ) && ( $request->exists( 'roles' ) ) ) {
            $roles = $request->input( 'roles' );

            $this->userRepository->allotRolesFor( $user, $roles );
        }

        return redirect( '/admin/user' );
    }

    /**
     * 删除用户
     *
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function del( $user_id )
    {
        $user = $this->userRepository->findUserBy( $user_id );

        if ( $user->delete() ) {
            flash( '删除用户成功！' )->success();

            $this->userRepository->delUserRoleRelationsBy( $user_id );

            // 仅删除该用户对应的缓存
            $this->cacheRepository->removeCacheBy( $user_id );
        }

        return redirect( '/admin/user' );
    }

    /**
     * 冻结用户
     *
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function frozen( $user_id )
    {
        $user = $this->userRepository->findUserBy( $user_id );
        $user->status = 0;

        if ( $user->save() ) {
            return redirect( '/admin/user' );
        }
    }

    /**
     * 解冻用户
     *
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function unfrozen( $user_id )
    {
        $user = $this->userRepository->findUserBy( $user_id );
        $user->status = 1;

        if ( $user->save() ) {
            return redirect( '/admin/user' );
        }
    }
}
