@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row menu-content">
            <div class="panel">
                <div class="panel-heading">
                    <a href="{{ url('/admin') }}">基础->文章列表</a>
                </div>
                <article class="panel-body">
                    文章列表
                </article>
            </div>
        </div>
    </div>

@endsection
