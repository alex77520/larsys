<?php

namespace App\Repositories;

use App\Admin;
use App\Role;

class UserRepository
{
    public function findUserBy($user_id)
    {
        return $user = Admin::find($user_id);
    }

    public function getAllUsersWithRoleName($page = 5)
    {
        $users = Admin::orderBy('created_at')->with(['roles' => function ($query) {
            return $query->select('name');
        }])->paginate($page);

        return $users;
    }

    public function getAllRolesIdAndName()
    {
        return $roles = Role::select('id', 'name')->where('name', '!=', '超级管理员')->get();
    }

    public function getUserRolesIdBy($user)
    {
        $user_roles_id = [];
        foreach ($user->roles as $role) {
            $user_roles_id[] = $role->role_id;
        }

        return $user_roles_id;
    }

    public function findUserWithRoleIdAndName($user_id)
    {
        return $user = Admin::with(['roles' => function($query) {
            return $query->select('role_id', 'name');
        }])->find($user_id);
    }

    public function createUser($data)
    {
        return $user = Admin::create($data);
    }

    public function allotRolesFor($user, $roles)
    {
        $user->roles()->attach($roles);
    }
}