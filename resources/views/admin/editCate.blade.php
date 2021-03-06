@extends('layouts.admin')

@section('content')
    <div class="container">

        @include('vendor.ueditor.assets')

        <div class="row">
            <div class="panel">
                <div class="panel-heading">
                    <h4>编辑栏目</h4>
                </div>
                <div class="panel-body">

                {{--上传图片的真实表单位置--}}
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
                    <!--atlas-->
                    <div>
                        <form action="/api/admin/uploadImg" method="post" id="uploadAtlas">
                            {{ csrf_field() }}
                            <input type="file" name="uploadImg" class="hidden uploadAtlas"/>
                            <input type="text" name="type" value="atlas" class="hidden"/>
                        </form>
                    </div>
                    <script>
                        $(function () {
                            // 上传图标
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
                                        var str = '<img src="'+ res.msg +'" alt="'+ res.msg +'" width="40px" />';
                                        $('.icon-img').html(str);
                                    }
                                })
                            });

                            // 上传banner
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
                                        var str = '<img src="'+ res.msg +'" alt="'+ res.msg +'" width="40px" />';
                                        $('.banner-img').html(str);
                                    }
                                })
                            });

                            // 上传atlas
                            $('.uploadAtlas').change(function () {
                                $('#uploadAtlas').trigger('submit');
                            });
                            $('#uploadAtlas').on('submit', function (e) {
                                e.preventDefault();
                                $.ajax({
                                    url: '/api/admin/uploadImg',
                                    type: 'post',
                                    data: new FormData(this),
                                    contentType: false,
                                    cache: false,
                                    processData: false,
                                    success: function (res) {
                                        var str = '<div class="atlas-single">' +
                                            '<img class="atlas-img" src="'+ res.msg +'" alt="图集子图">' +
                                            '<input type="text" name="atlas[]" value="'+ res.msg +'" class="hidden">' +
                                            '<input class="atlas-tag form-control" type="text" name="ImageTags[]" placeholder="添加图片描述">' +
                                            '</div>';
                                        $('.atlas-body').append(str);
                                    }
                                })
                            });
                        })
                    </script>

                    <form class="form-horizontal" method="post" action="{{ url('/admin/cate/doEdit/' . $my_cate->id) }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">栏目名称</label>
                            <div class="col-sm-8">
                                <input name="name" type="text" class="form-control" id="name"
                                       value="{{ old('name') ? old('name') : $my_cate->name }}" placeholder="请输入名称" required>
                            </div>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="pid" class="col-sm-2 control-label">所属栏目</label>
                            <div class="col-sm-4">
                                <select name="pid" class="js-example-basic-single form-control pid-dropdown" id="pid" required>
                                    <option value="0">最高层级</option>
                                    @foreach($cates as $cate)
                                        <option value="{{ $cate->id }}" {{ $my_cate->pid == $cate->id ? 'selected' : ''}}>
                                            {{ str_repeat('—', 2 * $cate->level) . $cate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="level" class="col-sm-2 control-label">所处层级</label>
                            <div class="col-sm-3">
                                <input name="level" type="text" class="form-control" id="level"
                                       value="{{ old('level') ? old('level') : $my_cate->level }}" placeholder="请输入栏目的LEVEL,最高为0" required>
                            </div>
                            @if ($errors->has('level'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('level') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">栏目状态</label>
                            <div class="col-sm-2">
                                <select name="status" class="js-example-basic-single form-control pid-dropdown" id="status" required>
                                    <option value="1" {{ $my_cate->status == 1 ? 'selected' : '' }}>显示</option>
                                    <option value="0" {{ $my_cate->status == 0 ? 'selected' : '' }}>隐藏</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon" class="col-sm-2 control-label">栏目图标</label>
                            <div class="col-sm-6">
                                @if(isset($my_cate->images[0]))
                                    <input name="icon" type="text" class="form-control"
                                           value="{{ old('icon') ? old('icon') : $my_cate->images[0]->url }}"
                                           id="icon" placeholder="请选择栏目的图标">
                                @else
                                    <input name="icon" type="text" class="form-control"
                                           value="{{ old('icon') ? old('icon') : '' }}"
                                           id="icon" placeholder="请选择栏目的图标">
                                @endif
                            </div>
                            <div class="col-sm-1 icon-img">
                                @if(isset($my_cate->images[0]))
                                <img src="{{ $my_cate->images[0]->url }}" alt="{{ $my_cate->images[0]->url }}" width="40px" />
                                @endif
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" type="button" onclick="$('.uploadIcon').click();">选择图片</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="banner" class="col-sm-2 control-label">栏目大图</label>
                            <div class="col-sm-6">
                                @if(isset($my_cate->images[1]))
                                    <input name="banner" type="text" class="form-control"
                                           value="{{ old('banner') ? old('banner') : $my_cate->images[1]->url }}"
                                           id="banner" placeholder="请选择栏目的Banner">
                                @else
                                    <input name="banner" type="text" class="form-control"
                                           value="{{ old('banner') ? old('banner') : '' }}"
                                           id="banner" placeholder="请选择栏目的Banner">
                                @endif
                            </div>
                            <div class="col-sm-1 banner-img">
                                @if(isset($my_cate->images[1]))
                                    <img src="{{ $my_cate->images[1]->url }}" alt="{{ $my_cate->images[1]->url }}" width="40px" />
                                @endif
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" type="button" onclick="$('.uploadBanner').click();">选择图片</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="model" class="col-sm-2 control-label">栏目模型</label>
                            <div class="col-sm-4">
                                <select name="model" class="js-example-basic-single form-control model-dropdown" id="model" required>
                                    @foreach($models = $cates[0]->getModelName(null) as $key => $model)
                                        <option value="{{ $key }}" {{ $my_cate->model == $key ? 'selected' : '' }} >{{ $model }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group self-temp">
                            <label for="self_temp" class="col-sm-2 control-label">栏目模板</label>
                            <div class="col-sm-4">
                                <select name="self_temp" class="js-example-basic-single form-control self-dropdown" id="self_temp">
                                    <option value="">无</option>
                                    @foreach($self_temps as $self_temp)
                                        <option value="{{ $self_temp['prefix'] }}" {{ $my_cate->self_temp == $self_temp['prefix'] ? 'selected' : '' }}>{{ $self_temp['file'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group article-temp">
                            <label for="content_temp" class="col-sm-2 control-label">内容模板</label>
                            <div class="col-sm-4">
                                <select name="content_temp" class="js-example-basic-single form-control content-dropdown" id="content_temp">
                                    <option value="">无</option>
                                    @foreach($content_temps as $content_temp)
                                        <option value="{{ $content_temp['prefix'] }}"
                                                {{ $my_cate->content_temp == $content_temp['prefix'] ? 'selected' : '' }}
                                        >{{ $content_temp['file'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="digest" class="col-sm-2 control-label">栏目简介</label>
                            <div class="col-sm-8">
                                <textarea name="digest" rows="3"
                                          class="form-control" id="digest"
                                          placeholder="请输入简介">{{ old('digest') ? old('digest') : $my_cate->digest }}
                                </textarea>
                            </div>
                            @if($errors->has('digest'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('digest') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-sm-2 control-label">栏目内容</label>
                            <div class="col-sm-8">
                                <textarea name="content" type="text/plain" id="content">{{ old('content') ? old('content') : $my_cate->content }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="atlas" class="col-sm-2 control-label">图集</label>
                            <div class="col-sm-8">
                                <button type="button" class="btn btn-default btn-success" onclick="$('.uploadAtlas').click()">点击添加图集</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="atlas-body col-sm-8 col-sm-offset-2">
                                @foreach($atlas as $item)
                                    <div class="atlas-single">
                                        <img class="atlas-img" src="{{ $item->url }}" alt="图集子图">
                                        <input type="text" name="atlas[]" value="{{ $item->url }}" class="hidden">
                                        <input class="atlas-tag form-control" type="text" name="ImageTags[]" value="{{ $item->tags->name }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taxis" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-2">
                                <input name="taxis" type="text" class="form-control" id="taxis"
                                       value="{{ old('taxis') ? old('taxis') : $my_cate->taxis }}" placeholder="排序">
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