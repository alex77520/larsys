<?php

namespace App\Repositories;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionRepository
{
    protected $cache;

    public function __construct(CacheRepository $cacheRepository)
    {
        $this->cache = $cacheRepository;
    }

    public function findPermission($permission_id)
    {
        return Permission::where('status', 1)->find($permission_id);
    }

    public function createPermission($data)
    {
        $this->cache->removeAllCache();

        return Permission::create($data);
    }

    public function destroyPermissionBy($permission_id)
    {
        $this->delRolePermissionRelationsBy($permission_id);

        $this->cache->removeAllCache();

        return Permission::destroy($permission_id);
    }

    public function initMenus()
    {
        $user = Auth::guard('admin')->user();

        // 缓存中保存菜单和uris的键名
        $menu_name = 'menus_' . $user->id;
        $uri_name = 'uris_' . $user->id;

        if (! $this->cache->hashFieldExist(env('REDIS_ADMIN_HASH_KEY'), $menu_name))
        $this->cacheAllMenusOrPartMenus($user, $menu_name, $uri_name);
    }

    public function cacheAllMenusOrPartMenus($user, $menu_name, $uri_name)
    {
        if ($user->is_admin === 1) {
            $admin_menus = $this->getAllMenus();
            $admin_uris = $this->getAllUris();
        } else {
            $admin_menus = $this->getPermissionMenus($user);
            $admin_uris = $this->getPermissionUris($user);
        }

        // buildTree->App/Helpers/helpers.php
        $admin_menus = buildTree($admin_menus);

        $this->cache->hashSet(env('REDIS_ADMIN_HASH_KEY'), $menu_name, serialize($admin_menus));
        $this->cache->hashSet(env('REDIS_ADMIN_HASH_KEY'), $uri_name, serialize($admin_uris));
    }

    /**
     * 直接拿到所有menu
     *
     * @return mixed
     */
    public function getAllMenus()
    {
        $admin_menus = Permission::where('is_menu', 1)
            ->select('id', 'pid', 'name', 'uri')
            ->get()
            ->toArray();

        return $admin_menus;
    }

    public function getAllUris()
    {
        $admin_permissions = Permission::where('uri', '!=', '')
            ->select('uri')
            ->get()
            ->toArray();

        // 确保与getPermissionUris方法返回的数据格式一致
        $fresh_permissions = [];
        foreach($admin_permissions as $permission) {
            $fresh_permissions[] = $permission['uri'];
        }

        return $fresh_permissions;
    }

    /**
     * 删除关联表中role和permission之间的联系
     *
     * @param $permission_id
     */
    public function delRolePermissionRelationsBy($permission_id)
    {
        DB::table('admin_role_permission')->where('permission_id', '=', $permission_id)->delete();
    }

    /**
     * 获取有权限的栏目
     *
     * ###
     *
     * @param $user
     * @return array
     */
    public function getPermissionMenus($user)
    {
        $user_roles = $this->findRolesBy($user->id);

        $user_permissions = [];
        foreach ($user_roles as $role) {
            $role_permissions = $this->findPermissionsBy($role['id']);
            foreach ($role_permissions as $permission) {
                // （关键）把pivot关联数组信息去除，
                // 原因是该项的存在使得数组的每个键值都变得唯一而达不到去重目的
                unset($permission['pivot']);
                if (in_array($permission, $user_permissions)) continue;
                array_push($user_permissions, $permission);
            }
        }

        return  $user_permissions;
    }

    public function getPermissionUris($user)
    {
        $roles = $this->findRolesBy($user->id);

        $permission_uris = [];
        foreach ($roles as $role) {
            $permissions = $this->findPermissionsBy($role['id'], false);
            foreach ($permissions as $permission) {
                if (in_array($permission['uri'], $permission_uris) || $permission['uri'] == '') continue;
                $permission_uris[] = $permission['uri'];
            }
        }

        return $permission_uris;
    }

    /**
     * 面包屑导航
     *
     * @param int $i
     * @param array $bread
     * @return array
     */
    public function breadCrumbs($i = 0, &$bread = [])
    {
        $res = Permission::where('is_menu', 1)->find($i);

        if ($res) {
            $bread[] = $res;
            $this->breadCrumbs($res->pid, $bread);
        }

        return $bread;
    }

    public function findRolesBy($user_id)
    {
        return $user_roles = User::find($user_id)->roles()->get()->toArray();
    }

    public function findPermissionsBy($role_id, $is_menu = true)
    {
        if ($is_menu === true) {
            $role_permissions = Role::find($role_id)->permissions()->where('is_menu', 1)->get()->toArray();
        } else {
            $role_permissions = Role::find($role_id)->permissions()->get()->toArray();
        }

        return $role_permissions;
    }

    public function getAllPermissions($page = 5, $returnArray = false)
    {
        if ($page === 0) {
            if ($returnArray === false) {
                $permissions = Permission::where('status', 1)->orderBy('created_at')->get();
            } else {
                $permissions = Permission::where('status', 1)->orderBy('created_at')->get()->toArray();
            }
        }

        if ($returnArray === false) {
            $permissions = Permission::where('status', 1)->orderBy('created_at')->paginate($page);
        }

        return $permissions;
    }

    public function buildOptionStr($permissions, $selected_id = '', $separation = '', $repeat_num = 1)
    {
        $options = '';
        $repeat_num = $repeat_num * 2;

        foreach ($permissions as $permission) {

            $options .= '<option value="'. $permission['id'] .'"';
            if ($permission['id'] == $selected_id) $options .= 'selected';

            $options .= '>'. str_repeat($separation, $repeat_num) . $permission['name'] .'</option>';

            if ($permission['sub_menu'] != '') {

                $options .= $this->buildOptionStr($permission['sub_menu'], '', '—', $repeat_num);
            }
        }

        return $options;
    }
}