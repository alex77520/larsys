<?php

namespace App\Repositories;

use App\Admin;
use App\Permission;
use App\Role;
use Auth;
use DB;
use Illuminate\Support\Facades\Cache;

class PermissionRepository
{

    /**
     * @var CacheRepository
     */
    protected $cacheRepository;

    /**
     * PermissionRepository constructor.
     * @param CacheRepository $cacheRepository
     */
    public function __construct( CacheRepository $cacheRepository )
    {
        $this->cacheRepository = $cacheRepository;
    }

    /**
     * 初始化菜单
     */
    public function initMenus()
    {
        $user = Auth::guard( 'admin' )->user();

        // 缓存中保存菜单和uris的键名
        $menu_name = 'menus_' . $user->id;
        $uri_name = 'uris_' . $user->id;

        if ( env('REDIS_OPEN') === 'on') {
            if ( ! $this->cacheRepository->hashFieldExist( env( 'REDIS_ADMIN_HASH_KEY' ), $menu_name ) ) {
                $this->cacheAllMenusOrPartMenus( $user, $menu_name, $uri_name );
            }
        } else {

            $datas = $this->getAllMenusOrPartMenus( $user );
            $admin_menus = $datas['menus'];
            $admin_uris = $datas['uris'];

            Cache::store('file')->forever( env( 'REDIS_ADMIN_HASH_KEY' ) . '_' . $menu_name, serialize($admin_menus));
            Cache::store('file')->forever( env( 'REDIS_ADMIN_HASH_KEY' ) . '_' . $uri_name, serialize($admin_uris));
        }

    }

    /**
     * 通过ID找到单个权限实例
     *
     * @param $permission_id
     * @return mixed
     */
    public function findPermission( $permission_id )
    {
        return Permission::where( 'status', 1 )->find( $permission_id );
    }

    /**
     * 新建权限
     *
     * @param $data
     * @return mixed
     */
    public function createPermission( $data )
    {
        $this->cacheRepository->removeAllCache();

        return Permission::create( $data );
    }

    /**
     * 通过ID删除权限
     *
     * @param $permission_id
     * @return int
     */
    public function destroyPermissionBy( $permission_id )
    {
        $this->delRolePermissionRelationsBy( $permission_id );

        $this->cacheRepository->removeAllCache();

        return Permission::destroy( $permission_id );
    }

    /**
     * 将菜单写入缓存
     * 通过is_admin字段判断用户的等级:
     * 0->普通管理员 需要进一步判断权限目录
     * 1->超级管理员 直接获取所有权限
     *
     * @param $user
     * @param $menu_name
     * @param $uri_name
     */
    public function cacheAllMenusOrPartMenus( $user, $menu_name, $uri_name )
    {
        $datas = $this->getAllMenusOrPartMenus( $user );
        $admin_menus = $datas['menus'];
        $admin_uris = $datas['uris'];

        $this->cacheRepository->hashSet( env( 'REDIS_ADMIN_HASH_KEY' ), $menu_name, serialize( $admin_menus ) );
        $this->cacheRepository->hashSet( env( 'REDIS_ADMIN_HASH_KEY' ), $uri_name, serialize( $admin_uris ) );
    }

    /**
     * 获取所有菜单以及uri或者部分菜单以及uri
     *
     * @param $user
     * @return array
     */
    public function getAllMenusOrPartMenus( $user )
    {
        if ( $user->is_admin === 1 ) {
            $admin_menus = $this->getAllMenus();
            $admin_uris = $this->getAllUris();
        } else {
            $admin_menus = $this->getPermissionMenus( $user );
            $admin_uris = $this->getPermissionUris( $user );
        }

        // buildTree->App/Helpers/helpers.php
        $admin_menus = buildTree( $admin_menus );

        return $menus = [
            'menus' => $admin_menus,
            'uris' => $admin_uris
        ];
    }

