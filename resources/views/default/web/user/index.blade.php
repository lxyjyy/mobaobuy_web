@extends(themePath('.','web').'web.include.layouts.member')
@section('title','会员中心')
<style type="text/css">
	.member_index_right{width: 968px;box-sizing: border-box; height: auto;}
	.whitebg{background: #FFFFFF;}
	.fl{float:left;}
	.ml15{margin-left:15px;}
	.br1{border: 1px solid #DEDEDE;}
	.pr {position:relative; }.pa{position: absolute;}
	.member_top_per{margin: 27px auto;overflow: hidden;}
.member_top_per li{width:50%;float: left;box-sizing: border-box;}
.ml30{margin-left:30px;}
.ovh{overflow: hidden;}
.mt5{margin-top:5px;}
.db{display:block;}
.member_Name{width: 75px;height: 20px;line-height: 20px;border-radius: 25px;color: #75b335;}
.member_Name_border{border: 1px solid #75b335;}
.member_Name_stute{width: 290px;margin: 47px auto;}
.tac{text-align:center !important;}
.mt10{margin-top:10px;}
.mt20{margin-top:20px;}
.gray,a.gray,a.gray:hover{color:#aaa;}
.mem_stute{height: 26px;}
.Pend_payment{background: url(../img/mem_stute.png)no-repeat 0px 0px;}
.cp{cursor:pointer;}
.ml50{margin-left:50px;}
.orange,a.orange,a.orange:hover{color:#ff6600;}
.ml10{margin-left:10px;}
.fr{float:right;}
.Pend_goods{background: url(../img/mem_stute.png)no-repeat 0px -28px;}
.mt15{margin-top:15px;}
.member_right_title_icon{background: url(../img/member_title_icon.png)no-repeat 0px 6px;}
.mt25{margin-top:25px;}
.ml30{margin-left:30px;}
.pl20{padding-left:20px;}
.fs16{font-size:16px;}
.fr{float:right;}
.colr_blu{color: #218bca;}
.mr35{margin-right:35px;}
.order_record{width: 905px;margin: 20px auto 35px;border: 1px solid #DEDEDE;}
.order_record tr td{text-align: center;border: 1px solid #DEDEDE;height: 45px;line-height: 45px;}
.order_record tr:first-child{background-color: #F4F4F4;height: 50px;line-height: 50px;border: none;}
.or_re_btn{width:90px;margin:0 auto;height:30px;line-height:30px;color:#fff;display:block;border-radius:3px;background-color: #75b335;}
</style>
@section('content')
<!--会员-->
<!-- <div class="clearfix mt25">
	    <div class="w1200"> -->
	    	<!--左边部分-->
		
			<div class="member_index_right whitebg fl ml15 br1 pr" style="margin-left: -31px;margin-top:20px;">
				<ul class="member_top_per">
					<li style="border-right:1px solid #DEDEDE;">
						<div class="fl ml30"><img src="img/per_logo.png"/></div>
						<div class="fl ml15 ovh">
							<span class="mt5 db">{{session('_web_user')['nick_name']}}</span>
							<div class="member_Name member_Name_border tac mt10">
								@if(session('_curr_deputy_user')['is_firm'] && session('_curr_deputy_user')['is_self'] ==1)
									{{trans('home.enterprise')}}
								@elseif(session('_curr_deputy_user')['is_firm'] && session('_curr_deputy_user')['is_self'] ==0)
									{{trans('home.staff')}} @elseif(empty($memberInfo['userRealInfo']))
									<a href="/account/userRealInfo"><style>.member_Name{width:96px;}</style>{{trans('home.go_real_name')}}</a>
								@elseif($memberInfo['userRealInfo']['review_status'] == 1)
									{{trans('home.header_personal')}}
								@elseif($memberInfo['userRealInfo']['review_status'] == 0)
									{{trans('home.wait_audit')}}
								@elseif($memberInfo['userRealInfo']['review_status'] == 2)
									{{trans('home.audit_failed')}}
								@endif</div>
							<span class="mt20 gray db">{{trans('home.member_welcome')}}！</span>
						</div>
					</li>
					<li>
						<div class="member_Name_stute">
							<a href="/order/list?tab_code=waitPay">
								<div class="fl mem_stute Pend_payment cp">
									<span class="ml50">{{trans('home.wait_pay')}}</span>
									<span class="green ml10">{{$memberInfo['nPayOrderTotalCount']}}</span>
								</div>
							</a>
							<a href="/order/list?tab_code=waitPay">
								<div class="fr mem_stute Pend_goods cp">
									<span class="ml50">{{trans('home.paid')}}</span>
									<span class="green ml10">{{$memberInfo['yPayOrderTotalCount']}}</span>
								</div>
							</a>
						</div>
					</li>
				</ul>
			   
			</div>
			<!--我的订单-->
			<div class="member_index_right whitebg fl ml15 br1 pr mt15" style="margin-left: -31px;">
				<!--标题-->
				<h1 class="member_right_title_icon mt25 ml30 pl20 fs16" style="width:910px;">
					<span>{{trans('home.my_order')}}</span>
					<span class="fr colr_blu mr35">
						<a href="/order/list">{{trans('home.view_all_orders')}}>></a>
					</span>
				</h1>
			
			<table class="order_record">
				<tr>
					<th>{{trans('home.order_number')}}</th>
					<th>{{trans('home.shop_name')}}</th>
					<th>{{trans('home.order_amount')}}</th>
					<th>{{trans('home.status')}}</th>
				</tr>

				@if(empty($memberInfo['orderInfo']))
					<tr><td colspan="5">{{trans('home.no_order')}}</td></tr>
				@else
					@foreach($memberInfo['orderInfo'] as $v)
					<tr>
						<td>{{$v['order_sn']}}</td>
						<td>{{$v['shop_name']}}</td>
						<td>￥{{$v['order_amount']}}</td>
						<td class="green">
							@if($v['pay_status'] == 0)
								{{trans('home.unpaid')}}
							@elseif($v['pay_status'] ==1)
								{{trans('home.paid')}}
							@else
								{{trans('home.partial_payment')}}
							@endif
						</td>
					</tr>
					@endforeach
				@endif
			</table>
			</div>
			
		
			<!--商品推荐-->
			<div class="member_index_right whitebg fl ml15 br1 pr mt15" style="margin-left: -31px;">
				<!--标题-->
				<h1 class="member_right_title_icon mt25 ml30 pl20 fs16" style="width:910px;">
					<span>{{trans('home.goods_recommendation')}}</span>
					<span class="fr colr_blu mr35">
						<a href="/goodsList/1">{{trans('home.check_all_quotations')}}>></a>
					</span>
				</h1>
		
			<table class="order_record">
				<tr>
					<th>{{trans('home.goods_name')}}</th>
					<th>{{trans('home.price')}}</th>
					<th>{{trans('home.num')}}</th>
					<th>{{trans('home.delivery_area')}}</th>
					<th>{{trans('home.operation')}}</th>
				</tr>
				@foreach($memberInfo['shopGoodsInfo'] as $v)
				<tr>
					<td>{{getLangData($v,'goods_name')}}</td>
					<td class="green">￥{{$v['shop_price']}}</td>
					<td>{{$v['goods_number']}}</td>
					<td>{{$v['delivery_place']}}</td>
					<td><a class="or_re_btn" href="/goodsDetail/{{$v['id']}}/1"><span style="color: #fff;">{{trans('home.view_details')}}</span></a></td>
				</tr>
				@endforeach
				
			</table>
			</div>
			
		{{--</div>    --}}
	{{--</div>  --}}


<!-- </div>
</div> -->
@endsection

