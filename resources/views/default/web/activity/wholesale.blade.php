@extends(themePath('.','web').'web.include.layouts.home')
@section('title', '集采拼团')
@section('css')
	<link rel="stylesheet" type="text/css" href="{{asset('plugs/layui/css/layui.css')}}" />
	<link rel="stylesheet" href="css/index.css" />
	<style>
		.nav-div .nav-cate .ass_menu {display: none;}
		.top-search-div .search-div .logo{background:none;}
		.bottom_time{width: 154px;color: #666;float: left;text-align: left;}
		.bottom_btn {width: 96px;float: right;text-align: center;height: 38px;border-radius: 5px;line-height: 35px;}
	</style>

@endsection
@section('js')
	<script src="{{asset(themePath('/', 'web').'js/index.js')}}" ></script>
	<script type="text/javascript">
		$(function(){
			$(".nav-cate").hover(function(){
				$(this).children('.ass_menu').toggle();// 鼠标悬浮时触发
			});
		})
	</script>
@endsection
@section('content')
	<div class="limit_bg">
		<div class="ms_title"></div>
		{{--<div class="limit_date">2018年10月1日-2018年10月31日</div>--}}
		
		
		<div class="w1200 ovh">
		<ul class="ms_list">
			@if(empty($wholesaleInfo))
				无集采拼图活动
			@else
				@foreach($wholesaleInfo as $v)
				<li>
					<div class="ms_list_center">
						<div class="ovh">
							<h1 class="fs24 fl" style="height: 36px;">{{$v['goods_name']}}</h1><span class="fr pt10 ovh di"><font class="orange">{{$v['click_count']}}</font>次浏览</span>
						</div>
						<div class="ovh mt30">
							<div class="mx_addr fl" style="width: 117px;">{{$v['shop_name']}}</div><div class="mx_addr fl ml15" style="width: 117px;">{{$v['num']}}kg</div>
						</div>
						<div class="ovh mt20 ">
							<div class="mx_progress"><div class="mx_progress_com"></div></div><span class="fl fs16 ml10 gray">已参与{{$v['partake_quantity']}}kg</span>
						</div>
						<!-- <div class="tac mt40 ovh"><span class="addr_dw">上海  浦东新区</span></div> -->
						<div class="mx_price"><font class="gray">单价最高</font> <font class="orange fs24">￥{{$v['price']}}</font>/kg</div>

						@if($v['is_over'])
							<div class="graybg">
								<div class="bottom_time"><p>距离结束：</p><span class="orange count-down-text">0天0小时0分钟0秒</span></div>
								<div class="bottom_btn b1b1b1bg fs16 white cp">已结束</div>
							</div>
						@elseif($v['is_soon'])
							<div class="graybg count-down" data-endtime="{{$v['begin_time']}}">
								<div class="bottom_time"><p>距离开始：</p><span class="orange count-down-text">0天0小时0分钟0秒</span></div>
								<div class="bottom_btn b1b1b1bg fs16 white cp">敬请期待</div>
							</div>
						@else
							<div class="graybg count-down" data-endtime="{{$v['end_time']}}">
								<div class="bottom_time"><p>距离结束：</p><span class="orange count-down-text">0天0小时0分钟0秒</span></div>
								<a href="/wholesale/detail/{{encrypt($v['id'])}}"><div class="bottom_btn redbg fs16 white cp">参与集采</div></a>
							</div>
						@endif
					</div>
				</li>
				@endforeach
			@endif
		</ul>
	
	</div>
	<div class="w1200 redbg ovh" style="margin-bottom: 30px;">
		
		<div class="Rules_activity">集采规则</div>
		<ul class="Rules_text">
			<li>1.本活动对所有会员开放</li>
			<li>2.秒杀订单需要在30分钟内完成支付，逾期未付款，系统将自动取消订单</li>
			<li>3.如有任何疑问，请联系在线客服或拨打免费服务热线：400-100-1234</li>
		</ul>
	</div>
	</div>
@endsection


