<?php

namespace App\Repositories;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class PermissionRepository
{

    public static $permissionUris = [];

    public function initMenus()
    {
        $user = Auth::guard('admin')->user();

        $menu_name = 'admin_menus_' . $user->id;

        if (! Redis::exists($menu_name)) {
            $this->cacheAllMenusOrPartMenus($user, $menu_name);
        }
    }

    public function cacheAllMenusOrPartMenus($user, $menu_name)
    {
        $admin_menus = $user->is_admin === 1
            ? $this->getAllMenus()
            : $this->getPermissionMenus($user);

        // buildTree->App/Helpers/helpers.php
        $admin_menus = buildTree($admin_menus);

        Redis::set($menu_name, serialize($admin_menus));
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

    /**
     * 获取有权限的栏目
     *
     * @param $user
     * @return array
     */
    public function getPermissionMenus($user)
    {
        $user_roles = User::find($user->id)->roles()->get();

        $user_permissions = [];
        foreach ($user_roles as $role) {
            $role_permissions = Role::find($role->id)->permissions()->where('is_menu', 1)->get()->toArray();

            $user_permissions = $user_permissions + $role_permissions;
        }

        return $user_permissions;
    }

    public function setPermissionUris()
    {

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
}