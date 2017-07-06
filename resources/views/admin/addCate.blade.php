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
                    <!--icons-->
                    <div>
                        <form action="/api/admin/uploadImg" method="post" id="uploadIcon">
                            {{ csrf_field() }}
                            <input type="file" name="uploadImg" class="hidden uploadIcon"/>
                            <input type="text" name="type" value="icons" class="hidden"/>
                        </form>
                    </div>
                    <!--banners-->
                    <div>
                        <form action="/api/admin/uploadImg" method="post" id="uploadBanner">
                            {{ csrf_field() }}
                            <input type="file" name="uploadImg" class="hidden uploadBanner"/>
                            <input type="text" name="type" value="banners" class="hidden"/>
                        </form>
                    </div>
                    <script>
                        $(function () {
                            $('.uploadIcon').change(function () {
                                $('#uploadIcon').trigger('submit');
                            });
                            $('#uploadIcon').on('submit', function (e) {
                                e.preventDefault();
                                 $.ajax({
                                 url: '/api/admin/uploadImg',
                                 type: 'post',
                                 data: new FormData(this),
                                 contentType: false,
                                 cache: false,
                                 processData: false,
                                 success: function (res) {
                                     $('input[name=icon]').val(res.msg);
                                 }})
                            });

                            $('.uploadBanner').change(function () {
                                $('#uploadBanner').trigger('submit');
                            });
                            $('#uploadBanner').on('submit', function (e) {
                                e.preventDefault();
                                $.ajax({
                                    url: '/api/admin/uploadImg',
                                    type: 'post',
                                    data: new FormData(this),
                                    contentType: false,
                                    cache: false,
                                    processData: false,
                                    success: function (res) {
                                        $('input[name=banner]').val(res.msg);
                                    }})
                            });
                        })
                    </script>
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
                            <label for="pid" class="col-sm-2 control-label">所属栏目</label>
                            <div class="col-sm-8">
                                <select name="pid" class="js-example-basic-single form-control pid-dropdown" id="pid" required>
                                    <option value="0">最高层级</option>
                                    @foreach($cates as $cate)
                                        <option value="{{ $cate->id }}">{{ str_repeat('—', 2 * $cate->level) . $cate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon" class="col-sm-2 control-label">栏目图标</label>
                            <div class="col-sm-6">
                                <input name="icon" type="text" class="form-control" id="icon" placeholder="请选择栏目的图标">
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-primary" onclick="$('.uploadIcon').click();">选择图片</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="banner" class="col-sm-2 control-label">栏目大图</label>
                            <div class="col-sm-6">
                                <input name="banner" type="text" class="form-control" id="banner" placeholder="请选择栏目的Banner">
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-primary" onclick="$('.uploadBanner').click();">选择图片</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="model" class="col-sm-2 control-label">栏目模型</label>
                            <div class="col-sm-8">
                                <select name="model" class="js-example-basic-single form-control model-dropdown" id="model" required>
                                    @foreach($models = $cates[0]->getModelName(null) as $key => $model)
                                        <option value="{{ $key }}">{{ $model }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group self-temp">
                            <label for="self_temp" class="col-sm-2 control-label">栏目模板</label>
                            <div class="col-sm-8">
                                <select name="self_temp" class="js-example-basic-single form-control self-dropdown" id="self_temp">
                                    <option value="">1111</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group article-temp">
                            <label for="content_temp" class="col-sm-2 control-label">内容模板</label>
                            <div class="col-sm-8">
                                <select name="content_temp" class="js-example-basic-single form-control content-dropdown" id="content_temp">
                                    <option value="">2222</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="digest" class="col-sm-2 control-label">栏目简介</label>
                            <div class="col-sm-8">
                                <textarea name="digest" rows="3" class="form-control" id="digest" placeholder="请输入简介"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-sm-2 control-label">栏目内容</label>
                            <div class="col-sm-8">
                                <textarea name="content" type="text/plain" id="content"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taxis" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-8">
                                <input name="taxis" type="text" class="form-control" id="taxis"
                                       value="{{ old('taxis') ? old('taxis') : '' }}" placeholder="排序">
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

                        $(function () {
                            $(".pid-dropdown").select2();
                            $(".model-dropdown").select2();
                            $(".self-dropdown").select2();
                            $(".content-dropdown").select2();
                        })

                    </script>

                </div>
            </div>

        </div>
    </div>
@endsection