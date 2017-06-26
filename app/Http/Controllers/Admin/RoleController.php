<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminRoleRequest;
use App\Permission;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    protected $role_repository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->role_repository = $roleRepository;
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
            return redirect('/admin/role');
        }
    }

    public function doAdd(AdminRoleRequest $request)
    {
        $data = $request->all();

        if ($this->role_repository->createRole($data)) {
            flash('创建角色成功！')->success();
            return redirect('/admin/role');
        }
    }

    public function doEdit(AdminRoleRequest $request, $role_id)
    {
        $role = $this->role_repository->findRoleBy($role_id);

        $role->name = $request->input('name');

        if ($role->save())
            flash('编辑成功！')->success();
            return redirect('/admin/role');
    }

    public function getPermissions($role_id)
    {
        $role = $this->role_repository->getRoleWithPermissions($role_id);

        $check_permissions = [];
        foreach ($role->permissions as $permission) {
            $check_permissions[] = $permission->permission_id;
        }

        $all_permissions = Permission::select('id', 'name', 'pid')->get();

        foreach ($all_permissions as $key => $permission) {
            $all_permissions[$key]['pId'] = $permission['pid'];
            unset($all_permissions[$key]['pid']);
            if (in_array($permission['id'], $check_permissions)) $all_permissions[$key]['checked'] = true;
        }

        return response()->json($all_permissions);
    }

    public function allot(Request $request, $role_id)
    {
        $role = $this->role_repository->getRoleWithPermissions($role_id);

        $permissions_id = [];
        foreach ($role->permissions as $permission) {
            $permissions_id[] = $permission->permission_id;
        }

        foreach ($request->input('permissions') as $item) {
            if (! in_array($item, $permissions_id)) {
                $role->permissions()->attach($item);
            }
        }

        flash('权限分配成功！')->success();
        return redirect('/admin/role');
    }
}
