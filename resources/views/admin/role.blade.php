@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ asset('/admin/role/add') }}" class="btn btn-sm btn-success">添加角色</a>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover">
                        <tr class="info">
                            <th>ID</th>
                            <th>名称</th>
                            <th>权限</th>
                            <th>注册时间</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($roles as $role)
                            <tr>
                                <td><b>{{ $role->id }}</b></td>
                                <td>{{ $role->name }}</td>
                                <td><a type="button" class="btn-xs btn-warning" href="javascript:void(0);" onclick="layerOpen('{{ $role->id }}')">查看权限</a></td>
                                <td>{{ date_format($role->created_at, 'Y-m-d') }}</td>
                                <td>{{ date_format($role->updated_at, 'Y-m-d') }}</td>
                                <td>
                                    <a type="button" class="btn-xs btn-primary" href="{{ url('/admin/role/'. $role->id .'/edit') }}">编辑</a>
                                    <a type="button" class="btn-xs btn-danger" href="{{ url('/admin/role/'. $role->id .'/del') }}">删除</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="pull-right">{{ $roles->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var zNodes = [];

        function layerOpen(roleId) {
            var role = roleId;
            $.ajax({
                url: "/admin/role/getPermissions/"+ role,
                type: 'get',
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function (res) {
                    zNodes = res;
                    open(role, res);
                }
            });

            function open(role, res) {
                layer.open({
                    type: 1,
                    offset: '100px',
                    title: '配置权限',
                    area: '200px',
                    skin: 'layui-layer-rim', //加上边框
                    btn: ['提交'],
                    yes: function() {
                        $('.allotForm').submit()
                    },
                    btnAlign: 'c',
                    content: '<div class="content_wrap" id="role'+ role +'" style="margin: 10px;"><div class="zTreeDemoBackground left"><ul id="treeDemo" class="ztree"></ul>' +
                    '</div><form class="allotForm" action="/admin/role/allot/'+ role +'" method="post">{{ csrf_field() }}<div id="hiddenBox"></div></form></div>'
                });

                function zTreeOnCheck(event, treeId, treeNode) {
                    if (treeNode.isParent) {
                        $.each(treeNode.children, function (index, value) {
                            if (treeNode.checked) {
                                appendHidden(value.id);
                            } else {
                                removeHidden(value.id);
                            }
                        });
                    } else {
                        if (treeNode.checked) {
                            appendHidden(treeNode.id);
                        } else {
                            removeHidden(treeNode.id);
                        }
                    }
                };

                function appendHidden(id) {
                    var hiddenString = '<input type="hidden" name="permissions[]" value="' + id + '">';
                    $("#hiddenBox").append(hiddenString);
                }

                function removeHidden(id) {
                    $("#hiddenBox>input").each(function (index, element) {
                        if ($(this).val() == id) {
                            $(this).remove();
                        }
                    });
                }

                var setting = {
                    check: {
                        enable: true
                    },
                    data: {
                        simpleData: {
                            enable: true
                        }
                    },
                    callback: {
                        onCheck: zTreeOnCheck
                    }
                };

                var zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
                zTreeObj.expandAll(true);

                function appendChecked(res) {
                    var hiddenString = '';
                    $.each(res, function (i, v) {
                        if (v.checked == true) {
                            hiddenString += '<input type="hidden" name="permissions[]" value="' + v.id + '">';
                        }
                    })
                    $("#hiddenBox").append(hiddenString);
                }

                appendChecked(res);
            }
        }

    </script>
@endsection