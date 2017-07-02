<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminRoleRequest;
use App\Repositories\CacheRepository;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    protected $role_repository;
    protected $cache;

    public function __construct(RoleRepository $roleRepository, CacheRepository $cacheRepository)
    {
        $this->role_repository = $roleRepository;
        $this->cache = $cacheRepository;
    }

    /**
     * 角色列表展示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roles = $this->role_repository->getAllRoles($page = 5);

        return view('admin.role', compact('roles'));
    }

    /**
     * 展示添加角色页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        return view('admin.addRole');
    }

    /**
     * 展示编辑用户页面
     *
     * @param $role_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($role_id)
    {
        $role = $this->role_repository->findRoleBy($role_id);

        return view('admin.editRole', compact('role'));
    }

    /**
     * 删除角色
     *
     * @param $role_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function del($role_id)
    {
        if ($this->role_repository->destroyRoleBy($role_id)) {
            flash('删除角色成功！')->success();
        }

        return redirect('/admin/role');
    }

    /**
     * 执行添加角色操作
     *
     * @param AdminRoleRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doAdd(AdminRoleRequest $request)
    {
        $data = $request->all();

        if ($this->role_repository->createRole($data))
            flash('创建角色成功！')->success();

        return redirect('/admin/role');
    }

    /**
     * 执行编辑角色操作
     *
     * @param AdminRoleRequest $request
     * @param $role_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doEdit(AdminRoleRequest $request, $role_id)
    {
        $role = $this->role_repository->findRoleBy($role_id);

        $role->name = $request->input('name');

        if ($role->save())
            flash('编辑角色成功！')->success();

        // 删除权限缓存
        $this->cache->removeAllCache();

        return redirect('/admin/role');
    }

    /**
     * 分配权限弹出层
     * 获取角色所有权限
     *
     * @param $role_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissions($role_id)
    {
        $checked_permissions = $this->role_repository->getRolePermissionsIdBy($role_id);

        $all_permissions = $this->role_repository->setCheckedPermissionData($checked_permissions);

        return response()->json($all_permissions);
    }

    /**
     * 执行角色权限分配操作
     *
     * @param Request $request
     * @param $role_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function allot(Request $request, $role_id)
    {
        $permissions_request = $request->input('permissions');

        $role = $this->role_repository->findRoleBy($role_id);

        $this->role_repository->allotPermissions($role, $permissions_request);

        flash('权限分配成功！')->success();

        return redirect('/admin/role');
    }
}
