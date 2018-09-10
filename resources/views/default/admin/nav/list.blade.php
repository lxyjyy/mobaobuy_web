@extends(themePath('.')."admin.include.layouts.master")
@section('iframe')
    <link rel="stylesheet" type="text/css" href="{{asset(themePath('/').'css/checkbox.min.css')}}" />
<div class="warpper">
    <div class="title">系统设置 - 自定义导航栏</div>
    <div class="content">

        <div class="flexilist">
            <div class="common-head">
                <div class="fl">
                    <a href="/nav/addForm"><div class="fbutton"><div class="add" title="添加新链接"><span><i class="icon icon-plus"></i>添加新链接</span></div></div></a>
                </div>
                <div class="refresh">
                    <div class="refresh_tit" title="刷新数据"><i class="icon icon-refresh"></i></div>
                    <div class="refresh_span">刷新 - 共{{$count}}条记录</div>
                </div>



            </div>
            <div class="common-content">
                <form method="POST" action="" name="listForm" onsubmit="return confirm_bath()">
                    <div class="list-div" id="listDiv">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <thead>
                            <tr>
                                <th width="15%"><div class="tDiv">名称</div></th>
                                <th width="10%"><div class="tDiv">是否显示</div></th>
                                <th width="10%"><div class="tDiv">是否新窗口</div></th>
                                <th width="10%"><div class="tDiv">地址</div></th>
                                <th width="8%"><div class="tDiv">位置</div></th>
                                <th width="10%"><div class="tDiv">排序</div></th>
                                <th width="12%" class="handle">操作</th>
                            </tr>
                            </thead>
                            <input id="_token" type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <tbody>
                            @foreach($navs as $nav)
                            <tr class="">
                                <input id="_token" type="hidden" name="_token" value="{{ csrf_token()}}"/>
                                <td><div class="tDiv">{{$nav['name']}}</div></td>
                                <td>
                                    <div class="tDiv">
                                        <label class="el-switch el-switch-lg">
                                            <input type="checkbox" @if($nav['is_show']==1)checked @endif  name="switch" value="{{$nav['is_show']}}"  data-id="{{$nav['id']}}"   hidden>
                                            <span class="j_click1 el-switch-style"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="tDiv">
                                        <label class="el-switch el-switch-lg">
                                            <input type="checkbox" @if($nav['opennew']==1)checked @endif  name="switch" value="{{$nav['opennew']}}"  data-id="{{$nav['id']}}"   hidden>
                                            <span class="j_click2 el-switch-style"></span>
                                        </label>

                                    </div>
                                </td>
                                <td><div class="tDiv">{{$nav['url']}}</div></td>
                                <td><div class="tDiv">{{$nav['type']}}</div></td>
                                <td><div class="tDiv changeInput">
                                        <input type="text" name="sort_order" class="text w40 " data-id="{{$nav['id']}}" value="{{$nav['sort_order']}}" >
                                    </div></td>

                                <td class="handle">
                                    <div class="tDiv a2">
                                        <a href="/nav/editForm?id={{$nav['id']}}" title="编辑" class="btn_edit"><i class="icon icon-edit"></i>编辑</a>
                                        <a href="javascript:void(0);" onclick="remove({{$nav['id']}})" title="移除" class="btn_trash"><i class="icon icon-trash"></i>删除</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="12">
                                    <div class="tDiv">

                                        <div class="list-page">
                                            <!-- $Id: page.lbi 14216 2008-03-10 02:27:21Z testyang $ -->


                                            {{$navs->links()}}

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


        $('.j_click1').click(function(){
                var is_show ;
                var id = $(this).siblings('input').attr('data-id');
                var input = $(this).siblings('input');
                if (input.val() === '1') {
                    is_show = 0;
                } else {
                    is_show = 1;
                }

                layui.use(['layer'], function() {
                    layer = layui.layer;
                    $.post("{{url('/nav/status')}}",{"id":id,"is_show":is_show,"_token":$("#_token").val()},function(res){
                        if(res.code==200){
                            layer.msg(res.msg, {icon: 1});
                            input.val(res.data);
                        }else{
                            layer.msg(res.msg, {icon: 5});
                        }
                    },"json");

                });
        });


        $('.j_click2').click(function(){
            var opennew ;
            var id = $(this).siblings('input').attr('data-id');
            var input = $(this).siblings('input');
            if (input.val() === '1') {
                opennew = 0;
            } else {
                opennew = 1;
            }

            layui.use(['layer'], function() {
                layer = layui.layer;
                $.post("{{url('/nav/status')}}",{"id":id,"opennew":opennew,"_token":$("#_token").val()},function(res){
                    if(res.code==200){
                        layer.msg(res.msg, {icon: 1});
                        input.val(res.data);
                    }else{
                        layer.msg(res.msg, {icon: 5});
                    }
                },"json");

            });
        });

        function remove(id)
        {
            layui.use('layer', function(){
                var layer = layui.layer;
                layer.confirm('确定要删除吗?', {icon: 3, title:'提示'}, function(index){
                    window.location.href="/nav/delete?id="+id;
                    layer.close(index);
                });
            });

        }

        $(".changeInput input").blur(function(){

            var sort_order = $(this).val();
            var id = $(this).attr('data-id');
            var postData = {
                'id':id,
                'sort_order':sort_order,
                '_token':$("#_token").val(),
            }
            //console.log(postData);
            var url = "/nav/sort";
            $.post(url,postData,function(res){
                if(res.code==200){
                    window.location.href=res.data;
                }else{
                    alert('更新失败');
                }
            },"json");

        });


    </script>
@stop
