@extends(themePath('.')."seller.include.layouts.master")
@section('styles')
    <style>
        [class^="icon-"], [class*=" icon-"] {
            line-height: 23px;
        }
        .Self-product-list li span{width:14%;}
        .news_pages ul.pagination {text-align: center;}
        .Self-product-list li span{width: 12.5%;float: left;text-align: center;}
        .pro_detail{overflow: hidden;}
        .pur_volume{float:left;border: 1px solid #DEDEDE; box-sizing:border-box;}
        .pur_volume .pur{cursor:pointer;width: 26px;text-align: center;float: left;height: 28px;line-height: 28px;background-color: #fafafa;box-sizing:border-box;}
        .pur_num{float:left;width: 50px;height: 28px;line-height: 28px;text-align: center;border: 1px solid #fff;}

    </style>
@endsection
@section('body')
    @include('partials.base_header')
    <script src="{{asset(themePath('/').'js/jquery.validation.min.js')}}" ></script>
    <script src="{{asset(themePath('/').'js/jquery.cookie.js')}}" ></script>
    <script src="{{asset(themePath('/').'js/dsc_admin2.0.js')}}" ></script>
    <link rel="stylesheet" type="text/css" href="{{asset(themePath('/').'plugs/layui/css/layui.css')}}" />
    <link rel="stylesheet" type="text/css" href="/ui/area/1.0.0/area.css" />
    <script type="text/javascript" src="/ui/area/1.0.0/area.js"></script>
    <div class="warpper">
        <div class="title"><a href="/admin/activity/wholesale?currentPage={{$currentPage}}" class="s-back">返回</a>集采拼团</div>
        <div class="content">
            <div class="flexilist">
                <div class="mian-info">
                    <form action="/admin/activity/wholesale/save" method="post" name="theForm" id="wholesale_form" novalidate="novalidate">
                        <input type="text" value="@if(!empty($wholesale_info)){{$wholesale_info['id']}}@endif" name="id" style="display: none">
                        <div class="switch_info" style="display: block;">
                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;选择商家：</div>
                                <div class="label_value">
                                    <input type="text" @if(!empty($wholesale_info))  shop-id="{{$wholesale_info['shop_id']}}" value="{{$wholesale_info['shop_name']}}" @else shop-id="" value="" @endif autocomplete="off" id="company_name" size="40"  class="text">
                                    <input type="hidden" name="company_name" @if(!empty($wholesale_info)) value="{{$wholesale_info['shop_name']}}" @endif id="company_name_val" />
                                    <input type="hidden" name="shop_id" @if(!empty($wholesale_info)) value="{{$wholesale_info['shop_id']}}" @endif id="shop_id" />
                                    <div style="margin-left: 10px;" class="notic">只能通过点击查询出来的数据填入，不能手动输入</div>
                                    <ul class="query_company_name" style="overflow:auto;display:none;height:200px;position: absolute; z-index: 2; top: 62px; background: #fff;width: 320px; box-shadow: 0px -1px 1px 2px #dedede;">
                                    </ul>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label">&nbsp;选择商品分类：</div>
                                <div class="label_value">
                                    <input type="text" data-catname="" @if(!empty($wholesale_info))  cat-id="{{$wholesale_info['cat_id']}}" value="{{$wholesale_info['cat_name']}}" @else cat-id="" value="" @endif autocomplete="off" id="cat_name" size="40"  class="text">
                                    <div style="margin-left: 10px;" class="notic">商品分类用于辅助选择商品</div>
                                    <ul class="query_cat_name" style="overflow:auto;display:none;height:200px;position: absolute; z-index: 2; top: 102px; background: #fff;width: 320px; box-shadow: 0px -1px 1px 2px #dedede;">
                                    </ul>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;选择商品：</div>
                                <div class="label_value">
                                    <input type="text" @if(!empty($good['packing_spec'])) data-packing-spac="{{$good['packing_spec']}}" @else data-packing-spac="" @endif @if(!empty($wholesale_info)) value="{{$wholesale_info['goods_name']}}" @else value="" @endif autocomplete="off"  id="goods_name" size="40"  class="text">
                                    <input type="hidden" @if(!empty($wholesale_info)) value="{{$wholesale_info['goods_id']}}" @endif name="goods_id"  id="goods_id">
                                    <div class="form_prompt"></div>
                                    <ul class="query_goods_name" style="overflow:auto;display:none;height:200px;position: absolute;top: 142px; background: #fff;padding-left:20px;width: 300px; z-index: 2; box-shadow: 1px 1px 1px 1px #dedede;">
                                    </ul>
                                </div>
                            </div>
                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;开始时间：</div>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" style="width:159px;height: 30px;" name="start_date" id="start_date" @if(!empty($wholesale_info)) value="{{$wholesale_info['begin_time'][0]}}" @endif>
                                </div>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" style="width:159px;height: 30px;" name="start_time" id="start_time" @if(!empty($wholesale_info)) value="{{$wholesale_info['begin_time'][1]}}" @endif>
                                </div>
                                <div class="form_prompt"></div>
                            </div>
                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;结束时间：</div>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input"  style="width:159px;height: 30px;" name="end_date" id="end_date" @if(!empty($wholesale_info)) value="{{$wholesale_info['end_time'][0]}}" @endif>
                                </div>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input"  style="width:159px;height: 30px;" name="end_time" id="end_time" @if(!empty($wholesale_info)) value="{{$wholesale_info['end_time'][1]}}" @endif>
                                </div>
                                <div class="form_prompt"></div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;拼团价格：</div>
                                <div class="label_value">
                                    <input type="number" name="price" class="text" value="@if(!empty($wholesale_info)){{$wholesale_info['price']}}@endif" maxlength="10" autocomplete="off" id="price">
                                    <div class="form_prompt"></div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;目标数量：</div>
                                <div class="label_value">
                                    <input type="number" name="num" class="text" @if(!empty($wholesale_info)) value="{{$wholesale_info['num']}}" @endif maxlength="5"  autocomplete="off" id="num">
                                    <div class="form_prompt"></div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;订金比例：</div>
                                <div class="label_value">
                                    <input type="number" name="deposit_ratio" class="text" @if(!empty($wholesale_info)) value="{{$wholesale_info['deposit_ratio']}}" @endif maxlength="5"  autocomplete="off" id="deposit_ratio">（0 不需支付定金,可以直接输入0）
                                    <div class="form_prompt"></div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;最小拼团量：</div>
                                <div class="label_value">
                                    <div class="pro_detail">
                                        <div class="pur_volume">
                                            <span class="pur bbright">-</span>
                                            <input type="text" name="min_limit" class="pur_num" value="@if(!empty($wholesale_info)){{$wholesale_info['min_limit']}}@endif" id="min_limit"/>
                                            <span class="pur bbleft">+</span>
                                        </div>
                                    </div>
                                    <div class="form_prompt"></div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;最大限购量：</div>
                                <div class="label_value">
                                    <div class="pro_detail">
                                        <div class="pur_volume">
                                            <span class="pur bbright">-</span>
                                            <input type="text" name="max_limit" class="pur_num" value="@if(!empty($wholesale_info)){{$wholesale_info['max_limit']}}@endif" id="max_limit"/>
                                            <span class="pur bbleft">+</span>
                                        </div>（0 不限,可以直接输入0）
                                    </div>
                                    <div class="form_prompt"></div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label">&nbsp;</div>
                                <div class="label_value info_btn">
                                    <input type="submit" value="确定" class="button" id="submitBtn">
                                    <input type="reset" value="重置" class="button button_reset">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        layui.use('laydate', function() {
            var laydate = layui.laydate;

            laydate.render({
                elem: '#start_date'
            });

            laydate.render({
                elem: '#start_time'
                ,type: 'time'
            });
            laydate.render({
                elem: '#end_date'
            });

            laydate.render({
                elem: '#end_time'
                ,type: 'time'
            });
        });

        $(function(){
            //表单验证
            $("#submitBtn").click(function(){
                if($("#wholesale_form").valid()){
                    $("#wholesale_form").submit();
                }
            });
            $('#wholesale_form').validate({
                errorPlacement:function(error, element){
                    var error_div = element.parents('div.label_value').find('div.form_prompt');
                    element.parents('div.label_value').find(".notic").hide();
                    error_div.append(error);
                },
                ignore : [],
                rules:{
                    price :{
                        required : true,
                        number:true
                    },
                    num :{
                        required : true,
                        number:true
                    },
                    goods_id:{
                        required : true,
                    },
                    start_date:{
                        required:true
                    },
                    end_date:{
                        required:true
                    },
                    deposit_ratio:{
                        required:true,
                        number:true
                    }
                },
                messages:{
                    price:{
                        required : '<i class="icon icon-exclamation-sign"></i>'+'必填项'
                    },
                    number :{
                        required : '<i class="icon icon-exclamation-sign"></i>'+'必填项',
                        number : '<i class="icon icon-exclamation-sign"></i>'+'必须为数字',
                    },
                    goods_id :{
                        required : '<i class="icon icon-exclamation-sign"></i>'+'必填项'
                    },
                    start_date :{
                        required : '<i class="icon icon-exclamation-sign"></i>'+'必填项'
                    },
                    end_date :{
                        required : '<i class="icon icon-exclamation-sign"></i>'+'必填项'
                    },
                    deposit_ratio :{
                        required : '<i class="icon icon-exclamation-sign"></i>'+'必填项',
                        number : '<i class="icon icon-exclamation-sign"></i>'+'必须为数字',
                    },
                }
            });

            // 限制商品总数
            $("#num").change(function () {
                let spec = Number($("#goods_name").attr("data-packing-spac"));
                let num = Number($(this).val());
                let b_num = num % spec;
                console.log(spec)
                if (spec==0|| spec==''){
                    layer.msg('请先选择商品');
                    $("#num").val(0);
                    return false;
                }
                if (num<spec){
                    $(this).val(spec);
                } else {
                    if (b_num >0){
                        $(this).val(num-b_num);
                    } else {
                        $(this).val(num);
                    }
                }
            });

            // 减
            $(".bbright").click(function(){
                let spec = Number($("#goods_name").attr("data-packing-spac"));
                let num = Number($(this).siblings('.pur_num').val());
                let id = $(this).siblings('.pur_num').attr('id');
                let min_num = $("#min_limit").val();
                let max_num = $("#max_limit").val();
                let b_num = num % spec;
                if (spec==0|| spec==''){
                    layer.msg('请先选择商品');
                    $(this).siblings('.pur_num').val(0);
                    return false;
                }
                if(id == 'min_limit' && min_num-spec>max_num){
                    if(max_num != 0){
                        $(this).siblings('.pur_num').val(max_num);
                        $.msg.alert('不能大于最大限购量');
                    }
                    if (b_num>0){
                        $(this).siblings('.pur_num').val(num-spec-b_num);
                    } else {
                        $(this).siblings('.pur_num').val(num-spec);
                    }
                }else if(id == 'max_limit' && min_num>max_num-spec){
                    $(this).siblings('.pur_num').val(min_num);
                    $.msg.alert('不能小于最小起售量');
                }else if(num <= spec){
                    $(this).siblings('.pur_num').val(spec);
                } else {
                    if (b_num>0){
                        $(this).siblings('.pur_num').val(num-spec-b_num);
                    } else {
                        $(this).siblings('.pur_num').val(num-spec);
                    }
                }
            });

            // 加
            $(".bbleft").click(function(){
                var _num;
                let tota_num = Number($("#num").val());
                let spec = Number($("#goods_name").attr("data-packing-spac"));
                let num = Number($(this).siblings('.pur_num').val());
                let id = $(this).siblings('.pur_num').attr('id');
                let min_num = Number($("#min_limit").val());
                let max_num = Number($("#max_limit").val());

                let b_num = num % spec;
                if (spec==0|| spec==''){
                    layer.msg('请先选择商品');
                    $(this).siblings('.pur_num').val(0);
                    return false;
                }
                //60 60 60 20
                if(id == 'max_limit' && min_num>max_num-b_num+spec){
                    $(this).siblings('.pur_num').val(min_num);
                    $.msg.alert('不能小于最小起售量');
                }else if (num>tota_num){
                    $(this).siblings('.pur_num').val(tota_num);
                } else {
                    if (num+spec>tota_num){
                        $(this).siblings('.pur_num').val(tota_num);
                    } else{
                        $(this).siblings('.pur_num').val(num+spec);
                    }
                    if(id == 'min_limit' && min_num+spec>max_num){
                        // $(this).siblings('.pur_num').val(max_num);
                        if(max_num != 0){
                            if (min_num+spec>tota_num){
                                $("#max_limit").val(tota_num);
                            } else {
                                $("#max_limit").val(num+spec);
                            }
                        }

                    }
                }
            });
        });

        $("#min_limit").change(function () {
            let min_num = Number($(this).val());
            let max_num = Number($("#max_limit").val());
            let spac = Number($("#goods_name").attr("data-packing-spac"));
            let tota_num = Number($("#num").val());

            if(max_num != 0 && min_num > max_num){
                $(this).val(max_num);
            }else{
                if (min_num<spac){
                    $(this).val(spac);
                } else {
                    if (min_num>tota_num){
                        $(this).val(tota_num);
                    }else{
                        let _count = min_num%spac;
                        if(_count > 0){
                            $(this).val(min_num - _count);
                        }else{
                            $(this).val(min_num);
                        }

                    }
                }
            }
        });


        // 控制最大值直接输入
        $("#max_limit").change(function () {
           let max_val =  $(this).val();
           let min_val = $("#min_limit").val();
           let spac = Number($("#goods_name").attr("data-packing-spac"));
           let tota_num = Number($("#num").val());
           if (max_val==0){
               $(this).val(0);
               return false;
           }
           if (max_val<min_val){
               $(this).val(min_val);
           } else {
               if (max_val>tota_num){
                   $(this).val(tota_num);
               }else{
                   let _count = max_val%spac;
                   if(_count > 0){
                       $(this).val(max_val - _count);
                   }else{
                       $(this).val(max_val);
                   }

               }
           }
        });

        document.onclick=function(event){
            $(".query_cat_name").hide();
            $(".query_company_name").hide();
        }

        // 商家 获取焦点请求所有的商家数据
        $("#company_name").focus(function(){
            $(".query_company_name").children().filter("li").remove();
            $.ajax({
                url: "/admin/shop/ajax_list",
                dataType: "json",
                data:{},
                type:"POST",
                success:function(res){
                    if(res.code==1){
                        $(".query_company_name").show();
                        var data = res.data;
                        for(var i=0;i<data.length;i++){
                            $(".query_company_name").append('<li data-shop-id="'+data[i].id+'" class="created_company_name" style="cursor:pointer;margin-left: 4px">'+data[i].company_name+'</li>');
                        }
                    }
                }
            })
        });

        //点击将li标签里面的值填入input框内
        $(document).delegate(".created_company_name","click",function(){
            //$("#company_name").siblings("div").filter(".notic").remove();
            var company_name = $(this).text();
            var shop_id = $(this).attr("data-shop-id");
            $("#company_name").val(company_name);
            $("#company_name_val").val(company_name);
            $("#shop_id").val(shop_id);
            $(".query_company_name").hide();
        });

        //根据company里面输入的文字实时查询分类数据
        $("#company_name").bind("input propertychange",function(res){
            var company_name = $(this).val();
            $(".query_company_name").children().filter("li").remove();
            $.post('/admin/shop/ajax_list',{'company_name':company_name},function(res){
                if(res.code==1){
                    $(".query_company_name").show();
                    var data = res.data;
                    for(var i=0;i<data.length;i++){
                        $(".query_company_name").append('<li data-shop-id="'+data[i].id+'" class="created_company_name" style="cursor:pointer;margin-left: 4px">'+data[i].company_name+'</li>');
                    }
                }
            },"json");
        });

        $("#company_name").blur(function(){
            let _name = $("#company_name_val").val();
            $(this).val(_name);
        });

        // 种类 获取焦点请求所有的分类数据
        $("#cat_name").focus(function(){
            $(".query_cat_name").children().filter("li").remove();
            $.ajax({
                url: "/admin/promote/getGoodsCat",
                dataType: "json",
                data:{},
                type:"POST",
                success:function(res){
                    if(res.code==1){
                        $(".query_cat_name").show();
                        var data = res.data;
                        for(var i=0;i<data.length;i++){
                            $(".query_cat_name").append('<li data-cat-id="'+data[i].id+'" class="created_cat_name" style="cursor:pointer;margin-left: 4px">'+data[i].cat_name+'</li>');
                        }
                    }
                }
            })
        });

        // 种类 点击将选中的值填入input框内
        $(document).delegate(".created_cat_name","click",function(){
            var cat_name = $(this).text();
            var cat_id = $(this).attr("data-cat-id");
            $("#cat_name").attr('data-catname',cat_name);
            $("#cat_name").val(cat_name);
            $("#cat_name").attr("cat-id",cat_id);
        });

        //根据company里面输入的文字实时查询分类数据
        $("#cat_name").bind("input propertychange",function(res){
            var cat_name = $(this).val();
            $(".query_cat_name").children().filter("li").remove();
            $.post('/admin/promote/getGoodsCat',{'cat_name':cat_name},function(res){
                if(res.code==1){
                    $(".query_cat_name").show();
                    var data = res.data;
                    console.log(data);
                    for(var i=0;i<data.length;i++){
                        $(".query_cat_name").append('<li data-cat-id="'+data[i].id+'" class="created_cat_name" style="cursor:pointer;margin-left: 4px">'+data[i].cat_name+'</li>');
                    }
                }
            },"json");
        });

        $("#cat_name").blur(function(){
            let _name = $(this).attr("data-catname");
            $(this).val(_name);
        });


        // 商品 获取焦点请求所有的商品数据
        $("#goods_name").focus(function(){
            $(".query_goods_name").children().filter("li").remove();
            var cat_id = $("#cat_name").attr("cat-id");
            $.ajax({
                url: "/admin/promote/getGood",
                dataType: "json",
                data:{"cat_id":cat_id},
                type:"POST",
                success:function(res){
                    if(res.code==200){
                        $(".query_goods_name").show();
                        var data = res.data;
                        for(var i=0;i<data.length;i++){
                            $(".query_goods_name").append('<li data-packing-spac="'+data[i].packing_spec+'" data-packing-unit= "'+data[i].packing_unit+'" data-goods-id="'+data[i].id+'" class="created_goods_name" style="cursor:pointer;">'+data[i].goods_full_name+'</li>');
                        }
                    }else{
                        $(".query_goods_name").show();
                        $(".query_goods_name").append('<li  style="cursor:pointer;">该分类下没有查询到商品</li>');
                    }
                }
            })
        });

        //点击将li标签里面的值填入input框内
        $(document).delegate(".created_goods_name","click",function(){
            $("#goods_name").siblings("div").filter(".notic").remove();
            var goods_name = $(this).text();
            var goods_id = $(this).attr("data-goods-id");
            var packing_spac = $(this).attr("data-packing-spac");
            let packing_unit = $(this).attr('data-packing-unit');
            $("#goods_name").val(goods_name);
            $("#goods_id").val(goods_id);
            $("#goods_name").attr("data-packing-spac",packing_spac);
            $("#min_limit").val(packing_spac);
            $("#num").val(packing_spac);
            $("#num").attr("disabled",false);
            $("#goods_name").after('<div style="margin-left: 10px;color:red;" class="notic">包装规格为：'+packing_spac+packing_unit+'</div>');
        });

        $("#goods_number").change(function () {
            let spac = $("#goods_name").attr("data-packing-spac");
            let goods_number = $(this).val();
            if (spac >goods_number){
                $(this).val(spac);
            } else {
                if (goods_number%spac>0){
                    $(this).val(goods_number-goods_number%spac);
                } else {
                    $(this).val(goods_number);
                }
            }
        });
    </script>
@stop