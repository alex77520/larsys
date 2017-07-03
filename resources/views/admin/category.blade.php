@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ asset('/admin/cate/add') }}" class="btn btn-sm btn-success">添加栏目</a>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover">
                        <tr class="info">
                            <th>ID</th>
                            <th>栏目名称</th>
                            <th>模型</th>
                            <th>状态</th>
                            <th>创建时间</th>
                            <th>操作</th>
                            <th>排序</th>
                        </tr>
                        @foreach($cates as $cate)
                            <tr>
                                <td><b>{{ $cate['id'] }}</b></td>
                                <td>{{ $cate['name'] }}</td>
                                <td><b>{{ $cate['model'] }}</b></td>
                                <td>{{ $cate['status'] === 1 ? '显示' : '隐藏' }}</td>
                                <td>{{ $cate['created_at'] }}</td>
                                <td>
                                    <a type="button" class="btn-xs btn-primary" href="{{ url('/admin/cate/'. $cate['id'] .'/edit') }}">编辑</a>
                                    <a type="button" class="btn-xs btn-danger" href="{{ url('/admin/cate/'. $cate['id'] .'/del') }}">删除</a>
                                </td>
                                <td>{{ $cate['taxis'] }}</td>
                            </tr>
                            @if(!empty($cate['sub_menu']))
                                @foreach($cate['sub_menu'] as $sub_menu)
                                <tr>
                                    <td><b>{{ $sub_menu['id'] }}</b></td>
                                    <td>|__{{ $sub_menu['name'] }}</td>
                                    <td><b>{{ $sub_menu['model'] }}</b></td>
                                    <td>{{ $sub_menu['status'] === 1 ? '显示' : '隐藏' }}</td>
                                    <td>{{ $sub_menu['created_at'] }}</td>
                                    <td>
                                        <a type="button" class="btn-xs btn-primary" href="{{ url('/admin/cate/'. $sub_menu['id'] .'/edit') }}">编辑</a>
                                        <a type="button" class="btn-xs btn-danger" href="{{ url('/admin/cate/'. $sub_menu['id'] .'/del') }}">删除</a>
                                    </td>
                                    <td>{{ $sub_menu['taxis'] }}</td>
                                </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection