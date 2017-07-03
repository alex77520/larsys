@extends('layouts.admin')

@section('content')
    <div class="container">

        @include('vendor.ueditor.assets')

        <div class="row">
            <div class="panel">
                <div class="panel-heading">
                    <h4>添加栏目</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{ url('/admin/cate/doAdd') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">栏目名称</label>
                            <div class="col-sm-8">
                                <input name="name" type="text" class="form-control" id="name"
                                       value="{{ old('name') ? old('name') : '' }}" placeholder="请输入名称" required>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-sm-2 control-label">栏目内容</label>
                            <div class="col-sm-8">
                                <textarea name="content" type="text/plain" id="content"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="submit" class="btn btn-default btn-success pull-right">提交</button>
                            </div>
                        </div>
                    </form>
                    <script type="text/javascript">
                        var ue = UE.getEditor('content');
                        ue.ready(function() {
                            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
                        });
                    </script>
                </div>
            </div>

        </div>
    </div>
@endsection