@extends(themePath('.')."seller.include.layouts.master")
{{--<link rel="stylesheet" type="text/css" href="{{asset(themePath('/').'layui/css/dsc/general.css')}}" />--}}
{{--<link rel="stylesheet" type="text/css" href="{{asset(themePath('/').'layui/css/dsc/style.css')}}" />--}}
@section('body')
    <div class="warpper">
        <div class="title">店铺列表</div>
        <div class="content">
            <div class="explanation" id="explanation">
                <div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                    <li>自营的商家可以有多个店铺，这里显示店铺的信息。</li>
                </ul>
            </div>
            <div class="flexilist">
                <div class="common-head">
                    <div class="fl">
                        <a href="/admin/shop/store/add"><div class="fbutton"><div class="add" title="添加店铺"><span><i class="icon icon-plus"></i>添加店铺</span></div></div></a>
                    </div>
                    <div class="refresh">
                        <div class="refresh_tit" title="刷新数据">
                            <i class="icon icon-refresh"  style="display: block;margin-top: 1px;"></i></div>
                        <div class="refresh_span">刷新 - 共{{$total}}条记录</div>
                    </div>
                </div>
                <div class="common-content">
                    <form method="POST" action="" name="listForm">
                        <div class="list-div" id="listDiv">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                <tr>
                                    <th width="5%"><div class="tDiv">ID</div></th>
                                    <th width="15%"><div class="tDiv">注册时间</div></th>
                                    <th width="25%"><div class="tDiv">所属商家</div></th>
                                    <th width="25%"><div class="tDiv">店铺名称</div></th>
                                    <th width="10"><div class="tDiv">状态</div></th>
                                    <th width="20%" class="handle">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($storeList as $vo)
                                        <tr class="">
                                            <td><div class="tDiv">{{$vo['id']}}</div></td>
                                            <td><div class="tDiv">{{$vo['add_time']}}</div></td>
                                            <td><div class="tDiv">{{$vo['company_name']}}</div></td>
                                            <td><div class="tDiv">{{$vo['store_name']}}</div></td>
                                            <td>
                                                <div class="tDiv">
                                                    @if($vo['is_forbidden'] == 1)
                                                        <div class='layui-btn layui-btn-sm layui-btn-radius layui-btn-primary'>已禁用</div>
                                                    @else
                                                        <div class='layui-btn layui-btn-sm layui-btn-radius'>已启用</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="handle">
                                                <div class="tDiv a3">
                                                    <a href="javascript:void(0);" onclick="remove({{$vo['id']}})" title="移除" class="btn_trash"><i class="icon icon-trash"></i>删除</a>
                                                    @if($vo['is_forbidden'] == 1)
                                                        <a href="javascript:void(0);" onclick="setStatus('{{$vo['id']}}',0)" title="启用" class="btn_trash"><i class="layui-icon layui-icon-ok-circle"></i>启用</a>
                                                    @else
                                                        <a href="javascript:void(0);" onclick="setStatus('{{$vo['id']}}',1)" title="禁用" class="btn_trash"><i class="layui-icon layui-icon-close-fill"></i>禁用</a>
                                                    @endif

                                                    <a href="/admin/shop/store/edit?id={{$vo['id']}}&currentPage={{$currentPage}}" title="编辑" class="btn_edit"><i class="icon icon-edit"></i>编辑</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                <tfoot>
                                    <tr>
                                        <td colspan="12">
                                            <div class="tDiv">
                                                <div class="list-page">
                                                    <ul id="page"></ul>
                                                    <style>
                                                        .pagination li{
                                                            float: left;
                                                            width: 30px;
                                                            line-height: 30px;}
                                                    </style>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        layui.use(['upload','layer'], function() {
            var layer = layui.layer;

            $(".viewPic").click(function(){
                var src = $(this).attr('path');
                index = layer.open({
                    type: 1,
                    title: '大图',
                    // area: ['700px', '600px'],
                    content: '<img src="'+src+'">'
                });
            });

        });
        //启用、禁用
        function setStatus(id,is_forbidden){
            var _info = is_forbidden == 0 ? '启用' : '禁用';
            layui.use('layer', function(){
                var layer = layui.layer;
                layer.confirm('确定要'+_info+'该店铺吗?', {icon: 3, title:'提示'}, function(index){
                    $.ajax({
                        'url':'/admin/shop/store/setStatus',
                        'data':{
                            'id':id,
                            'is_forbidden':is_forbidden
                        },
                        'type':'post',
                        success: function (res) {
                            if (res.code == 1){
                                layer.msg(res.msg, {icon: 1,time:1000});
                                layer.close(index);
                                window.location.reload();
                            } else {
                                layer.msg(res.msg, {icon: 5,time:2000});
                            }
                        }
                    });
                });
            });
        }
        //删除
        function remove(id)
        {
            layui.use('layer', function(){
                var layer = layui.layer;
                layer.confirm('确定要删除吗?', {icon: 3, title:'提示'}, function(index){
                    $.ajax({
                        'url':'/admin/shop/store/delete',
                        'data':{
                            'id':id
                        },
                        'type':'post',
                        success: function (res) {
                            if (res.code == 1){
                                layer.msg(res.msg, {icon: 1,time:1000});
                                layer.close(index);
                                window.location.reload();
                            } else {
                                layer.msg(res.msg, {icon: 5,time:2000});
                            }
                        }
                    });
                });
            });
        }

        $("#submitBtn").click(function () {
            let settlement_bank_account_name = $("input[name='settlement_bank_account_name']").val();
            let settlement_bank_account_number = $("input[name='settlement_bank_account_number']").val();
            let data = {};
            if (settlement_bank_account_name){
                data.settlement_bank_account_name = settlement_bank_account_name;
            }
            if (settlement_bank_account_number) {
                data.settlement_bank_account_number = settlement_bank_account_number;
            }
            if (data.length==0){
                return false;
            }
            $.ajax({
                url:'/seller/updateCash',
                data:data,
                type:'POST',
                success: function (res) {
                    if (res.code==1){
                        layer.msg(res.msg);
                    } else {
                        layer.msg(res.msg);
                    }
                    window.location.reload();
                }
            });
        });
    </script>
@stop
