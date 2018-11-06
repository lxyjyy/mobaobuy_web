@extends(themePath('.','web').'web.include.layouts.home')
@section('title', '抢购详情')
@section('css')
	<link rel="stylesheet" type="text/css" href="{{asset('plugs/layui/css/layui.css')}}" />
	{{--<link rel="stylesheet" href="{{asset(themePath('/').'css/global.css')}}" />--}}
	<link rel="stylesheet" href="css/global.css" />
	<link rel="stylesheet" href="/css/index.css" />
	<style>
		.nav-div .nav-cate .ass_menu {display: none;}
		.top-search-div .search-div .logo{
			background:none;
		}
	</style>

@endsection
@section('js')
	{{--<script src="{{asset('plugs/layui/layui.all.js')}}"></script>--}}
	{{--<script src="{{asset(themePath('/', 'web').'js/index.js')}}" ></script>--}}
	<script type="text/javascript">
        $(function(){
            $(".nav-cate").hover(function(){
                $(this).children('.ass_menu').toggle();// 鼠标悬浮时触发
            });

            function Remaine_time(ntime,nday,nhour,nminute,nsecond){
                var intDiff=parseInt(ntime);//总秒数
                window.setInterval(function(){
                    var day=0,hour=0,minute=0,second=0;
                    if(intDiff>0){
                        day=Math.floor(intDiff/(60*60*24));
                        hour=Math.floor(intDiff/(60*60))-(day*24);
                        minute=Math.floor(intDiff/60)-(day*24*60)-(hour*60);
                        second=Math.floor(intDiff)-(day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                    }
                    if(hour<=9)hour='0'+hour;
                    if(minute<=9)minute='0'+minute;
                    if(second<=9)second='0'+second;
                    nday.html(day+"天");
                    nhour.html('<s></s>'+hour);
                    nminute.html('<s></s>'+minute);
                    nsecond.html('<s></s>'+second);
                    intDiff--;
                },1000)

            }
            Remaine_time(10800,$('.day_show1'),$('.hour_show1'),$('.minute_show1'),$('.second_show1'));

            $('.nav').hover(function(){
                $('.ass_menu').toggle();
            })
//导航
            $('.ass_menu li').hover(function(){
                $(this).find('.ass_fn').toggle();
            })


            //规格
            var thisMul = $('.shop_num_plus').attr('pid');

            //最小可购数
            var min_limit = $('#min_limit').attr('min-limit');
            //可售数量
            var canSell = $('.shop_num_plus').attr('canSell');
            var NumNew;
            $(document).delegate('.shop_num_plus','click',function(){
                var ipts=$(this).siblings('input.Bidders_record_text');
                var iptsVal=ipts.attr('value');
                if(Number(ipts.val())+Number(thisMul)>Number(canSell)){
                    alert('不能大于可售');
                    return;
                }else{
                    NumNew=Number(ipts.val())+Number(thisMul);
                    ipts.val(Number(NumNew));
                }
            });

            $(document).delegate('.shop_num_reduce','click',function(){
                var ipts=$(this).siblings('input.Bidders_record_text');
                var iptsVal=ipts.attr('value');
                if (Number(ipts.val())-Number(thisMul)<Number(min_limit)) {
                    alert('已经是最低的购买数量了');
                    return;
                }else{
                    NumNew=Number(ipts.val())-Number(thisMul);
                    ipts.val(Number(NumNew));
                }


            })
        })

        function collectGoods(obj){
            var id = $(obj).attr('id');
            if(id>0){
                $.ajax({
                    url: "/addCollectGoods",
                    dataType: "json",
                    data:{
                        'id': id
                    },
                    type:"POST",
                    success:function(data){
                        console.log(data);
                        return;
                        if(data.code){
                            $.msg.alert('收藏成功');
                        }else{
                            $.msg.alert(data.msg);
                        }
                    }
                })
            }
        }

        function toBalance(goodsId,activityId){
            var goodsNum = $('#goodsNum').val();
            var activityIdEncrypt = $('#activityId').val();
            $.ajax({
                url: "/buyLimitToBalance",
                dataType: "json",
                data: {
                    'goodsId' : goodsId,
                    'activityId' : activityId,
                    'goodsNum' : goodsNum
                },
                type: "POST",
                success: function(data){
                    // console.log(data);return;
                    if(data.code){
                        // console.log(data);
                        window.location.href='/confirmOrder/'+activityIdEncrypt;
                    }else{
                        $.msg.alert(data.msg);
                    }
                }
            })
        }
	</script>
@endsection
@section('content')

	<div class="clearfix">
		<div class="w1200 pr ovh">
			<div class="crumbs mt5">当前位置：<a href="/">产品列表</a> &gt; <a href="/subject/list/56/page/1.html">产品详情</a> &gt;<span class="gray"></span></div>
			<div class="pro_chart mt5">
				<h1 class="pro_chart_title">
					商品价格走势
				</h1>
				<div class="pro_chart_img">

				</div>
				<div><span class="pro_chart_opert follow">收藏</span><span class="pro_chart_opert share ml20"></span></div>
			</div>
			<div class="fl ml35 mt5">
				<h1 class="fwb fs16">{{$goodsInfo['goods_name']}}</h1>
				<span class="red mt5 db"></span>
				<style type="text/css">
					.Time_limit{height:46px;background: url(/img/limit_time.png)no-repeat;}
					.xs_ms{margin-left:15px;width:95px;height:46px;line-height: 46px;background: url(/img/xs_ms.png)no-repeat 0px 16px;font-size: 18px;color: #fff; padding-left: 20px;}
					.time_mode{margin-top:11px;width: 24px;height: 24px;border-radius:6px;background-color: #323232;overflow: hidden;line-height: 24px;text-align: center;color: #fff;}
					.Surplus_time{float:right;width:200px;height: 46px;line-height: 46px;margin-right: 20px;}
				</style>
				<div class="Time_limit mt3">
					<span class="xs_ms fl">限时秒杀</span>
					<div class="Surplus_time" >
						<span class="white fl" >剩余时间</span>
						<span class="time_mode fl ml10 hour_show1">00</span>
						<span class="fl ml5">:</span>
						<span class="time_mode fl ml5 minute_show1">00</span>
						<span class="fl ml5">:</span>
						<span class="time_mode fl ml5 second_show1">00</span>
					</div>
				</div>
				<style type="text/css">
					.price_market{width:635px;height: 109px;background-color: #f4f4f4;}
					.limit_line{float:left;width: 1px;height: 45px;margin-top:32px;background-color: #cccccc;}

				</style>
				<div class="price_market">
					<div class="fl ml20">
						<div class="mt15" style="width: 500px;"><span class="fs14" style="letter-spacing: 10px;">秒杀价</span><span class="ml20 fs18"><font class="fwb red fs22">￥{{$goodsInfo['activity_price']}}</font>/kg</span></div>
						<div class="mt20"><span class="fs14" style="letter-spacing: 10px;">市场价</span><span class="ml25">￥{{$goodsInfo['market_price']}}/kg</span></div>
					</div>
					<div class="limit_line"></div>
					<div class="tac ovh">
						<span class="db mt35">累计售出</span>
						<span class="db red">{{$goodsInfo['activity_num'] - $goodsInfo['available_quantity']}} kg</span>
					</div>
				</div>
				<div class="pro_detail">
					<span class="ml15 pro_detail_title letter-space fl">库存</span><span  class="pro_value">{{$goodsInfo['activity_num']}}kg</span><span class="fl ">包装规格</span><span  class="ml35 fl">{{$goodsInfo['packing_spec']}}kg</span>
				</div>
				<div class="pro_detail">
					<span class="ml15 letter-space fl">批号</span><span  class="pro_value">{{$goodsInfo['goods_sn']}}</span><span class="fl letter-space">品牌</span><span  class="ml5 fl">{{$goodsInfo['brand_name']}}</span>
				</div>
				<div class="pro_detail">
					<!-- <span class="ml15 pro_detail_title fl">产品含量</span><span  class="pro_value">50%</span> -->
				</div>
				<div class="pro_detail bd1"></div>
				<div class="pro_detail">

					<span class="ml15 fl pro_detail_title" style="letter-spacing: 2px; height: 28px;line-height: 28px;">采  购  量</span><div class="pur_volume ml15"><span class="pur bbright shop_num_reduce" pid="{{$goodsInfo['packing_spec']}}" canSell="{{$goodsInfo['activity_num'] - $goodsInfo['available_quantity']}}">-</span><input type="text" class="pur_num Bidders_record_text" value="{{$goodsInfo['min_limit']}}" id="goodsNum" /><span id="min_limit" min-limit="{{$goodsInfo['min_limit']}}" class="pur bbleft shop_num_plus" pid="{{$goodsInfo['packing_spec']}}" canSell="{{$goodsInfo['activity_num'] - $goodsInfo['available_quantity']}}">+</span></div>

				</div>

				<div class="mt30" style="margin-left: 115px;">
					<button class="pro_detail_btn redbg" onclick="toBalance({{$goodsInfo['id']}},{{$goodsInfo['activity_id']}})">立即下单</button><button class="pro_detail_btn cccbg ml15 follow_btn" id="{{$goodsInfo['id']}}" aid="" onClick="collectGoods(this)">收藏商品</button>
				</div>
				<input type="hidden" name="" value="{{encrypt($goodsInfo['activity_id'])}}" id="activityId" />
			</div>
		</div>
	</div>
@endsection
@section('bottom_js')

@endsection
