@extends(themePath('.','web').'web.include.layouts.home')
@section('title', '报价列表')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('plugs/layui/css/layui.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset(themePath('/','web').'css/quotelist.css')}}" />
	<style>
		.nav-div .nav-cate .ass_menu {display: none;}
        .sort_down{background: url(/images/common_icon.png)no-repeat 64px 17px;}
        .sort_up{background: url(/images/common_icon.png)no-repeat 64px -10px}
        .sort_down_up{background: url(/images/down_up.png)no-repeat 63px 13px;}
        .add_time .sort_down_up{background: url(/images/down_up.png)no-repeat 92px 13px;}
        .add_time .sort_down{background: url(/images/common_icon.png)no-repeat 92px 17px;
        .add_time .sort_up{background: url(/images/common_icon.png)no-repeat 92px -10px;}
	</style>
@endsection
@section('js')
    <script src="{{asset('plugs/layui/layui.all.js')}}"></script>
    <script src="{{asset(themePath('/', 'web').'js/index.js')}}" ></script>
	<script>
        $(function(){
            $(".nav-cate").hover(function(){
                $(this).children('.ass_menu').toggle();// 鼠标悬浮时触发
            });
            // 更多/收起
            $('.pro_more').click(function(){
                $(this).toggleClass('pro_up');
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
	</script>
@endsection

@section('content')
	<div class="clearfix" style="background-color: #FFF;">
	<div class="w1200 pr">
		<div class="crumbs mt5 mb5"><span class="fl">当前位置：</span><a class="fl" href="/">产品列表</a>
            <div class="condition">
                <div style="margin-left:20px;display: none;" class="mode_add tac ml10 condition_tag" id="brand_tag" brand_id=""><i style="cursor: pointer" class="mode_close close_brand"></i></div>
                @if(isset($cate_id) && isset($cat_name) && !empty($cate_id) && !empty($cat_name))
                    <div style="margin-left:20px;" class="mode_add tac ml10 condition_tag" id="cate_tag" cate_id="{{$cate_id}}">
                        {{$cat_name}}<i style="cursor: pointer" class="mode_close close_cate"></i>
                    </div>
                @else
                    <div style="margin-left:20px;display: none;" class="mode_add tac ml10 condition_tag" id="cate_tag" cate_id="">
                        <i style="cursor: pointer" class="mode_close close_cate"></i>
                    </div>
                @endif

            </div>
			<div class="pro_Open pro_Open_up"></div>
			<div class="fr">共<font class="orange" id="relevant_total">{{$search_data['total']}}</font>个相关产品</div>
        </div>


		<div class="pro_screen">
            @if(!empty($search_data['filter']['brands']))
			<div class="pro_brand">
				<dl class="fl filter_item">
					<dt class="fl">品牌:</dt>
					<dd class="pro_brand_list ml30">
						@foreach($search_data['filter']['brands'] as $vo)
							<a onclick="choseByBrand(1,this)" class="choseByBrand" data-id="{{$vo['id']}}">{{$vo['brand_name']}}</a>
						@endforeach
					</dd>
					<div class="fl pro_brand_btn ml20 pro_more">更多</div>
					<div class="fl pro_brand_btn ml20 pro_m_select">多选</div>
				</dl>
			</div>
            @endif
            @if(!empty($search_data['filter']['cates']))
			<div class="pro_brand">
				<dl class="fl filter_item">
					<dt class="fl">种类:</dt>
					<dd class="pro_brand_list ml30">
						@foreach($search_data['filter']['cates'] as $vo)
						    <a onclick="choseByCate(1,this)" data-id="{{$vo['id']}}">{{$vo['cat_name']}}</a>
						@endforeach
					</dd>
					<div class="fl pro_brand_btn ml20 pro_more">更多</div>
					<div class="fl pro_brand_btn ml20 pro_m_select">多选</div>
				</dl>
			</div>
            @endif
            @if(!empty($search_data['filter']['city_list']))
			<div class="pro_brand" style="border-bottom: none;">
				<dl class="fl filter_item"><dt class="fl">地区:</dt>
					<dd class="pro_brand_list" style="width: 850px;margin-left:25px;">
						@foreach($search_data['filter']['city_list'] as $vo)
						    <label class=" check_box region"><input  class="check_box mr5 check_all fl mt10" name="region_box" type="checkbox" data-id="{{$vo['region_id']}}" value="{{$vo['region_name']}}"/><span  class="fl">{{$vo['region_name']}}</span></label>
						@endforeach
					</dd>
					<div onclick="getInfo(1)"  class="fl pro_brand_btn region_btn ml20">确定</div><div class="fl pro_brand_btn region_btn ml20 cancel_region">取消</div>
				</dl>
			</div>
            @endif
		</div>
		<div class="more_filter_box">更多选项...</div>
	</div>
	<div class="w1200 mt20 " style="margin-top: 20px;">
		<h1 class="product_title">产品列表</h1>
		<div class="scr">
			<div class="width1200">
				<div class="sequence-bar" style="padding:0;padding-right:10px;">
					<div class="fl">
						<a class="choose default active" href="#" style="height:39px;line-height:39px;margin-top:0;">综合</a>
					</div>
					<div class="fl">
						<ul id="sort" sort_name="" class="chooselist">
							<li class="sm_breed goods_number" sort=""><span class="sm_breed_span sort_down_up">数量</span></li>
							<li class="sm_breed shop_price" sort=""><span class="sm_breed_span sort_down_up">价格</span></li>
							<li class="sm_breed add_time" sort=""><span class="sm_breed_span sort_down_up" style="width: 113px;">上架时间</span></li>
						</ul>
					</div>
					<div class="fr">


					</div>
					<form class="fl" id="formid">
						<input class="min-max" name="lowest" id="minPrice" @if($lowest!="") value="{{$lowest}}" @else value=""  @endif value="" placeholder="￥最低价" style="margin-left: 5px">
						<span class="line">-</span>
						<input class="min-max" name="highest" id="maxPrice" @if($highest!="") value="{{$highest}}" @else value=""  @endif placeholder="￥最高价" style="margin-left: 5px">
						<input class="confirm active inline-block" id="btnSearchPrice" value="确定" type="button" style="margin-left: 5px">
					</form>
				</div>
			</div>
		</div>
		<ul class="Self-product-list">

			<li class="table_title"><span class="num_bg1">店铺</span><span  style="width:8%;">品牌</span><span style="width:8%;">种类</span><span>商品名称</span><span style="width:8%;">数量（公斤）</span><span>单价（元/公斤）</span><span>发货地址</span><span style="width:15%;">联系人</span><span>操作</span></li>
			@foreach($search_data['list'] as $vo)
				<li>
                    <span data-id="{{$vo['packing_spec']}}" id="packing_spec">{{$vo['shop_name']}}</span>
                    <span style="width:8%;">{{$vo['brand_name']}}</span>
                    <span class="ovh" style="width:8%;">{{$vo['cat_name']}}</span>
                    <span ><a class="orange" href="/goodsDetail?id={{$vo['id']}}&shop_id={{$vo['shop_id']}}">{{$vo['goods_name']}}</a></span>
                    <span style="width:8%;">{{$vo['goods_number']}}</span><span>{{$vo['shop_price']}}</span><span>{{$vo['delivery_place']}}</span>
                    <span style="width:15%;">{{$vo['salesman']}}/{{$vo['contact_info']}}</span>
                    <span>@if($vo['goods_number'])<button  data-id="{{$vo['id']}}" class="P_cart_btn">加入购物车</button>@else已售完 @endif</span>
                </li>
			@endforeach
		</ul>
		<!--页码-->
		<div class="news_pages" style="margin-top: 20px;text-align: center;">
			<ul id="page" class="pagination"></ul>
		</div>
	</div>
	</div>
@endsection

@section('bottom_js')
<script>
    paginate();
    changeURL();

    //取消地区选择
    $('.cancel_region').click(function(){
        $("input[name='region_box']").each(function(){
            $(this).attr("checked",false);
        });
        getInfo(1);
    });

    $(".goods_number").click(function(){//sort_goods_number
        setSort('goods_number');
    });

    $(".add_time").click(function(){
        setSort('add_time');
	});

    $(".shop_price").click(function () {
        setSort('shop_price');
    });


    //加入购物车
    $(document).delegate('.P_cart_btn','click',function(){
        var userId = "{{session('_web_user_id')}}";
        if(userId==""){
            $.msg.error("未登录",1);
            return false;
        }
        var id = $(this).attr("data-id");
        var number = $("#packing_spec").attr('data-id');
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


    //价格筛选
    $('#btnSearchPrice').click(function(){
        getInfo(1);
    });

    //删除筛选条件
    $(function(){
        $(document).delegate('.close_brand','click',function(){
            $('#brand_tag').hide();
            $('#brand_tag').empty();
            $('#brand_tag').attr('brand_id','');
            getInfo(1);
        });
        $(document).delegate('.close_cate','click',function(){
            $('#cate_tag').hide();
            $('#cate_tag').empty();
            $('#cate_tag').attr('cate_id','');
            getInfo(1);
        });
    });


	//无刷新改变url地址
    function changeURL(){
        window.history.pushState({},0,'http://'+window.location.host+'/goodsList');
    }
    //分页
    function paginate(){
        layui.use(['laypage'], function() {
            var laypage = layui.laypage;
            laypage.render({
                elem: 'page' //注意，这里的 test1 是 ID，不用加 # 号
                , count: "{{$search_data['total']}}" //数据总数，从服务端得到
                , limit: "{{$pageSize}}"   //每页显示的条数
                , curr: "{{$currpage}}"  //当前页
                , prev: "上一页"
                , next: "下一页"
                , theme: "#88be51" //样式
                , jump: function (obj, first) {
                    if (!first) {
                        getInfo(obj.curr);
                        {{--window.location.href="/goodsList?currpage="+obj.curr+"&orderType={{$orderType}}"+"&lowest={{$lowest}}"+"&highest={{$highest}}";--}}
                    }
                }
            });
        });
    }
    //根据品牌筛选
    function choseByBrand(currpage,b_obj){
        var _brand_name = $(b_obj).text();
        var _brand_id = $(b_obj).attr("data-id");
        $('#brand_tag').empty();
        $('#brand_tag').attr('brand_id',_brand_id);
        $('#brand_tag').append(_brand_name+'<i style="cursor: pointer"  class="mode_close close_brand"></i>');
        $('#brand_tag').show();
        getInfo(currpage);
    }
    //根据种类筛选
    function choseByCate(currpage,b_obj){
        var cate_id = $(b_obj).attr("data-id");
        var cat_name = $(b_obj).text();
        $('#cate_tag').empty();
        $('#cate_tag').attr('cate_id',cate_id);
        $('#cate_tag').append(cat_name+'<i style="cursor: pointer"  class="mode_close close_cate"></i>');
        $('#cate_tag').show();
        getInfo(currpage);
    }
    //请求ajax获取列表数据
    function getInfo(currpage){
        var _cate_id = $('#cate_tag').attr('cate_id');
        var _brand_id = $('#brand_tag').attr('brand_id');
        var region_ids = [];
        var region_names = [];
        $("input[name='region_box']").each(function(){
            if($(this).is(':checked')){
                region_ids.push($(this).attr('data-id'));
                region_names.push($(this).val());
            }
        });
        var _highest = $('#maxPrice').val();
        var _lowest = $('#minPrice').val();
        var _orderType = "{{$orderType}}";
        var _place_id = region_ids.join("|");

        //获取排序筛选
        var _name = $('#sort').attr('sort_name');
        var _goods_number = '';
        var _shop_price = '';
        var _add_time = '';
        if(_name == 'goods_number'){
            _goods_number = $('.goods_number').attr('sort');
            $('.shop_price span').attr('class','sm_breed_span sort_down_up');
            $('.add_time span').attr('class','sm_breed_span sort_down_up');
        }else if(_name == 'shop_price'){
            _shop_price = $('.shop_price').attr('sort');
            $('.goods_number span').attr('class','sm_breed_span sort_down_up');
            $('.add_time span').attr('class','sm_breed_span sort_down_up');
        }else if(_name == 'add_time'){
            _add_time = $('.add_time').attr('sort');
            $('.goods_number span').attr('class','sm_breed_span sort_down_up');
            $('.shop_price span').attr('class','sm_breed_span sort_down_up');
        }

        $.ajax({
            type: "get",
            url: "/condition/goodsList",
            data: {
                "brand_id":_brand_id,
                "currpage":currpage,
                'highest':_highest,//最高价
                'lowest':_lowest,//最低价
                'orderType':_orderType,//排序
                'cate_id':_cate_id,//分类
                'place_id':_place_id,//地区
                'sort_goods_number':_goods_number,//数量排序
                'sort_shop_price':_shop_price,//价格排序
                'sort_add_time':_add_time//时间排序
            },
            dataType: "json",
            success: function(res){
                if(res.code==200){
                    var data = res.data;
                    var currpage = data.currpage;
                    var pageSize = data.pageSize;
                    var total = data.total;
                    var list = data.list;
                    $(".table_title").nextAll().remove();//去除已经出现的数据
                    $("#page").remove();//删除分页div
                    for (var i=0;i<list.length;i++)
                    {
                        $(".table_title").after('<li><span data-id="'+list[i].packing_spec+'" id="packing_spec">'+list[i].shop_name+'</span><span style="width:8%;">'+list[i].brand_name+'</span><span style="width:8%;" class="ovh">'+list[i].cat_name+'</span><span ><a class="orange" href="/goodsDetail?id='+list[i].id+'&shop_id='+list[i].shop_id+'">'+list[i].goods_name+'</a></span><span style="width:8%;">'+list[i].goods_number+'</span><span>'+list[i].shop_price+'</span><span>'+list[i].delivery_place+'</span><span style="width:15%;">'+list[i].salesman+'/'+list[i].contact_info+'</span><span><button data-id="'+list[i].id+'" class="P_cart_btn">加入购物车</button></span></li>');
                        $(".news_pages").append('<ul id="page" class="pagination"></ul>');

                    }
                    $('#relevant_total').text(total);
                    //分页
                    layui.use(['laypage'], function() {
                        var laypage = layui.laypage;
                        laypage.render({
                            elem: 'page' //注意，这里的 test1 是 ID，不用加 # 号
                            , count: total //数据总数，从服务端得到
                            , limit: pageSize   //每页显示的条数
                            , curr: currpage  //当前页
                            , prev: "上一页"
                            , next: "下一页"
                            , theme: "#88be51" //样式
                            , jump: function (obj, first) {
                                if (!first) {
                                    getInfo(obj.curr);
                                }
                            }
                        });
                    });
                }else{
                    $(".table_title").nextAll().remove();
                    $(".table_title").after('<li style="color:red;">没有相关数据</li>');
                }
            }
        });
    }
    //排序
    function setSort(_name){
        var _this = $('.'+_name);
        var _obj = $('.'+_name+' span');
        var _down_up = _obj.hasClass("sort_down_up");
        var _down = _obj.hasClass("sort_down");
        var _up = _obj.hasClass("sort_up");
        if(_down_up == true && _down == false && _up == false){//默认情况 没用goods_number排序 此时点第一下执行倒序
            _this.attr('sort','desc');
            _obj.attr('class','sm_breed_span sort_down');
            $('#sort').attr('sort_name',_name);
            getInfo(1);
        }else if(_down_up == false && _down == true && _up == false){//此时是倒序 点击后正序
            _this.attr('sort','asc');
            _obj.attr('class','sm_breed_span sort_up');
            $('#sort').attr('sort_name',_name);
            getInfo(1);
        }else if(_down_up == false && _down == false && _up == true){//此时是正序 点击后倒序
            _this.attr('sort','desc');
            _obj.attr('class','sm_breed_span sort_down');
            $('#sort').attr('sort_name',_name);
            getInfo(1);
        }
    }

</script>
@endsection