<?php

namespace App\Repositories;

use App\Admin;
use Illuminate\Support\Facades\DB;

class UserRepository
{

    protected $cacheRepository;

    public function __construct(CacheRepository $cacheRepository)
    {
        $this->cacheRepository = $cacheRepository;
    }

    /**
     * 删除用户和角色的关联数据
     *
     * @param $user_id
     * @return mixed
     */
    public function delUserRoleRelationsBy($user_id)
    {
        return DB::table('admin_user_role')
            ->where('user_id', '=', $user_id)
            ->delete();
    }

    /**
     * 通过用户ID获取用户信息
     *
     * @param $user_id
     * @return mixed
     */
    public function findUserBy($user_id)
    {
        return $user = Admin::find($user_id);
    }

    /**
     * 获取所有用户并携带用户的角色名称（分页）
     *
     * @param int $page
     * @return mixed
     */
    public function getAllUsersWithRoleName($page = 5)
    {
        $users = Admin::orderBy('created_at')->with(['roles' => function ($query) {
            return $query->select('name');
        }])->paginate($page);

        return $users;
    }

    /**
     * 通过用户实例获取对应用户的角色ID
     *
     * @param $user
     * @return array
     */
    public function getUserRolesIdBy($user)
    {
        $user_roles_id = [];
        foreach ($user->roles as $role) {
            $user_roles_id[] = $role->role_id;
        }

        return $user_roles_id;
    }

    /**
     * 通过用户ID获取用户并携带用户对应的角色ID和名称
     *
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function findUserWithRoleIdAndName($user_id)
    {
        return $user = Admin::with(['roles' => function ($query) {
            return $query->select('role_id', 'name');
        }])->find($user_id);
    }

    /**
     * 创建新的用户
     *
     * @param $data
     * @return mixed
     */
    public function createUser($data)
    {
        return $user = Admin::create($data);
    }

    /**
     * 分配角色
     *
     * @param $user
     * @param $roles
     */
    public function allotRolesFor($user, $roles)
    {
        $this->delUserRoleRelationsBy($user->id);

        $user->roles()->attach($roles);

        $this->cacheRepository->removeCacheBy($user->id);
    }
}