@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel">
                <div class="panel-heading">
                    <h4>编辑用户</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{ url('/admin/user/doEdit/'.$user->id) }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">用户名称</label>
                            <div class="col-sm-8">
                                <input name="name" type="text" class="form-control" id="name"
                                       value="{{ old('name') ? old('name') : $user->name }}" placeholder="请输入名称" required>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">用户邮箱</label>
                            <div class="col-sm-8">
                                <input name="email" type="email" class="form-control" id="email"
                                       value="{{ old('email') ? old('email') : $user->email }}" placeholder="请输入邮箱" required>
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">登录密码</label>
                            <div class="col-sm-8">
                                <input name="password" type="password" class="form-control" id="password"
                                       value="{{ old('password') ? old('password') : '' }}" placeholder="请输入长度大于4位的密码" required>
                            </div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password-confirm" class="col-md-2 control-label">确认密码</label>
                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control"
                                       value="{{ old('password-confirm') ? old('password-confirm') : '' }}" name="password_confirmation" placeholder="请确认密码" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="roles" class="col-sm-2 control-label">最高权限</label>
                            <div class="col-sm-8">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_admin" value="1"
                                                {{ old('is_admin')||($user->is_admin) ? 'checked' : '' }}> 给予
                                        <small>（系统管理员拥有最高权力，选择该项则不必再为用户分配其他角色）</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="roles" class="col-sm-2 control-label">分配角色</label>
                            <div class="col-sm-8">
                                <select name="roles[]" class="js-example-basic-multiple form-control" multiple="multiple" id="roles">
                                    @foreach($roles as $role)
                                        <option {{ in_array($role->id, (array)$user_roles_id) ? 'selected' : ''}}
                                                value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="submit" class="btn btn-default btn-success pull-right">提交</button>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function () {
                                $(".js-example-basic-multiple").select2({
                                    placeholder: '请为用户分配角色，可多选'
                                });
                            })
                        </script>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection