@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel">
                <div class="panel-heading">
                    <h4>添加权限</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{ url('/admin/permission/doEdit/'. $permission->id) }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">权限名称</label>
                            <div class="col-sm-8">
                                <input name="name" type="text" class="form-control" id="name"
                                       value="{{ old('name') ? old('name') : $permission->name }}" placeholder="请输入名称" required>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="roles" class="col-sm-2 control-label">父级权限</label>
                            <div class="col-sm-8">
                                <select name="pid" class="js-example-basic-single form-control" id="pid" required>
                                    <option value="0">最高层级</option>
                                    {!! $options !!}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uri" class="col-sm-2 control-label">权限标识</label>
                            <div class="col-sm-8">
                                <input name="uri" type="text" class="form-control" id="uri"
                                       value="{{ old('uri') ? old('uri') : $permission->uri }}" placeholder="请输入名称">
                            </div>
                            @if ($errors->has('uri'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('uri') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="is_menu" class="col-sm-2 control-label">作为菜单</label>
                            <div class="col-sm-8">
                                <div class="checkbox">
                                    <label>
                                        <input id="is_menu" type="checkbox" name="is_menu" value="1"
                                                {{ old('is_menu')||($permission->is_menu == 1) ? 'checked' : '' }}> 是
                                        <small>（选择此项该权限将作为栏目导航）</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taxis" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-8">
                                <input name="taxis" type="text" class="form-control" id="taxis"
                                       value="{{ old('taxis') ? old('taxis') : $permission->taxis }}" placeholder="排序">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="submit" class="btn btn-default btn-success pull-right">提交</button>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function () {
                                $(".js-example-basic-single").select2();
                            })
                        </script>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection