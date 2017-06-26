<?php

namespace App\Repositories;

use App\Role;

class RoleRepository
{
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