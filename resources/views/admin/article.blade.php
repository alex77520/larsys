@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row menu-content">
            <div class="list-group col-sm-2">
                @foreach($cates as $cate)
                    <a href="{{ url('/admin/article/' . $cate->id) }}"
                       class="list-group-item {{ $cate->id == $cate_id ? 'active' : ''}}">
                        {{ $cate->name }}
                    </a>
                @endforeach
            </div>

            <div class="panel col-sm-9 col-sm-offset-1">
                <div class="panel-heading">
                    <h3 style="font-size: 18px; display: inline-block;"><b>文章管理</b></h3>
                    <a type="button" class="btn btn-success btn-sm pull-right"
                            href="{{ url('/admin/article/'. $cate_id .'/add') }}">添加文章</a>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover" style="table-layout:fixed">
                        <tr class="info">
                            <th>ID</th>
                            <th>标题</th>
                            <th>作者</th>
                            <th>点击率</th>
                            <th>属性</th>
                            <th>添加时间</th>
                            <th>更新时间</th>
                            <th>操作</th>
                            <th>排序</th>
                        </tr>
                        @foreach($articles as $article)
                            <tr>
                                <td><b>{{ $article->id }}</b></td>
                                <td style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">{{ $article->title }}</td>
                                <td>{{ $article->author }}</td>
                                <td>{{ $article->click_times }}</td>
                                <td>
                                    {{ $article->is_top === 'T' ? '置顶' : '普通' }}
                                    <span><b style="color: orangered;">{{ $article->is_hot === 'T' ? '火爆' : ''}}</b></span>
                                </td>
                                <td>{{ date_format($article->created_at, 'Y-m-d') }}</td>
                                <td>{{ date_format($article->updated_at, 'Y-m-d') }}</td>
                                <td>
                                    <a type="button" class="btn-xs btn-primary" href="{{ url('/admin/article/'. $article->id .'/edit') }}">编辑</a>
                                    <a type="button" class="btn-xs btn-danger" href="{{ url('/admin/article/'. $article->id .'/del') }}">删除</a>
                                </td>
                                <td>
                                    {{ $article->taxis }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="pull-right">{{ $articles->links() }}</div>
                </div>
            </div>
        </div>
    </div>

@endsection
