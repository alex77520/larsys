<?php

namespace App\Repositories;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class PermissionRepository
{

    public function initMenus()
    {
        $user = Auth::guard('admin')->user();

        // 缓存中保存菜单和uris的键名
        $menu_name = 'mysys_admin_menus_' . $user->id;
        $uri_name = 'mysys_admin_uris_' . $user->id;

        // if (！ Redis::exists($menu_name)) 不需要每次登录都刷新就引入改行,测试阶段注释
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

        Redis::set($menu_name, serialize($admin_menus));
        Redis::set($uri_name, serialize($admin_uris));
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
}