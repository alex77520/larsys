<?php

namespace App\Repositories;

use App\Admin;
use App\Role;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    protected $cache;

    public function __construct(CacheRepository $cacheRepository)
    {
        $this->cache = $cacheRepository;
    }

    public function delUserRoleRelationsBy($user_id)
    {
        return DB::table('admin_user_role')->where('user_id', '=', $user_id)->delete();
    }

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
        $this->delUserRoleRelationsBy($user->id);

        $user->roles()->attach($roles);

        $this->cache->removeCacheBy($user->id);
    }
}