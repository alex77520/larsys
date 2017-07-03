@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    管理员日志列表(5天以内)
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover">
                        <tr class="info">
                            <th>ID</th>
                            <th>登录用户</th>
                            <th>操作</th>
                            <th>URI</th>
                            <th>客户端IP地址</th>
                            <th>操作时间</th>
                        </tr>
                        @foreach($logs as $log)
                            <tr>
                                <td><b>{{ $log->id }}</b></td>
                                <td><b>{{ $log->username }}</b></td>
                                <td>{{ $log->name }}</td>
                                <td>{{ $log->uri }}</td>
                                <td>{{ $log->ip }}</td>
                                <td>{{ $log->created_at }}</td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="pull-right">{{ $logs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection