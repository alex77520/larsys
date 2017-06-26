@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel">
                <div class="panel-heading">
                    <h4>编辑角色</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{ url('/admin/role/doEdit/'.$role->id) }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">用户名称</label>
                            <div class="col-sm-8">
                                <input name="name" type="text" class="form-control" id="name"
                                       value="{{ old('name') ? old('name') : $role->name }}" placeholder="请输入名称" required>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="submit" class="btn btn-default btn-success pull-right">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection