<?php

namespace App\Repositories;

use App\Permission;
use App\Role;
use Illuminate\Support\Facades\DB;

class RoleRepository
{
    protected $cache;

    public function __construct(CacheRepository $cacheRepository)
    {
        $this->cache = $cacheRepository;
    }

    /**
     * 删除关联表中role和user之间的联系
     *
     * @param $role_id
     */
    public function delUserRoleRelationsBy($role_id)
    {
        return DB::table('admin_user_role')->where('role_id', '=', $role_id)->delete();
    }

    /**
     * 删除关联表中role和permission之间的联系
     *
     * @param $role_id
     */
    public function delRolePermissionRelationsBy($role_id)
    {
        return DB::table('admin_role_permission')->where('role_id', '=', $role_id)->delete();
    }

    /**
     * 通过ID得到角色实例
     *
     * @param $role_id
     * @return mixed
     */
    public function findRoleBy($role_id)
    {
        return Role::find($role_id);
    }

    /**
     * 删除角色（涉及到多个删除过程）
     *
     * @param $role_id
     * @return int
     */
    public function destroyRoleBy($role_id)
    {
        // 删除全部缓存，使其重新生成
        $this->cache->removeAllCache();

        // 将关联表中和该角色相关的关联记录删除
        $this->delUserRoleRelationsBy($role_id);
        $this->delRolePermissionRelationsBy($role_id);

        return Role::destroy($role_id);
    }

    /**
     * 创建新的角色
     *
     * @param array $data
     * @return mixed
     */
    public function createRole(array $data)
    {
        return Role::create($data);
    }

    /**
     * 获取所有角色（分页）
     *
     * @param $page
     * @return mixed
     */
    public function getAllRoles($page)
    {
        return $roles = Role::orderBy('created_at')->paginate($page);
    }

    /**
     * 通过角色ID获取所有权限
     *
     * @param $role_id
     * @return array
     */
    public function getRolePermissionsIdBy($role_id)
    {
        $role = Role::with(['permissions' => function($query) {
            return $query->select('permission_id');
        }])->find($role_id);

        $permissions_id = [];
        foreach ($role->permissions as $permission) {
            $permissions_id[] = $permission->permission_id;
        }

        return $permissions_id;
    }

    /**
     * 分配权限
     *
     * @param $role
     * @param $permissions_request
     */
    public function allotPermissions($role, $permissions_request)
    {
        $this->delRolePermissionRelationsBy($role->id);

        $this->cache->removeAllCache();

        $role->permissions()->attach($permissions_request);
    }

    /**
     * 角色分配权限时获取所有已有权限
     *
     * @param $checked_permissions
     * @return mixed
     */
    public function setCheckedPermissionData($checked_permissions)
    {
        $all_permissions = Permission::select('id', 'name', 'pid')->get();

        foreach ($all_permissions as $key => $permission) {
            $all_permissions[$key]['pId'] = $permission['pid'];
            unset($all_permissions[$key]['pid']);
            if (in_array($permission['id'], $checked_permissions)) $all_permissions[$key]['checked'] = true;
        }

        return $all_permissions;
    }
}