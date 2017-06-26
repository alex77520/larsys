<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Requests\AdminUserRequest;
use App\Repositories\UserRepository;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user_repository;

    public function __construct(UserRepository $userRepository)
    {
        $this->user_repository = $userRepository;
    }

    public function index()
    {
        $users = $this->user_repository->getAllUsersWithRoleName($page = 5);

        return view('admin.user', compact('users'));
    }

    public function add()
    {
        $roles = $this->user_repository->getAllRolesIdAndName();

        return view('admin.addUser', compact('roles'));
    }

    public function doAdd(AdminUserRequest $request)
    {
        $data = $request->except('roles');
        $data['password'] = bcrypt($request->input('password'));

        $user = $this->user_repository->createUser($data);

        if ((!$request->exists('is_admin')) && ($request->exists('roles'))) {
            $roles = $request->input('roles');

            $this->user_repository->allotRolesFor($user, $roles);
        }

        return redirect('/admin/user');
    }

    public function edit($user_id)
    {
        $user = $this->user_repository->findUserWithRoleIdAndName($user_id);

        $roles = $this->user_repository->getAllRolesIdAndName();

        $user_roles_id = $this->user_repository->getUserRolesIdBy($user);

        return view('admin.editUser', compact('user', 'roles', 'user_roles_id'));
    }

    public function doEdit(AdminUserRequest $request, $user_id)
    {
        $user = $this->user_repository->findUserBy($user_id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->is_admin = $request->exists('is_admin') ? $request->input('is_admin') : 0;
        $user->save();

        if ((!$request->exists('is_admin')) && ($request->exists('roles'))) {
            $roles = $request->input('roles');

            $this->user_repository->allotRolesFor($user, $roles);
        }

        return redirect('/admin/user');
    }

    public function del($user_id)
    {
        $user = $this->user_repository->findUserBy($user_id);

        if ($user->delete())
            return redirect('/admin/user');
    }

    public function frozen($user_id)
    {
        $user = $this->user_repository->findUserBy($user_id);
        $user->status = 0;

        if ($user->save())
            return redirect('/admin/user');
    }

    public function unfrozen($user_id)
    {
        $user = $this->user_repository->findUserBy($user_id);
        $user->status = 1;

        if ($user->save())
            return redirect('/admin/user');
    }
}
