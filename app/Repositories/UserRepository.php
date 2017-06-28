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
        DB::table('admin_user_role')->where('user_id', '=', $user_id)->delete();
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
        $user_roles = Admin::find($user->id)->roles()->select('role_id')->get();
        $roles_now = [];

        foreach ($user_roles as $role) {
            $roles_now[] = $role->role_id;
        }

        $roles_now_count = count($roles_now);
        $roles_request_count = count($roles);

        if ($roles_now_count > $roles_request_count) {
            $useless_roles = [];
            foreach ($roles_now as $item) {
                if (!in_array($item, $roles)) {
                    $useless_roles[] = $item;
                }
            }
            DB::table('admin_user_role')
                ->where('user_id', '=', $user->id)
                ->whereIn('role_id', $useless_roles)
                ->delete();
        } else if ($roles_now_count < $roles_request_count) {
            foreach ($roles as $item) {
                if (! in_array($item, $roles_now)) {
                    $user->roles()->attach($item);
                }
            }
        }

        $this->cache->removeCacheBy($user->id);
    }
}