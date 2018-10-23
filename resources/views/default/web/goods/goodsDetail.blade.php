<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>产品详情</title>
	@include(themePath('.','web').'web.include.partials.base')
	<link rel="stylesheet" type="text/css" href="{{asset(themePath('/').'plugs/layui/css/layui.css')}}" />
	<style>
		.Self-product-list li span{width:14%;}
		.news_pages ul.pagination {text-align: center;}
	</style>
    <script src="{{asset(themePath('/').'plugs/layui/layui.js')}}" ></script>
	<script type="text/javascript">
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
        })

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
                            window.location.href="/goodsDetail?currpage="+obj.curr+"&shop_id={{$shop_id}}&id={{$id}}";
                        }
                    }
                });
            });
        }


	</script>
</head>
<body>
<div class="clearfix height35 lh35 graybg">
	<div class="w1200">
		<div class="top_til">
			<a>登录</a>
			<a>注册</a>
			<a>帮助中心</a>
			<a>会员中心</a>
			<a>快速下单</a><span class="top_tel">400-000-0000</span></div>
	</div>
</div>
<div class="header">
	<div class="w1200">
		<div class="clearfix">
			<div class="logo" style="margin-top: 38px;"></div>
			<div class="index_search fl mt40">
				<input type="text" class="index_search_text fl" placeholder="请输入关键词、类别进行搜索"/><input type="button" class="index_search_btn white fs16 fl" value="搜 索"/>
				<a class="contact_artificial tac br1 db fl ml10">联系人工找货</a>
				<div class="hot_search_m">热门推荐：
					<a href="#.html" target="_blank">7000F泰国PTT</a>
					<a href="#.html" target="_blank">218WJ</a>
					<a href="#.html" target="_blank">7000</a>
					<a href="#.html" target="_blank">218w</a>
					<a href="#.html" target="_blank">7000f</a>
					<a href="#.html" target="_blank">9001台湾台塑</a>
				</div>
			</div>

			<a class="shopping_cart mt40 tac"><span class="fl ml25">我的购物车</span><i class="shopping_img fl"><img src="/images/cart_icon.png"/></i><span class="pro_cart_num white">{{$cart_count}}</span></a>
		</div>
		<div class="clearfix">

			<div class="nav">
				<div class="fication_menu">原料分类</div>
				<ul class="ass_menu" style="display: none;">
					<li><span class="ass_title">维生素</span> <span class="ass_list-right ass_list-right_icon ass_list-right_icon01"></span>
						<div class="ass_fn whitebg">
							<ul class="ass_fn_list">
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VE</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VD3</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">烟酰胺/烟酸</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
							</ul>
						</div>
					</li>
					<li><span class="ass_title">维生素</span> <span class="ass_list-right ass_list-right_icon ass_list-right_icon01"></span>
						<div class="ass_fn whitebg">
							<ul class="ass_fn_list">
								<li><h1 class="fn_title fl">VB</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VE</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VD3</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">烟酰胺/烟酸</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
							</ul>
						</div>
					</li>
					<li><span class="ass_title">维生素</span> <span class="ass_list-right ass_list-right_icon ass_list-right_icon01"></span>
						<div class="ass_fn whitebg">
							<ul class="ass_fn_list">
								<li><h1 class="fn_title fl">VC</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VE</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VD3</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">烟酰胺/烟酸</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
							</ul>
						</div>
					</li>
					<li><span class="ass_title">维生素</span> <span class="ass_list-right ass_list-right_icon ass_list-right_icon01"></span>
						<div class="ass_fn whitebg">
							<ul class="ass_fn_list">
								<li><h1 class="fn_title fl">VD</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VE</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VD3</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">烟酰胺/烟酸</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
							</ul>
						</div>
					</li>
					<li><span class="ass_title">维生素</span> <span class="ass_list-right ass_list-right_icon ass_list-right_icon01"></span>
						<div class="ass_fn whitebg">
							<ul class="ass_fn_list">
								<li><h1 class="fn_title fl">VE</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VE</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VD3</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">烟酰胺/烟酸</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
							</ul>
						</div>
					</li>
					<li><span class="ass_title">维生素</span> <span class="ass_list-right ass_list-right_icon ass_list-right_icon01"></span>
						<div class="ass_fn whitebg">
							<ul class="ass_fn_list">
								<li><h1 class="fn_title fl">VF</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VE</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VD3</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">VA</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
								<li><h1 class="fn_title fl">烟酰胺/烟酸</h1><div class="ass_fn_list_that ml35 ovh fl"><span>常规VA</span><span>VA棕榈酸酯</span><span>常规VA</span><span>VA醋酸酯</span><span>常规VA</span><span>常规VA</span></div></li>
							</ul>
						</div>
					</li>
				</ul>

			</div>

			<div class="menu">
				<ul><li>首页</li><li>报价列表</li><li>拼团活动</li><li>限时秒杀</li><li>资讯中心</li><li>产品列表</li><li>帮助中心</li></ul>
			</div>
		</div>
	</div>
