<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use App\Repositories\RoleRepository;
use App\Role;
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

        return redirect('/admin/role');
    }
}
