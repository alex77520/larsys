<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminPermissionRequest;
use App\Repositories\PermissionRepository;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    protected $permission_repository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permission_repository = $permissionRepository;
    }

    public function index()
    {
        $permissions = $this->permission_repository->getAllPermissions($page = 10);

        return view('admin.permission', compact('permissions'));
    }

    public function add()
    {
        $permissions = $this->permission_repository->getAllPermissions($page = 0, $returnArray = true);

        $options = $this->permission_repository->buildOptionStr(buildTree($permissions));

        return view('admin.addPermission', compact('options'));
    }

    public function edit($permission_id)
    {
        $permissions = $this->permission_repository->getAllPermissions($page = 0, $returnArray = true);

        $permission = $this->permission_repository->findPermission($permission_id);

        $options = $this->permission_repository->buildOptionStr(buildTree($permissions), $permission->pid);

        return view('admin.editPermission', compact('permission', 'options'));
    }


    public function del($permission_id)
    {
        if ($this->permission_repository->destroyPermissionBy($permission_id))
            flash('删除权限成功！')->success();

        return redirect('/admin/permission');
    }

    public function doAdd(AdminPermissionRequest $request)
    {
        if ($this->permission_repository->createPermission($request->all()))
            flash('添加权限成功！')->success();

        return redirect('/admin/permission');
    }

    public function doEdit(AdminPermissionRequest $request, $permission_id)
    {
        $permission = $this->permission_repository->findPermission($permission_id);

        $permission->name = $request->input('name');
        $permission->uri = $request->input('uri');
        $permission->pid = $request->input('pid');
        $permission->is_menu = $request->input('is_menu') == 1
            ? $request->input('is_menu') : 0;

        if ($permission->save($request->all()))
            flash('编辑权限成功！')->success();

        return redirect('/admin/permission');
    }

}