</div>
<div class="clearfix">
	<div class="w1200 pr ovh">
		<div class="crumbs mt5">当前位置：<a href="/">产品列表</a> &gt; <a href="/subject/list/56/page/1.html">产品详情</a> &gt;<span class="gray">{{$good_info['goods_name']}}</span></div>
		<style type="text/css">
			.Self-product-list li span{width: 13%;float: left;text-align: center;}
			.pro_chart{float:left;width: 528px; }
			.pro_chart_title{line-height: 70px;text-align: center;font-size: 18px;border: 1px solid #DEDEDE;border-bottom: 1px solid #DEDEDE;}
			.pro_chart_img{height: 355px;border: 1px solid #DEDEDE;}
			.pro_price{width: 635px;height: 56px;line-height: 56px;overflow: hidden;}
			.pro_detail{overflow: hidden;margin-top: 20px;}
			.pro_price_dj{width: 493px;border-right: 1px solid #DEDEDE;height: 45px;line-height: 45px;margin-top: 5px;display: block;}
			.start_amount{float: left;width: 141px;line-height:20px;text-align: center;margin-top: 7px;}
			.pro_value{width: 270px;margin-left: 15px;float: left;}
			.letter-space{letter-spacing: 30px;}
			.pro_detail_title{width: 88px;}
			.pro_chart_opert{width: 50px;height: 18px;display: inline-block;color: #666;padding-left: 23px;margin-top: 10px;}
			.follow{background: url(/images/pro_detail_icon.png)no-repeat 0px -61px;}
			.share{background: url(/images/pro_detail_icon.png)no-repeat -124px 3px;}
			.follow_btn{background: #b1b1b1 url(/images/pro_detail_icon.png)no-repeat 20px 14px;}
		</style>
		<div class="pro_chart mt5">
			<h1 class="pro_chart_title">
				商品价格走势
			</h1>
			<div class="pro_chart_img">

			</div>

		</div>
		<div class="fl ml35 mt5">
			<h1 class="fwb fs16">{{$good_info['goods_name']}}</h1>
			<span class="red mt5 db"></span>
			<div class="pro_price f4bg mt10">
				<div class="pro_price_dj fl"><span class="ml15 letter-space">单价</span><span class="ml15 fwb"><font class="fs22 red">{{$good_info['shop_price']}}</font>/kg</span></div>

			</div>
			<div class="pro_detail">
				<span class="ml15 pro_detail_title letter-space fl">库存</span><span  class="pro_value">{{$good_info['goods_number']}}{{$good_info['unit_name']}}</span><span class="fl ">包装规格</span><span  class="ml35 fl">{{$good_info['packing_spec']}}{{$good_info['packing_unit']}}</span>
			</div>
			<div class="pro_detail">
				<span class="ml15 letter-space fl">编号</span><span  class="pro_value">{{$good_info['goods_sn']}}</span><span class="fl letter-space">品牌</span><span  class="ml5 fl">{{$good_info['brand_name']}}</span>
			</div>
			<div class="pro_detail">
				<span class="ml15 pro_detail_title fl">产品属性</span>
				@foreach($good_info['goods_attr'] as $vo)
					<span style="width:100px;color:#88be51;"  class="pro_value">{{$vo}}</span>
				@endforeach
			</div>
			<div class="pro_detail bd1"></div>
			<div class="pro_detail">
				<style type="text/css">
					.pur_volume{float:left;border: 1px solid #DEDEDE; box-sizing:border-box;}
					.pur_volume .pur{cursor:pointer;width: 26px;text-align: center;float: left;height: 28px;line-height: 28px;background-color: #fafafa;box-sizing:border-box;}
					.pur_num{float:left;width: 50px;height: 28px;line-height: 28px;text-align: center;border: none;}
					.pro_detail_btn{cursor:pointer;width: 140px;height: 42px;line-height: 42px;border: none;font-size:16px;color: #fff;border-radius:3px;}
				</style>
				<span class="ml15 fl pro_detail_title" style="letter-spacing: 2px; height: 28px;line-height: 28px;">采  购  量</span><div class="pur_volume ml15"><span class="pur bbright">-</span><input type="text" class="pur_num" value="1" /><span class="pur bbleft">+</span></div>

			</div>

			<div class="mt30" style="margin-left: 115px;">
				<button class="pro_detail_btn orangebg">加入购物车</button><button class="pro_detail_btn cccbg ml15 follow_btn">关注商品</button>
			</div>
		</div>

	</div>
	<div class="w1200" style="margin-top: 80px;">
		<style type="text/css">
			.History_offo{height: 40px;line-height: 40px;border-bottom: 2px solid #75b335;background-color: #f0f0f0;box-sizing: border-box;}
			.History_offo h1{background-color: #75b335;text-align: center;width: 106px;color: #fff;font-size: 16px;}

			.History-product-list{margin-top: 10px;}

			.History-product-list li span{width: 14.2%;float: left;text-align: center;}
			.History-product-list li{height: 43px;line-height: 43px;background-color: #fff;border-bottom: 1px solid #CCCCCC;}
			.History-product-list li:first-child{height: 40px;line-height: 40px;background-color: #cccccc;}
			.History-product-list li:last-child{border-bottom: none;}
		</style>
		<div class="History_offo">
			<h1>历史报价</h1>
		</div>

		<ul class="Self-product-list">

			<li><span class="num_bg1">报价日期</span><span>品牌</span><span>种类</span><span>商品名称</span><span>数量（公斤）</span><span>单价（元/公斤）</span><span>发货地址</span></li>
			@foreach($goodsList as $vo)
				<li><span>{{$vo['add_time']}}</span><span>{{$vo['brand_name']}}</span><span class="ovh">{{$vo['cat_name']}}</span><span ><a class="orange" href="/goodsDetail?goods_id={{$vo['goods_id']}}&shop_id={{$vo['shop_id']}}">{{$vo['goods_name']}}</a></span><span>{{$vo['goods_number']}}</span><span>{{$vo['shop_price']}}</span><span>{{$vo['delivery_place']}}</span></li>
			@endforeach
		</ul>
		<!--页码-->
		<div class="news_pages">
			<ul id="page" class="pagination">

			</ul>
		</div>
	</div>
</div>
<script>
	$(".bbright").click(function(){
	    var number = parseInt($(".pur_num").val());
	    var packing_spec = parseInt("{{$good_info['packing_spec']}}");
	    if(number<packing_spec){
            $(".pur_num").val(number);
		}else{
            $(".pur_num").val(number-packing_spec);
		}
	});

    $(".bbleft").click(function(){
        var number = parseInt($(".pur_num").val());
        var packing_spec = parseInt("{{$good_info['packing_spec']}}");
        $(".pur_num").val(number+packing_spec);
    });

    $(".orangebg").click(function(){
        var id = "{{$good_info['id']}}";
        var number =  $(".pur_num").val();
        $.post("/cart",{'id':id,'number':number},function(res){
            if(res.code==1){
                var cart_count = res.data;
                $(".pro_cart_num").text(cart_count);
                $.msg.success(res.msg);
            }else{
                $.msg.alert(res.msg);
            }
        },"json");
    });
</script>
<!--底部-->
@include(themePath('.','web').'web.include.partials.footer_service')
@include(themePath('.','web').'web.include.partials.footer_new')
@include(themePath('.','web').'web.include.partials.copyright')
</body>
</html>