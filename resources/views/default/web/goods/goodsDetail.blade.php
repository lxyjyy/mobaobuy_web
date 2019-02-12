@extends(themePath('.','web').'web.include.layouts.home')
@if(empty(getSeoInfoByType('goods')['title']))
    @section('title', $good_info['goods_name'])
@else
    @section('title', $good_info['goods_name'].'-'.getSeoInfoByType('goods')['title'])
@endif
@section('keywords', getSeoInfoByType('goods')['keywords'])
@section('description', getSeoInfoByType('goods')['description'])
@section('css')
	<style>
		.Self-product-list li span{width:14%;}
		.news_pages ul.pagination {text-align: center;}
		.Self-product-list li span{width: 12.5%;float: left;text-align: center;}
		.pro_chart{float:left;width: 528px; }
		.pro_chart_title{line-height: 70px;text-align: center;font-size: 18px;border: 1px solid #DEDEDE;border-bottom: 1px solid #DEDEDE;}
		.pro_chart_img{height: 355px;border: 1px solid #DEDEDE;}
		.pro_price{width: 635px;height: 56px;line-height: 56px;overflow: hidden;}
		.pro_detail{overflow: hidden;margin-top: 20px;}
		.pro_price_dj{width: 493px;height: 45px;line-height: 45px;margin-top: 5px;display: block;}
		.start_amount{float: left;width: 141px;line-height:20px;text-align: center;margin-top: 7px;}
		.pro_value{width: 270px;margin-left: 15px;float: left;}
		.letter-space{letter-spacing: 30px;}
		.pro_detail_title{width: 88px;}
		.pro_chart_opert{width: 50px;height: 18px;display: inline-block;color: #666;padding-left: 23px;margin-top: 10px;}
		.follow{background: url(/images/pro_detail_icon.png)no-repeat 0px -61px;}
		.share{background: url(/images/pro_detail_icon.png)no-repeat -124px 3px;}
		.follow_btn{background: #b1b1b1 url(/images/pro_detail_icon.png)no-repeat 20px 14px;}
		.pur_volume{float:left;border: 1px solid #DEDEDE; box-sizing:border-box;}
		.pur_volume .pur{cursor:pointer;width: 26px;text-align: center;float: left;height: 28px;line-height: 28px;background-color: #fafafa;box-sizing:border-box;}
		.pur_num{float:left;width: 50px;height: 28px;line-height: 28px;text-align: center;border: none;}
		.pro_detail_btn{cursor:pointer;width: 140px;height: 42px;line-height: 42px;border: none;font-size:16px;color: #fff;border-radius:3px;}
        .History_offo{height: 50px;line-height: 50px;border-bottom: 1px solid #d4d3d3;background-color: #f0f0f0;box-sizing: border-box;}
        .History_offo h1{line-height: 50px;  height: 50px;text-align: center;color: #666;font-size: 18px;}
        .History_offo .titlecurr{border-bottom: 2px solid #75b335;  color: #75b335;margin: 0 auto; cursor: pointer;}
        .History-product-list{margin-top: 10px;}
        .History-product-list li span{width: 14.2%;float: left;text-align: center;}
        .History-product-list li{line-height: 43px;background-color: #fff;}
        .History-product-list li:first-child{line-height: 40px; }
        .History-product-list li:last-child{border-bottom: none;}
        .History_offo li{float: left;width: 106px;}
        .orangebg{background-color:#ff6f17;}
        .nav-div .nav-cate .ass_menu {display: none;}

        .quoteList{height: 280px;}
        .quoteList li span{width: 14.2%;float: left;text-align: center;}
        .quoteList li{line-height: 45px;background-color: #fff; border-bottom: 1px solid #DEDEDE;
            height: 45px;}
        /*.quoteList li:first-child{line-height: 40px; }*/
        /*.quoteList li:last-child{border-bottom: none;}*/
        .input_data{ padding-left: 5px;   border: 1px solid #dedede; height: 27px; box-sizing: border-box;width:158px}
        .chart_btn{    cursor: pointer;border: none; background-color: #dcdcdc; padding: 3.5px 10px; color: #807b7b;font-size: 13px;}
        .chart_btn:hover{background-color: #75b335; color: #fff;}
        .chart_btn.currlight{background-color: #75b335; color: #fff;}
    </style>
@endsection
@section('js')
{{--<script src="https://cdn.bootcss.com/echarts/4.2.0-rc.2/echarts-en.common.js"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/4.1.0/echarts-en.js"></script>
<script type="text/javascript" src="{{asset(themePath('/','web').'plugs/My97DatePicker/4.8/WdatePicker.js')}}"></script>
{{--<script type="text/javascript" src="https://hanlei525.github.io/layui-v2.4.3/layui-v2.4.5/layui.js"> </script>--}}
    {{--<link href="https://hanlei525.github.io/layui-v2.4.3/layui-v2.4.5/css/layui.css" rel="stylesheet" type="text/css"/>--}}
	<script>
        $(function(){
            // 更多/收起
            $('.pro_more').click(function(){
                $(this).toggleClass('pro_up')
                var mPro=$(this).text();
                if (mPro=='收起') {
                    $(this).text('更多');
                    $(this).prev('.pro_brand_list').removeClass('heightcurr');
                } else{
                    $(this).text('收起');
                    $(this).prev('.pro_brand_list').addClass('heightcurr');
                }
            })
            //更多选项
            $('.more_filter_box').click(function(){
                var mText = $(this).text();
                if(mText=='更多选项...'){
                    $('.pro_screen').removeClass('height0');
                    $('.pro_screen').addClass('heightcurr')
                    $('.more_filter_box').text('隐藏选项...');
                    $('.pro_Open').toggleClass('pro_Open_down');
                }else{
                    $('.pro_screen').removeClass('heightcurr');
                    $('.more_filter_box').text('更多选项...');
                }
            });
            //数量输入检测
            $('#pur_num').blur(function(){
                var _self = $(this);
                //数量
                var goodsNumber = Number(_self.val());//当前输入值 10
                var packing_spec = Number(_self.attr('packing_spec'));//规格 20
                var min_limit = Number(_self.attr('min_limit'));//最小采购量
                var can_num = Number(_self.attr('can_num')); //可售
                if(min_limit>packing_spec){
                    var min_count = min_limit;
                }else{
                    var min_count = packing_spec;
                }
                if((/^(\+|-)?\d+$/.test( goodsNumber ))&&goodsNumber>=min_count){
                    if(goodsNumber > can_num){
                        var _count = can_num%packing_spec; //整除为0
                        if(_count>0){
                            $(".pur_num").val(can_num - _count);
                        }else{
                            $(".pur_num").val(can_num);
                        }
                    }else{
                        var _count2 = goodsNumber%packing_spec;
                        if(_count2>0){
                            $(".pur_num").val(goodsNumber - _count2);
                        }else if(_count2==0){
                            $(".pur_num").val(goodsNumber);
                        }
                    }
                }else{
                    $.msg.error('输入的数量有误');
                    _self.val(min_count);
                }
            });

            $(".nav-cate").hover(function(){
                $(this).children('.ass_menu').toggle();// 鼠标悬浮时触发
            });
            var myChart = echarts.init(document.getElementById('price_zst'));
            var option = {
                tooltip : {
                    trigger: 'axis',
                    formatter: function (params) {
                        var res = params[0].seriesName + ' ' + params[0].name;
//                        res += '<br/>  最低价 : ' + params[0].value[1] + '  最高价 : ' + params[0].value[2];
                        return res;
                    }
                },
                legend: {
                    data:['价格走势']
                },
                dataZoom : {
                    show : true,
                    realtime: true,
                    start : 0,
                    end : 100
                },
                xAxis : [
                    {
                        type : 'category',
                        boundaryGap : true,
                        axisTick: {onGap:false},
                        splitLine: {show:false},
                        data : []
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        scale:true,
                        boundaryGap: [0.01, 0.01]
                    }
                ],
                series : [
                    {
                        name:'价格指数',//上证指数
                        type:'k',
                        barMaxWidth: 20,
                        data:[ // 开盘，收盘，最低，最高

                        ],
                    }
                ]
        };
            myChart.setOption(option);
            //页面初始化获取按日的全部信息
            getChartInfo();

            $('.get_chart_day').click(function(){
                $('.hid_type').val(1);
                $(this).siblings().removeClass('currlight');
                $(this).addClass('currlight');
                getChartInfo()
            });
            $('.get_chart_week').click(function(){
                $('.hid_type').val(2);
                $(this).siblings().removeClass('currlight');
                $(this).addClass('currlight');
                getChartInfo()
            });
            $('.get_chart_month').click(function(){
                $('.hid_type').val(3);
                $(this).siblings().removeClass('currlight');
                $(this).addClass('currlight');
                getChartInfo()
            });
            $('.search_btn').click(function(){
                getChartInfo()
            });

            function getChartInfo(){
                var _goods_id = $('.goods_id').val();
                var _type = $('.hid_type').val();
                var _begin_time = $('#begin_time').val();
                var _end_time = $('#end_time').val();
                $.get('/price/ajaxcharts?id='+_goods_id+'&type='+_type+'&begin_time='+_begin_time+'&end_time='+_end_time).done(function (data) {
                    console.log(data);

                    myChart.setOption({
                        xAxis: {
                            data: data.data.time
                        },
                        series: [{
                            // 根据名字对应到相应的系列
                            data: data.data.price
                        }]
                    });
                })
            }


        })
	</script>
<script>
    $(function(){
        $('.HistoryLi li').hover(function(){
            $(this).addClass('titlecurr').siblings().removeClass('titlecurr');
            $('.proitemlist>li').eq($(this).index()).show().siblings().hide();
        });
        var tipindex
        $('#show_yf_tips').hover(function(){
            tipindex = layer.tips('秣宝网产品报价均不含运费，运费价格需联系客服人员确认。', '#show_yf_tips', {
                tips: [1, '#75b335'],
                time: 0
            });
        },function () {
            layer.close(tipindex);
        })

    });
</script>

@endsection

@section('content')
    <div class="clearfix" style="background-color:white;">
	<div class="w1200 pr ovh">
		<div class="crumbs mt5">当前位置：<a href="/goodsList">商品列表</a> &gt;<span class="gray">{{$good_info['goods_name']}}</span></div>
		<div class="pro_chart mt5" style="position:relative">
            <div style="position:absolute;left:110px;top:200px;"><img src="/images/mobao_logo1.png" style="opacity:0.8;" width="250"/></div>
			<h2 class="pro_chart_title">
				商品价格走势
			</h2>
            <div style="margin: 10px 0">
                <input type="text" class="Wdate input_data" autocomplete="off" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'end_time\')||\'%y-%M-%d\'}'})" id="begin_time" placeholder="开始时间">
                <input type="text" class="Wdate input_data" autocomplete="off" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'begin_time\')}',maxDate:'%y-%M-%d'})" id="end_time" placeholder="结束时间">
                <input type="button" class="search_btn chart_btn" value="查询" />
                <input type="hidden" class="hid_type " value="1" />
                <input type="hidden" class="goods_id" value="{{$good_info['goods_id']}}" />
                <input class="get_chart_day chart_btn currlight" type="button" value="按日">
                <input class="get_chart_week chart_btn" type="button" value="按周">
                <input class="get_chart_month chart_btn" type="button" value="按月">
            </div>


			<div class="pro_chart_img" id="price_zst">

			</div>

		</div>
		<div class="fl ml35 mt5">
			<h2 class="fwb fs16">{{$good_info['goods_full_name']}}</h2>
			<span class="red mt5 db"></span>
			<div class="pro_price f4bg mt10">
				<div class="pro_price_dj fl"><span class="ml15 letter-space">单价</span><span class="ml15 fwb"><font class="fs22 red">￥{{$good_info['shop_price']}}元</font>/{{$good_info['unit_name']}}</span></div>

			</div>
			<div class="pro_detail">
				<span class="ml15 pro_detail_title letter-space fl" style="letter-spacing:0px;">交货时间</span>
                <span  class="pro_value">{{$good_info['delivery_time']}}</span>
                <span class="fl ">包装规格</span>
                <span  class="ml35 fl ovhwp" style="width: 150px;" title="{{$good_info['packing_spec'].$good_info['unit_name']}}/{{$good_info['packing_unit']}}">{{$good_info['packing_spec'].$good_info['unit_name']}}/{{$good_info['packing_unit']}}</span>
			</div>

			<div class="pro_detail">
				<span class="ml15 letter-space fl">编号</span><span  class="pro_value">{{$good_info['goods_sn']}}</span>
                <span class="fl letter-space">品牌</span><span  class="ml5 fl ovhwp" style="width: 150px;" title="{{$good_info['brand_name']}}">{{$good_info['brand_name']}}</span>
			</div>

            <div class="pro_detail">
                <span class="ml15 pro_detail_title fl" style="letter-spacing:8px;">业务员</span><span  class="pro_value">{{$good_info['salesman']}}</span>
                <span class="fl">联系方式</span><span  class="ml35 fl ovhwp" style="width: 150px;" title="{{$good_info['contact_info']}}">{{$good_info['contact_info']}}</span>
            </div>

            <div class="pro_detail">
                <span class="ml15 pro_detail_title fl">生产日期</span><span  class="pro_value">{{$good_info['production_date']}}</span>
                 <span class="fl letter-space">含量</span><span  class="ml5 fl ovhwp" style="width: 150px;" title="{{$good_info['goods_content']}}">{{$good_info['goods_content']}}</span>
            </div>
            <div class="pro_detail">
                <span class="ml15 pro_detail_title fl">运费说明</span>
                <span  class="pro_value">待定 <span style="color: #bbbbbb;" id="show_yf_tips">物流说明</span></span>
                {{--<span class="fl letter-space">含量</span><span  class="ml5 fl ovhwp" style="width: 150px;" title="{{$good_info['goods_content']}}">{{$good_info['goods_content']}}</span>--}}
            </div>
			<div class="pro_detail bd1"></div>
			<div class="pro_detail">

				<span class="ml15 fl pro_detail_title" style="letter-spacing: 2px; height: 28px;line-height: 28px;">采  购  量</span>
                <div class="pur_volume ml15 fl">
                    <span class="pur shop_num_reduce">-</span>
                    <input type="text" cid="{{$good_info['id']}}" can_num="{{$good_info['goods_number']}}" packing_spec="{{$good_info['packing_spec']}}" min_limit="{{$good_info['min_limit']}}" id="pur_num" class="pur_num" @if($good_info['packing_spec']>$good_info['min_limit']) value="{{$good_info['packing_spec']}}" @else value="{{$good_info['min_limit']}}" @endif/>
                    <span class="pur shop_num_plus">+</span>
                </div>
                <span class="fl" style="line-height: 28px;margin-left: 5px">{{$good_info['unit_name']}}</span>
                <span class="fl" style="line-height: 28px;margin-left: 50px">最小采购数量：</span>
                <span  class="ml5 fl ovhwp" style="width: 150px;line-height: 28px;margin-left: 5px">{{$good_info['min_limit'] ? $good_info['min_limit'] : $good_info['packing_spec']}}{{$good_info['unit_name']}}</span>
			</div>

			<div class="mt30" style="margin-left: 115px;">
                @if(session('_web_user_id'))
                    @if($collectGoods)
                        @if(!empty($good_info['expiry_time']) && $good_info['expiry_time'] < \Carbon\Carbon::now())
                             <button class="pro_detail_btn">已结束</button><button class="pro_detail_btn cccbg ml15 follow_btn">已收藏</button>
                        @else
                             <button class="pro_detail_btn orangebg">加入购物车</button><button class="pro_detail_btn cccbg ml15 follow_btn">已收藏</button>
                        @endif
                    @else
                        @if(!empty($good_info['expiry_time']) && $good_info['expiry_time'] < \Carbon\Carbon::now())
                                 <button class="pro_detail_btn">已结束</button><button class="pro_detail_btn cccbg ml15 follow_btn">收藏商品</button>
                        @else
                                <button class="pro_detail_btn orangebg">加入购物车</button><button class="pro_detail_btn cccbg ml15 follow_btn">收藏商品</button>
                        @endif
                    @endif
                @else
                    @if(!empty($good_info['expiry_time']) &&  $good_info['expiry_time'] < \Carbon\Carbon::now())
                         <button class="pro_detail_btn">已结束</button><button class="pro_detail_btn cccbg ml15 follow_btn">收藏商品</button>
                    @else
                        <button class="pro_detail_btn orangebg">加入购物车</button><button class="pro_detail_btn cccbg ml15 follow_btn">收藏商品</button>
                    @endif
                @endif
				
			</div>
		</div>
	</div>

        <div class="w1200" style="margin-top: 80px;">
            <h2 class="History_offo" style="font-weight: bold; padding-left: 20px">商家推荐</h2>
            {{--<div >--}}
                {{--<ul class="">--}}
                    {{--<li style="margin-left:25px;text-align:center;"></li>--}}
                {{--</ul>--}}
            {{--</div>--}}
        <ul class="quoteList" style="overflow: hidden; height: auto;">
            <li style="background-color: #F7F7F7">
                <span style="width: 18%;">报价日期</span>

                <span style="width: 18%;">品牌</span>
                <span style="width: 20%;">规格</span>
                {{--<span style="width: 12%;">数量</span>--}}
                <span style="width: 15%;">单价（元）</span>
                <span style="width: 16%;">发货地址</span>
                <span style="width: 13%;">联系人</span>
            </li>
            @foreach($quoteList as $vo)
                <li>
                    <span style="width:18%" class="ovhwp" title="{{$vo['add_time']}}">{{$vo['add_time']}}</span>
                    <span style="width:18%;" class="ovhwp" title="{{$vo['brand_name']}}">{{$vo['brand_name']}}</span>
                    <span style="width:20%;" class="ovhwp" title="{{$vo['goods_content'].' '.$vo['simple_goods_name']}}"><a style="color:#00a1e9;" href="/goodsDetail/{{$vo['id']}}/{{$vo['shop_id']}}">{{$vo['goods_content'].' '.$vo['simple_goods_name']}}</a></span>
{{--                    <span style="width:12%">@if($vo['goods_number'] < 0) 0{{$good_info['unit_name']}} @else {{$vo['goods_number']}}{{$good_info['unit_name']}} @endif</span>--}}
                    <span style="width:15%" class="ovhwp" title="￥{{$vo['shop_price']}}/{{$good_info['unit_name']}}">￥{{$vo['shop_price']}}/{{$good_info['unit_name']}}</span>
                    <span style="width:16%" class="ovhwp" title="{{$vo['delivery_place']}}">{{$vo['delivery_place']}}</span>
                    <span style="width:13%" class="ovhwp" title="{{$vo['salesman']}}/{{$vo['contact_info']}}">{{$vo['salesman']}}/{{$vo['contact_info']}}</span>
                </li>
            @endforeach
        </ul>
        </div>


        <div class="w1200" style="margin-top: 20px;">
            <div class="History_offo">
                <ul class="HistoryLi">
                    <li style="margin-left:25px;text-align:center;" class="titlecurr"><h2 style="font-weight: bold;">历史报价</h2></li>
                    <li style="text-align: center;"><h2 style="font-weight: bold;">商品详情</h2></li>
                </ul>
            </div>
        <div>
    <ul class="proitemlist">
        <li>
            <ul class="History-product-list br1">
                <li style="background-color: #cccccc">
                    <span style="width: 18%;">报价日期</span>
                    <span style="width: 16%;">种类</span>
                    <span style="width:15%">品牌</span>
                    <span style="width:17%">规格</span>
                    {{--<span style="width: 11%;">数量</span>--}}
                    <span style="width: 11%;">单价（元)</span>
                    <span style="width:10%">发货地址</span>
                    <span style="width: 13%;">联系人</span>
                </li>
                @foreach($goodsList as $vo)
                    <li>
                        <span style="width:18%" class="ovhwp" title="{{$vo['add_time']}}">{{$vo['add_time']}}</span>
                        <span style="width:16%" class="ovhwp" title="{{$vo['cat_top_name']}}">{{$vo['cat_top_name']}}</span>
                        <span style="width:15%" class="ovhwp" title="{{$vo['brand_name']}}">{{$vo['brand_name']}}</span>
                        <span style="width:17%;color:#00a1e9;" class="ovhwp" title="{{$vo['goods_content'].' '.$vo['simple_goods_name']}}">{{$vo['goods_content'].' '.$vo['simple_goods_name']}}</span>
                        {{--<span style="width:11%">@if($vo['goods_number'] < 0) 0 @else {{$vo['goods_number']}} @endif{{$vo['unit_name']}}</span>--}}
                        <span style="width:11%">￥{{$vo['shop_price']}}/{{$vo['unit_name']}}</span>
                        <span style="width:10%" class="ovhwp" title="{{$vo['delivery_place']}}">{{$vo['delivery_place']}}</span>
                        <span style="width:13%" class="ovhwp" title="{{$vo['salesman']}}/{{$vo['contact_info']}}">{{$vo['salesman']}}/{{$vo['contact_info']}}</span>
                    </li>
                @endforeach
            </ul>
            {{--<div class="news_pages">--}}
                {{--<ul id="page" class="pagination">--}}

                {{--</ul>--}}
            {{--</div>--}}
        </li>
        <li style="display: none"><ul class="History-product-list br1">
                <li>
                    {!! $good_info['goods_desc'] !!}

                </li>
            </ul>
        </li>
    </ul>
</div>
            <div class="clearfix whitebg ovh mt10" style="font-size: 0;"></div>
        </div>

            {{--<ul class="Self-product-list">--}}
                {{--<li><span class="num_bg1">报价日期</span><span>品牌</span><span>种类</span><span>商品名称</span><span>数量（kg）</span><span>单价（元/kg）</span><span>发货地址</span><span>联系人</span></li>--}}
                {{--@foreach($goodsList as $vo)--}}
                    {{--<li style="width:1200px;height: 60px;clear:both;"><span>{{$vo['add_time']}}</span><span>{{$vo['brand_name']}}</span><span class="ovh">{{$vo['cat_name']}}</span><span >{{$vo['goods_name']}}</span><span>{{$vo['goods_number']}}</span><span>{{$vo['shop_price']}}</span><span>{{$vo['delivery_place']}}</span><span>{{$vo['salesman']}}/{{$vo['contact_info']}}</span></li>--}}
                {{--@endforeach--}}
            {{--</ul>--}}
            <!--页码-->

@endsection

@section('bottom_js')
	<script>
        $(".shop_num_reduce").click(function(){
            var number = parseInt($(".pur_num").val());
            var packing_spec = parseInt("{{$good_info['packing_spec']}}");
            var min_limit = parseInt("{{$good_info['min_limit']}}");
            if(number<=packing_spec || number<=min_limit){
                $(".pur_num").val(number);
                $.msg.error('已经是最低的购买数量了');
            }else{
                $(".pur_num").val(number-packing_spec);
            }
        });

        $(".shop_num_plus").click(function(){
            var number = parseInt($(".pur_num").val());
            var packing_spec = parseInt("{{$good_info['packing_spec']}}");
            var can_num = parseInt($(".pur_num").attr('can_num'));
            if(number + packing_spec > can_num){
                var _count = can_num%packing_spec;
                if(_count>0){
                    $(".pur_num").val(can_num - _count);
                }else{
                    $(".pur_num").val(can_num);
                }
                $.msg.error('不能大于可售');
            }else{
                $(".pur_num").val(number+packing_spec);
            }
        });

        $(".orangebg").click(function(){
            var userId = "{{session('_web_user_id')}}";
            if(userId==""){
                layer.confirm('请先登录再进行操作。', {
                    btn: ['去登陆','再看看'] //按钮
                }, function(){
                    window.location.href='/login';
                }, function(){

                });
                return false;
            }
            var id = "{{$good_info['id']}}";
            var number =  $(".pur_num").val();
            $.post("/cart",{'id':id,'number':number},function(res){
                if(res.code==1){
                    var cart_count = res.data;
                    $(".pro_cart_num").text(cart_count);
                    $.msg.success(res.msg,1);
                }else{
                    $.msg.alert(res.msg);
                }
            },"json");
        });

        $(".follow_btn").click(function(){
            var userId = "{{session('_web_user_id')}}";
            if(userId==""){
                layer.confirm('请先登录再进行操作。', {
                    btn: ['去登陆','再看看'] //按钮
                }, function(){
                    window.location.href='/login';
                }, function(){

                });
                return false;
            }
            var goods_id = "{{$good_info['goods_id']}}";
            $.post("/addCollectGoods",{'id':goods_id},function(res){
                if(res.code==1){
                    $.msg.success("收藏成功",1);
                    window.location.reload();
                }else{
                    $.msg.alert(res.msg);
                }
            },"json");
        });

        paginate();
        function paginate(){
            layui.use(['laypage'], function() {
                var laypage = layui.laypage;
                laypage.render({
                    elem: 'page' //注意，这里的 test1 是 ID，不用加 # 号
                    , count: "{{$total}}" //数据总数，从服务端得到
                    , limit: "{{$pageSize}}"   //每页显示的条数
                    , curr: "{{$currpage}}"  //当前页
                    , prev: "上一页"
                    , next: "下一页"
                    , theme: "#88be51"
                    , jump: function (obj, first) {
                        if (!first) {
                            window.location.href="/goodsDetail/{{$id}}/{{$shop_id}}?currpage="+obj.curr;
                        }
                    }
                });
            });
        }
	</script>

@endsection

