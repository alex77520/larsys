<?php

namespace App\Repositories;

use App\Role;

class RoleRepository
{
    public function findRoleBy($role_id)
    {
        return Role::find($role_id);
    }

    public function destroyRoleBy($role_id)
    {
        return Role::destroy($role_id);
    }

    public function createRole(array $data)
    {
        return Role::create($data);
    }

    public function getAllRoles($page)
    {
        return $roles = Role::orderBy('created_at')->paginate($page);
    }

    public function getRoleWithPermissions($role_id)
    {
        return $role = Role::with(['permissions' => function($query) {
            return $query->select('permission_id');
        }])->find($role_id);
    }
}