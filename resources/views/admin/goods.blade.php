@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row menu-content">
            <div class="list-group col-sm-2">
                @foreach($cates as $cate)
                    <a href="{{ url('/admin/goods/' . $cate->id) }}"
                       class="text-center list-group-item {{ $cate->id == $cate_id ? 'active' : ''}}">
                        {{ $cate->name }}
                    </a>
                @endforeach
            </div>

            <div class="panel col-sm-9 col-sm-offset-1">
                <div class="panel-heading">
                    <h3 style="font-size: 18px; display: inline-block;"><b>产品管理</b></h3>
                    <a type="button" class="btn btn-success btn-sm pull-right"
                       href="{{ url('/admin/goods/'. $cate_id .'/add') }}">添加产品</a>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover" style="table-layout:fixed">
                        <tr class="info">
                            <th>ID</th>
                            <th>名称</th>
                            <th>属性</th>
                            <th>添加时间</th>
                            <th>更新时间</th>
                            <th>操作</th>
                            <th>排序</th>
                        </tr>
                        @foreach($goods as $item)
                            <tr>
                                <td><b>{{ $item->id }}</b></td>
                                <td style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">{{ $item->name }}</td>
                                <td>
                                    {{ $item->is_top === 'T' ? '置顶' : '普通' }}
                                    <span><b style="color: orangered;">{{ $item->is_hot === 'T' ? '火爆' : ''}}</b></span>
                                </td>
                                <td>{{ date_format($item->created_at, 'Y-m-d') }}</td>
                                <td>{{ date_format($item->updated_at, 'Y-m-d') }}</td>
                                <td>
                                    <a type="button" class="btn-xs btn-primary" href="{{ url('/admin/goods/'. $item->id .'/edit') }}">编辑</a>
                                    <a type="button" class="btn-xs btn-danger" href="{{ url('/admin/goods/'. $item->id .'/del') }}">删除</a>
                                </td>
                                <td>
                                    {{ $item->taxis }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="pull-right">{{ $goods->links() }}</div>
                </div>
            </div>
        </div>
    </div>

@endsection
