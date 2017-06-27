@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ asset('/admin/permission/add') }}" class="btn btn-sm btn-success">添加权限</a>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover">
                        <tr class="info">
                            <th>ID</th>
                            <th>名称</th>
                            <th>URI</th>
                            <th>属性</th>
                            <th>注册时间</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($permissions as $permission)
                            <tr>
                                <td><b>{{ $permission->id }}</b></td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->uri }}</td>
                                <td>
                                    <span>{{ $permission->is_menu == 1 ? '菜单' : '隐藏' }}</span>
                                </td>
                                <td>{{ date_format($permission->created_at, 'Y-m-d') }}</td>
                                <td>{{ date_format($permission->updated_at, 'Y-m-d') }}</td>
                                <td>
                                    <a type="button" class="btn-xs btn-primary" href="{{ url('/admin/permission/'. $permission->id .'/edit') }}">编辑</a>
                                    <a type="button" class="btn-xs btn-danger" href="{{ url('/admin/permission/'. $permission->id .'/del') }}">删除</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="pull-right">{{ $permissions->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection