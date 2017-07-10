<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminPermissionRequest;
use App\Repositories\CacheRepository;
use App\Repositories\PermissionRepository;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    protected $permissionRepository;
    protected $cache;

    public function __construct(PermissionRepository $permissionRepository, CacheRepository $cacheRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->cache = $cacheRepository;
    }

    /**
     * 权限列表展示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $permissions = $this->permissionRepository->getAllPermissions($page = 20);

        return view('admin.permission', compact('permissions'));
    }

    /**
     * 添加权限页面展示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        $permissions = $this->permissionRepository->getAllPermissions($page = 0);

        $options = setDropDownMenu($permissions);

        return view('admin.addPermission', compact('options'));
    }

    /**
     * 编辑权限页面展示
     *
     * @param $permission_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($permission_id)
    {
        $permissions = $this->permissionRepository->getAllPermissions($page = 0);

        $permission = $this->permissionRepository->findPermission($permission_id);

        $options = setDropDownMenu($permissions);

        return view('admin.editPermission', compact('permission', 'options'));
    }

    /**
     * 删除权限
     *
     * @param $permission_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function del($permission_id)
    {
        if ($this->permissionRepository->destroyPermissionBy($permission_id))
            flash('删除权限成功！')->success();

        return redirect('/admin/permission');
    }

    /**
     * 执行添加权限操作
     *
     * @param AdminPermissionRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doAdd(AdminPermissionRequest $request)
    {
        if ($this->permissionRepository->createPermission($request->all()))
            flash('添加权限成功！')->success();

        return redirect('/admin/permission');
    }

    /**
     * 执行编辑权限操作
     *
     * @param AdminPermissionRequest $request
     * @param $permission_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doEdit(AdminPermissionRequest $request, $permission_id)
    {
        $permission = $this->permissionRepository->findPermission($permission_id);

        $permission->name = $request->input('name');
        $permission->uri = $request->input('uri');
        $permission->pid = $request->input('pid');
        $permission->taxis = $request->input('taxis');
        $permission->is_menu = $request->input('is_menu') == 1 ? $request->input('is_menu') : 0;

        if ($permission->save($request->all())) $this->cache->removeAllCache();

        flash('编辑权限成功！')->success();

        return redirect('/admin/permission');
    }

}
