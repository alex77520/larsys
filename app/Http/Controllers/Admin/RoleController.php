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

    public function index()
    {
        $roles = $this->role_repository->getAllRoles($page = 5);

        return view('admin.role', compact('roles'));
    }

    public function add()
    {
        return view('admin.addRole');
    }

    public function edit($role_id)
    {
        $role = $this->role_repository->findRoleBy($role_id);

        return view('admin.editRole', compact('role'));
    }

    public function del($role_id)
    {
        if ($this->role_repository->destroyRoleBy($role_id)) {
            flash('删除角色成功！')->success();
        }

        return redirect('/admin/role');
    }

    public function doAdd(AdminRoleRequest $request)
    {
        $data = $request->all();

        if ($this->role_repository->createRole($data))
            flash('创建角色成功！')->success();

        return redirect('/admin/role');
    }

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

    public function getPermissions($role_id)
    {
        $checked_permissions = $this->role_repository->getRolePermissionsIdBy($role_id);

        $all_permissions = $this->role_repository->setCheckedPermissionData($checked_permissions);

        return response()->json($all_permissions);
    }

    public function allot(Request $request, $role_id)
    {
        $permissions_request = $request->input('permissions');

        $role = $this->role_repository->findRoleBy($role_id);

        $this->role_repository->allotPermissions($role, $permissions_request);

        flash('权限分配成功！')->success();

        return redirect('/admin/role');
    }
}