    /**
     * 拿到所有是菜单的权限
     * is_menu=0 -> 不是菜单
     * is_menu=1 -> 是菜单
     *
     * @return mixed
     */
    public function getAllMenus()
    {
        $admin_menus = Permission::where( 'is_menu', 1 )
            ->select( 'id', 'pid', 'name', 'uri' )
            ->orderBy( 'taxis' )
            ->get()
            ->toArray();

        return $admin_menus;
    }

    /**
     * 获取所以权限的URI
     *
     * @return array
     */
    public function getAllUris()
    {
        $admin_permissions = Permission::where( 'uri', '!=', '' )
            ->select( 'uri' )
            ->get()
            ->toArray();

        // 确保与getPermissionUris方法返回的数据格式一致
        $fresh_permissions = [];
        foreach ( $admin_permissions as $permission ) {
            $fresh_permissions[] = $permission['uri'];
        }

        return $fresh_permissions;
    }

    /**
     * 删除关联表中role和permission之间的联系
     *
     * @param $permission_id
     */
    public function delRolePermissionRelationsBy( $permission_id )
    {
        DB::table( 'admin_role_permission' )->where( 'permission_id', '=', $permission_id )->delete();
    }

    /**
     * 获取有权限的栏目
     *
     * ###
     *
     * @param $user
     * @return array
     */
    public function getPermissionMenus( $user )
    {
        $user_roles = $this->findRolesBy( $user->id );

        $user_permissions = [];

        foreach ( $user_roles as $role ) {

            $role_permissions = $this->findPermissionsBy( $role['id'] );

            foreach ( $role_permissions as $permission ) {
                // （关键）把pivot关联数组信息去除，
                // 原因是该项的存在使得数组的每个键值都变得唯一而达不到去重目的
                unset( $permission['pivot'] );

                if ( in_array( $permission, $user_permissions ) ) {
                    continue;
                }

                array_push( $user_permissions, $permission );
            }
        }

        return $user_permissions;
    }

    /**
     * 获取用户拥有的权限URI
     *
     * @param $user
     * @return array
     */
    public function getPermissionUris( $user )
    {
        $roles = $this->findRolesBy( $user->id );

        $permission_uris = [];

        foreach ( $roles as $role ) {

            $permissions = $this->findPermissionsBy( $role['id'], false );

            foreach ( $permissions as $permission ) {

                if ( in_array( $permission['uri'], $permission_uris ) || $permission['uri'] == '' ) {
                    continue;
                }

                $permission_uris[] = $permission['uri'];
            }
        }

        return $permission_uris;
    }

    /**
     * 通过用户ID找到该用户对应的所有角色
     *
     * @param $user_id
     * @return mixed
     */
    public function findRolesBy( $user_id )
    {
        return $user_roles = Admin::find( $user_id )->roles()->get()->toArray();
    }

    /**
     * 通过角色ID获取角色对应的所有权限
     *
     * @param $role_id
     * @param bool $is_menu
     * @return mixed
     */
    public function findPermissionsBy( $role_id, $is_menu = true )
    {
        if ( $is_menu === true ) {
            $role_permissions = Role::find( $role_id )->permissions()->where( 'is_menu', 1 )->get()->toArray();
        } else {
            $role_permissions = Role::find( $role_id )->permissions()->get()->toArray();
        }

        return $role_permissions;
    }

    /**
     * 获取所有权限
     * 支持分页
     * $page->0 => 不分页
     *
     * @param int $page
     * @param bool $returnArray
     * @return mixed
     */
    public function getAllPermissions( $page = 5, $returnArray = false )
    {
        if ( $page === 0 ) {
            if ( $returnArray === false ) {
                return $permissions = Permission::where( 'status', 1 )->orderBy( 'taxis' )->get();
            } else {
                return $permissions = Permission::where( 'status', 1 )->orderBy( 'taxis' )->get()->toArray();
            }
        }

        if ( $returnArray === false ) {
            return $permissions = Permission::where( 'status', 1 )->orderBy( 'taxis' )->paginate( $page );
        }
    }
}