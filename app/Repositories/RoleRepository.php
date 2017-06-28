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
        DB::table('admin_user_role')->where('role_id', '=', $role_id)->delete();
    }

    /**
     * 删除关联表中role和permission之间的联系
     *
     * @param $role_id
     */
    public function delRolePermissionRelationsBy($role_id)
    {
        DB::table('admin_role_permission')->where('role_id', '=', $role_id)->delete();
    }

    /**
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

    public function createRole(array $data)
    {
        return Role::create($data);
    }

    public function getAllRoles($page)
    {
        return $roles = Role::orderBy('created_at')->paginate($page);
    }

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

    public function allotPermissions($permissions_now, $permissions_request, $role)
    {
        $count_permissions_now = count($permissions_now);
        $count_permissions_request = count($permissions_request);

        if ($count_permissions_request > $count_permissions_now) {
            foreach ($permissions_request as $item) {
                if (! in_array($item, $permissions_now)) {
                    $role->permissions()->attach($item);
                }
            }
        } else if ($count_permissions_request < $count_permissions_now) {
            $useless_permissions = [];
            foreach ($permissions_now as $item) {
                if (!in_array($item, $permissions_request)) {
                    $useless_permissions[] = $item;
                }
            }
            DB::table('admin_role_permission')
                ->where('role_id', '=', $role->id)
                ->whereIn('permission_id', $useless_permissions)
                ->delete();
        }

        // 删除全部缓存
        $this->cache->removeAllCache();
    }

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