@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <span><a href="{{ url('/admin/image') }}">图片管理</a></span>
                    <div class="btn-group pull-right" role="group">
                        <button class="btn btn-sm btn-warning dropdown-toggle" type="button" id="dropdownMenu"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">筛选图片
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="{{ url('/admin/image/0') }}">图标</a></li>
                            <li><a href="{{ url('/admin/image/1') }}">Banner</a></li>
                            <li><a href="{{ url('/admin/image/2') }}">图集</a></li>
                            <li><a href="{{ url('/admin/image/3') }}">轮播图</a></li>
                        </ul>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover image-table">
                        <tr class="info image-table-tr">
                            <th>ID</th>
                            <th>图像</th>
                            <th>所属模型</th>
                            <th>模型ID</th>
                            <th>类型</th>
                            <th>添加时间</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($images as $image)
                            <tr class="image-table-tr">
                                <td><b>{{ $image->id }}</b></td>
                                <td><img src="{{ $image->url }}" alt="{{ $image->url }}" width="50px"></td>
                                <td>{{ $image->model_type }}</td>
                                <td>{{ $image->model_id }}</td>
                                <td>{{ $image->getImgType($image->type) }}</td>
                                <td>{{ date_format($image->created_at, 'Y-m-d') }}</td>
                                <td>{{ date_format($image->updated_at, 'Y-m-d') }}</td>
                                <td>
                                    <a type="button" class="btn-xs btn-primary" href="{{ url('/admin/image/'. $image->model_id .'/edit?model=' . class_basename($image->model_type)) }}">图片位置</a>
                                    <a type="button" class="btn-xs btn-danger" href="{{ url('/admin/image/'. $image->id .'/del') }}">删除</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="pull-right">{{ $images->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    </script>
@endsection