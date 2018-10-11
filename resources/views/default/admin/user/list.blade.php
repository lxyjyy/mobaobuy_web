@extends(themePath('.')."admin.include.layouts.master")
@section('iframe')
<div class="warpper">
    <div class="title">会员 - 会员列表</div>
    <div class="content visible">

        <div class="explanation" id="explanation">
            <div class="ex_tit">
                <i class="sc_icon"></i>
                <h4>操作提示</h4>
                <span id="explanationZoom" title="收起提示"></span>
            </div>
            <ul>
                <li>已经审核通过的会员，审核按钮不再显示。</li>
            </ul>
        </div>

    </div>
    <div class="content">

        <div class="flexilist">
            <div class="common-head">
                <div class="fl">
                    <a href="javascript:download_userlist();"><div class="fbutton"><div class="csv" title="导出会员列表"><span><i class="icon icon-download-alt"></i>导出会员列表</span></div></div></a>
                </div>

                <div class="refresh">
                    <div class="refresh_tit" title="刷新数据"><i class="icon icon-refresh"></i></div>
                    <div class="refresh_span">刷新 - 共{{$userCount}}条记录</div>
                </div>

                <div class="search">
                    <form action="/admin/user/list" name="searchForm" >
                        <div class="input">
                            <input id="_token" type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <input  type="hidden" name="is_firm" value="{{$is_firm}}"/>
                            <input type="text" value="{{$user_name}}" name="user_name" class="text nofocus user_name" placeholder="会员名称" autocomplete="off">
                            <input type="submit" class="btn" name="secrch_btn" ectype="secrch_btn" value="">
                        </div>
                    </form>
                </div>

            </div>
            <div class="common-content">
                <form method="POST" action="" name="listForm" onsubmit="return confirm_bath()">
                    <div class="list-div" id="listDiv" data-id="{{$is_firm}}">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <thead>
                            <tr>

                                <th width="5%"><div class="tDiv">编号</div></th>
                                <th width="10%"><div class="tDiv">用户名</div></th>
                                <th width="10%"><div class="tDiv">昵称</div></th>
                                <th width="8%"><div class="tDiv">是否实名</div></th>
                                <th width="8%"><div class="tDiv">积分</div></th>
                                <th width="8%"><div class="tDiv">注册时间</div></th>
                                <th width="8%"><div class="tDiv">访问次数</div></th>

                                <th width="6%"><div class="tDiv">是否冻结</div></th>
                                <th width="12%" class="handle">操作</th>
                            </tr>
                            </thead>
                            <input id="_token" type="hidden" name="_token" value="{{ csrf_token()}}"/>
                            <tbody>
                            @foreach($users as $user)
                            <tr class="">

                                <td><div class="tDiv">{{$user['id']}}</div></td>
                                <td><div class="tDiv">{{$user['user_name']}}</div></td>
                                <td><div class="tDiv">{{$user['nick_name']}}</div></td>
                                <td><div class="tDiv">
                                        @if($user['userreal']==1)<div class='layui-btn layui-btn-sm layui-btn-radius'>已实名</div>
                                        @elseif($user['userreal']==0 && $user['is_validated'] == 0)<div class='layui-btn layui-btn-sm layui-btn-radius layui-btn-primary'>待审核</div>
                                        @else<div class='layui-btn layui-btn-sm layui-btn-radius  layui-btn-danger'>待实名</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="tDiv">
                                        <a href="/admin/user/points?id={{$user['id']}}&is_firm={{$user['is_firm']}}&currpage={{$currpage}}" class="layui-btn layui-btn-normal">{{$user['points']}}</a>
                                    </div>
                                </td>
                                <td><div class="tDiv">{{$user['reg_time']}}</div></td>
                                <td><div class="tDiv">{{$user['visit_count']}}</div></td>

                                <td>
                                    <div class="tDiv">
                                        <div class="switch @if($user['is_freeze']) active @endif" title="@if($user['is_freeze']) 是 @else 否 @endif" onclick="listTable.switchBt(this, '{{url('admin/user/change/active')}}', '{{$user['id']}}')">
                                            <div class="circle"></div>
                                        </div>
                                        <input type="hidden" value="0" name="">
                                    </div>
                                </td>

                                <td class="handle">
                                    <div class="tDiv a2">
                                        <a href="{{url('/admin/user/detail')}}?id={{$user['id']}}&is_firm={{$user['is_firm']}}&currpage={{$currpage}}" class="btn_see"><i class="sc_icon sc_icon_see"></i>查看</a>
                                        <a  href="{{url('/admin/user/log')}}?id={{$user['id']}}&is_firm={{$user['is_firm']}}&currpage={{$currpage}}" class="btn_see"><i class="sc_icon sc_icon_see"></i>日志</a>
                                        <a @if($user['is_validated']==1) style="display:none;" @endif href="{{url('/admin/user/verifyForm')}}?id={{$user['id']}}&is_firm={{$user['is_firm']}}&currpage={{$currpage}}" class="btn_see"><i class="sc_icon sc_icon_see"></i>审核</a>
                                        <a href="{{url('/admin/user/userRealForm')}}?id={{$user['id']}}&is_firm={{$user['is_firm']}}&currpage={{$currpage}}" class="btn_see"><i class="sc_icon sc_icon_see"></i>实名审核</a>
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
        paginate();
        function paginate(){
            layui.use(['laypage'], function() {
                var laypage = layui.laypage;
                laypage.render({
                    elem: 'page' //注意，这里是 ID，不用加 # 号
                    , count: "{{$userCount}}" //数据总数，从服务端得到
                    , limit: "{{$pageSize}}"   //每页显示的条数
                    , curr: "{{$currpage}}"  //当前页
                    , jump: function (obj, first) {
                        if (!first) {
                            window.location.href="/admin/user/list?currpage="+obj.curr+"&user_name={{$user_name}}&is_firm="+"{{$is_firm}}";
                        }
                    }
                });
            });
        }

        //导出会员
        function download_userlist()
        {
            location.href = "/admin/user/export";
        }

    </script>
@stop
