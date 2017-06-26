@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ asset('/admin/user/add') }}" class="btn btn-sm btn-success">添加用户</a>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover">
                        <tr class="info">
                            <th>ID</th>
                            <th>名称</th>
                            <th>角色</th>
                            <th>邮箱</th>
                            <th>状态</th>
                            <th>注册时间</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($users as $user)
                            <tr>
                                <td><b>{{ $user->id }}</b></td>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @if($user->is_admin === 1)
                                        <b>超级管理员</b>
                                    @else
                                        @foreach($user->roles as $role)
                                            <span>{{ $role->name }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span>{{ $user->status == 1 ? '正常' : '冻结' }}</span>
                                </td>
                                <td>{{ date_format($user->created_at, 'Y-m-d') }}</td>
                                <td>{{ date_format($user->updated_at, 'Y-m-d') }}</td>
                                <td>
                                    <a type="button" class="btn-xs btn-primary" href="{{ url('/admin/user/'. $user->id .'/edit') }}">编辑</a>
                                    <a type="button" class="btn-xs btn-danger" href="{{ url('/admin/user/'. $user->id .'/del') }}">删除</a>
                                    @if($user->status === 1)
                                        <a type="button" class="btn-xs btn-warning" href="{{ url('/admin/user/'. $user->id .'/frozen') }}">冻结</a>
                                    @else
                                        <a type="button" class="btn-xs btn-success" href="{{ url('/admin/user/'. $user->id .'/unfrozen') }}">解冻</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="pull-right">{{ $users->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection