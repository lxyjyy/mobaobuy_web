@extends(themePath('.')."seller.include.layouts.master")
@section('title','秣宝商户')
@section('styles')
    <style>
        #main{
            height: 400px;
            overflow: hidden;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #e3e3e3;
            -moz-border-radius: 4px;
            border-radius: 4px;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
        }
        　 #cnv {position:relative;margin:0px auto;padding:0px;width:600px;height:400px;overflow:hidden;}
    /*　　　　#cnv .content {background:#666;width:1280px;height:720px;padding:10px;color:#fff}*/
    </style>
@endsection
@section('content')
<div class="layui-layout layui-layout-admin">

    <!-- 内容主体区域 -->
    <div class="layui-header" style="background:#3b8cd8;" >
        <a href="/seller" id="firstT">
            <div class="layui-logo" style="background:#fff;">
            <img style="max-height: 40px;" src="{{getFileUrl(getConfig('shop_logo', asset('images/logo.png')))}}">
            </div>
        </a>
        <!-- 头部区域（可配合layui已有的水平导航） -->

        @include(themePath('.')."seller.include.partials._header")
    </div>
    <div class="layui-side" style="background:#383838; ">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="left-menu" style="color: #ffffff;">
                @include(themePath('.')."seller.include.partials._sidebar")
            </ul>
        </div>
    </div>
    <div class="clearfix"></div>
    <!-- 内容主体区域 -->
    <div class="layui-body" id="cnv" style="bottom: 0px;overflow:scroll;overflow-y:hidden;overflow-x:hidden;">
        <div class="layui-tab" lay-allowClose="true" lay-filter="tab-switch" >
            <ul class="layui-tab-title" style="position: sticky;background-color: white">
                <li class="layui-this" >后台首页</li>
            </ul>
            <div class="layui-tab-content" >
                <div class="layui-tab-item layui-show">
                    <div id="list" class="mt25 pt20" style="height: 300px">
                        <div style="padding: 20px; background-color: #F2F2F2;">
                            <div class="layui-row layui-col-space15">
                                <div class="layui-col-md6">
                                    <div class="layui-card">
                                        <div class="layui-card-header">本年度</div>
                                        <div class="layui-card-body">
                                            成交订单:<i class="red">{{$yearSalesVolume['num']}}</i>份<br>
                                            成交金额:<i class="red">{{number_format($yearSalesVolume['paid'])}}</i>元
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-md6">
                                    <div class="layui-card">
                                        <div class="layui-card-header">本月</div>
                                        <div class="layui-card-body">
                                            成交订单:<i class="red">{{$monthSalesVolume['num']}}</i>份<br>
                                            成交金额:<i class="red">{{number_format($monthSalesVolume['paid'])}}</i>元
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-col-md12">
                                    <div class="layui-card">
                                        <div class="layui-card-body">
                                            今日已成交订单: <i class="red">{{$dateSalesVolume['num']}}</i>份
                                            今日已成交金额: <i class="red">{{number_format($dateSalesVolume['paid'])}}</i>元
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="main" style="margin: 0 4px">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-footer" style="text-align: center;" >
    @include(themePath('.')."seller.include.partials._footer")
    </div>
</div>
@endsection
@section('script')
    <script src="{{asset(themePath('/').'e-chars/echarts-all.js')}}" ></script>
    <script src="{{asset(themePath('/').'e-chars/require.js')}}" ></script>
    <script type="text/javascript">
        require.config({
            paths: {
                echarts: 'theme/macarons'
            }
        });
        let month = [];
        var waitAffirm = [];
        var waitPay = [];
        var waitSend = [];
        var finished = [];
        // 第二个参数可以指定前面引入的主题
        var chart = echarts.init(document.getElementById('main'), 'macarons');
        $.ajax({
            url:'/seller/chars',
            data:'',
            type:'POST',
            async:false,
            success:function (res) {
                if (res.code==1){
                    for (let i1 in res.data.waitAffirm) {
                        waitAffirm.push(res.data.waitAffirm[i1]);
                    }
                    for (let i2 in res.data.waitPay) {
                        waitPay.push(res.data.waitPay[i2]);
                    }
                    for (let i3 in res.data.waitSend) {
                        waitSend.push(res.data.waitSend[i3]);
                    }
                    for (let i3 in res.data.finished) {
                        finished.push(res.data.finished[i3]);
                    }
                }
            }
        });
        chart.setOption({
            title : {
                text: '订单情况',
                subtext: ''
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                data:['待付款','待发货','已成交']
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data : ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月']
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'待付款',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data: waitPay
                },
                {
                    name:'待发货',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data: waitSend
                },
                {
                    name:'已成交',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data:finished
                }
            ]
        });



        var bodyWidth = $("body").width();

        if(bodyWidth<1380){
            $("#flyOwn").attr("height","92%");
        }else{
            $("#flyOwn").attr("height","95%");
        }

        $(window).resize(function(e) {
            bodyWidth = $("body").width();

            if(bodyWidth<1380){
                $("#flyOwn").attr("height","92%");
            }else{
                $("#flyOwn").attr("height","95%");
            }
        });
    </script>

@endsection


