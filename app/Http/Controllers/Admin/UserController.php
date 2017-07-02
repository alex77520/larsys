<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminUserRequest;
use App\Repositories\CacheRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $user_repository;
    protected $cache;
    protected $role_repository;

    public function __construct(UserRepository $userRepository,
                                CacheRepository $cacheRepository,
                                RoleRepository $roleRepository)
    {
        $this->user_repository = $userRepository;
        $this->cache = $cacheRepository;
        $this->role_repository = $roleRepository;
    }

    /**
     * 展示用户列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = $this->user_repository->getAllUsersWithRoleName($page = 5);

        return view('admin.user', compact('users'));
    }

    /**
     * 展示添加用户页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        $roles = $this->role_repository->getAllRolesIdAndName();

        return view('admin.addUser', compact('roles'));
    }

    /**
     * 执行添加用户操作
     *
     * @param AdminUserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doAdd(AdminUserRequest $request)
    {
        $data = $request->except('roles');
        $data['password'] = bcrypt($request->input('password'));

        $user = $this->user_repository->createUser($data);

        if ((!$request->exists('is_admin')) && ($request->exists('roles'))) {

            $roles = $request->input('roles');

            $this->user_repository->allotRolesFor($user, $roles);
        }

        return redirect('/admin/user');
    }

    /**
     * 展示编辑用户页面
     *
     * @param $user_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($user_id)
    {
        $user = $this->user_repository->findUserWithRoleIdAndName($user_id);

        $roles = $this->user_repository->getAllRolesIdAndName();

        $user_roles_id = $this->user_repository->getUserRolesIdBy($user);

        return view('admin.editUser', compact('user', 'roles', 'user_roles_id'));
    }

    /**
     * 执行用户编辑
     *
     * @param AdminUserRequest $request
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doEdit(AdminUserRequest $request, $user_id)
    {
        $user = $this->user_repository->findUserBy($user_id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->is_admin = $request->exists('is_admin') ? $request->input('is_admin') : 0;
        $user->save();

        if ((! $request->exists('is_admin')) && ($request->exists('roles'))) {

            $roles = $request->input('roles');

            $this->user_repository->allotRolesFor($user, $roles);
        }

        return redirect('/admin/user');
    }

    /**
     * 删除用户
     *
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function del($user_id)
    {
        $user = $this->user_repository->findUserBy($user_id);

        if ($user->delete()) {
            flash('删除用户成功！')->success();

            $this->user_repository->delUserRoleRelationsBy($user_id);

            // 仅删除该用户对应的缓存
            $this->cache->removeCacheBy($user_id);
        }

        return redirect('/admin/user');
    }

    /**
     * 冻结用户
     *
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function frozen($user_id)
    {
        $user = $this->user_repository->findUserBy($user_id);
        $user->status = 0;

        if ($user->save()) return redirect('/admin/user');
    }

    /**
     * 解冻用户
     *
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function unfrozen($user_id)
    {
        $user = $this->user_repository->findUserBy($user_id);
        $user->status = 1;

        if ($user->save()) return redirect('/admin/user');
    }
}
