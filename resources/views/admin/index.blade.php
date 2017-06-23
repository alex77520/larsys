@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row menu-content">
            <div class="panel">
                <div class="panel-heading">
                    <a href="{{ url('/admin') }}">首页</a>
                </div>
                <article class="panel-body">
                    欢迎来到Laravel管理系统！
                </article>
            </div>
        </div>
    </div>

@endsection
