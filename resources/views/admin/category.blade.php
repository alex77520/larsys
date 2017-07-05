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
                            <th>层级</th>
                            <th>创建时间</th>
                            <th>操作</th>
                            <th>排序</th>
                        </tr>
                        @foreach($cates as $cate)
                            <tr>
                                <td><b>{{ $cate->id }}</b></td>
                                <td>{{ str_repeat('—', $cate->level * 2) . $cate->name }}</td>
                                <td><b>{{ $cate->getModelName($cate->model) }}</b></td>
                                <td>{{ $cate->status === 1 ? '显示' : '隐藏' }}</td>
                                <td>{{ $cate->level }}</td>
                                <td>{{ $cate->created_at }}</td>
                                <td>
                                    <a type="button" class="btn-xs btn-primary" href="{{ url('/admin/cate/'. $cate->id .'/edit') }}">编辑</a>
                                    <a type="button" class="btn-xs btn-danger" href="{{ url('/admin/cate/'. $cate->id .'/del') }}">删除</a>
                                </td>
                                <td>{{ $cate->taxis }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection