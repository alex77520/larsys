@extends('layouts.admin')

@section('content')
    <div class="container">

        @include('vendor.ueditor.assets')

        <div class="row">
            <div class="panel">
                <div class="panel-heading">
                    <h4>添加文章</h4>
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
                    <!--attachment-->
                    <div>
                        <form action="/api/admin/uploadFile" method="post" id="uploadFile">
                            {{ csrf_field() }}
                            <input type="file" name="uploadFile" class="hidden uploadFile"/>
                            <input type="text" name="type" value="attachments" class="hidden"/>
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

                            // 上传附件
                            $('.uploadFile').change(function () {
                                $('#uploadFile').trigger('submit');
                            });
                            $('#uploadFile').on('submit', function (e) {
                                e.preventDefault();
                                $.ajax({
                                    url: '/api/admin/uploadFile',
                                    type: 'post',
                                    data: new FormData(this),
                                    contentType: false,
                                    cache: false,
                                    processData: false,
                                    success: function (res) {
                                        $('input[name=attachment]').val(res.msg);
                                    }})
                            });
                        })
                    </script>

                    <form class="form-horizontal" method="post" action="{{ url('/admin/article/'. $article_id .'/doEdit') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">文章标题</label>
                            <div class="col-sm-8">
                                <input name="title" type="text" class="form-control" id="title"
                                       value="{{ old('title') ? old('title') : $article->title }}" placeholder="请输入文章名称" required>
                            </div>
                            @if ($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="author" class="col-sm-2 control-label">文章作者</label>
                            <div class="col-sm-6">
                                <input name="author" type="text" class="form-control" id="author"
                                       value="{{ old('author') ? old('author') : $article->author }}" placeholder="请输入作者名称" required>
                            </div>
                            @if ($errors->has('author'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('author') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="comefrom" class="col-sm-2 control-label">文章来源</label>
                            <div class="col-sm-6">
                                <input name="comefrom" type="text" class="form-control" id="comefrom"
                                       value="{{ old('comefrom') ? old('comefrom') : $article->comefrom }}" placeholder="请输入来源">
                            </div>
                            @if ($errors->has('comefrom'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('comefrom') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="tag" class="col-sm-2 control-label">文章标签</label>
                            <div class="col-sm-6">
                                <input name="tag" type="text" class="form-control" id="tag"
                                       value="{{ old('tag') ? old('tag') : $article->tag }}" placeholder="请输入文章标签">
                            </div>
                            @if ($errors->has('tag'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('tag') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="icon" class="col-sm-2 control-label">文章图标</label>
                            <div class="col-sm-6">
                                @if(isset($article->images[0]))
                                    <input name="icon" type="text" class="form-control"
                                           value="{{ old('icon') ? old('icon') : $article->images[0]->url }}"
                                           id="icon" placeholder="请选择文章的图标">
                                @else
                                    <input name="icon" type="text" class="form-control"
                                           value="{{ old('icon') ? old('icon') : '' }}"
                                           id="icon" placeholder="请选择文章的图标">
                                @endif
                            </div>
                            <div class="col-sm-1 icon-img">
                                @if(isset($article->images[0]))
                                    <img src="{{ $article->images[0]->url }}" alt="{{ $article->images[0]->url }}" width="40px" />
                                @endif
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" type="button" onclick="$('.uploadIcon').click();">选择图片</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="banner" class="col-sm-2 control-label">文章大图</label>
                            <div class="col-sm-6">
                                @if(isset($article->images[1]))
                                    <input name="icon" type="text" class="form-control"
                                           value="{{ old('banner') ? old('banner') : $article->images[1]->url }}"
                                           id="icon" placeholder="请选择文章的Banner">
                                @else
                                    <input name="icon" type="text" class="form-control"
                                           value="{{ old('banner') ? old('banner') : '' }}"
                                           id="icon" placeholder="请选择文章的Banner">
                                @endif
                            </div>
                            <div class="col-sm-1 banner-img">
                                @if(isset($article->images[1]))
                                    <img src="{{ $article->images[1]->url }}" alt="{{ $article->images[1]->url }}" width="40px" />
                                @endif
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary" type="button" onclick="$('.uploadBanner').click();">选择图片</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="digest" class="col-sm-2 control-label">文章简介</label>
                            <div class="col-sm-8">
                                <textarea name="digest" rows="3" class="form-control" id="digest" placeholder="请输入文章简介">{{ old('digest') ? old('digest') : $article->digest }}
                                </textarea>
                            </div>
                            @if($errors->has('digest'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('digest') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="content" class="col-sm-2 control-label">文章内容</label>
                            <div class="col-sm-8">
                                <textarea name="content" type="text/plain" id="content">{{ old('content') ? old('content') : $article->content }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="attachment" class="col-sm-2 control-label">文章附件</label>
                            <div class="col-sm-6">
                                <input name="attachment" type="text" class="form-control"
                                       value="{{ old('attachment') ? old('attachment') : $article->attachment }}"
                                       id="attachment" placeholder="请选择附件">
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-primary" type="button" onclick="$('.uploadFile').click();">选择文件</button>
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
                                        <input class="atlas-tag form-control" type="text" name="tags[]" value="{{ $item->tags->name }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_top" class="col-sm-2 control-label">置顶</label>
                            <div class="col-sm-2">
                                <select name="is_top" class="js-example-basic-single form-control pid-dropdown" id="is_top" required>
                                    <option value="F" {{ $article->is_top === 'F' ? 'selected' : ''}}>否</option>
                                    <option value="T" {{ $article->is_top === 'T' ? 'selected' : ''}}>是</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_hot" class="col-sm-2 control-label">热门</label>
                            <div class="col-sm-2">
                                <select name="is_hot" class="js-example-basic-single form-control pid-dropdown" id="is_hot" required>
                                    <option value="F" {{ $article->is_hot === 'F' ? 'selected' : ''}}>否</option>
                                    <option value="T" {{ $article->is_hot === 'T' ? 'selected' : ''}}>是</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taxis" class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-2">
                                <input name="taxis" type="text" class="form-control" id="taxis"
                                       value="{{ old('taxis') ? old('taxis') : $article->taxis }}" placeholder="排序">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cate_id" class="col-sm-2 control-label">改变文章分类</label>
                            <div class="col-sm-2">
                                <select name="cate_id" class="js-example-basic-single form-control pid-dropdown" id="cate_id">
                                    @foreach($cates as $cate)
                                        <option value="{{ $cate->id }}" {{ $cate->id == $article->cate_id ? 'selected' : ''}}>{{ $cate->name }}</option>
                                    @endforeach
                                </select>
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

                        })
                    </script>
                </div>
            </div>

        </div>
    </div>
@endsection