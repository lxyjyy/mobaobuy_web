@extends(themePath('.')."seller.include.layouts.master")
@section('body')

    <div class="warpper">
        <div class="title"><a href="/admin/shop/store?currentPage={{$currentPage}}" class="s-back">返回</a>店铺 - 编辑店铺</div>
        <div class="content">
            <div class="explanation" id="explanation">
                <div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                    <li>只有自营的商家才能添加店铺</li>
                </ul>
            </div>
            <div class="flexilist">
                <div class="mian-info">
                    <form action="/admin/shop/store/save" method="post" enctype="multipart/form-data" name="theForm" id="store_form" novalidate="novalidate">
                        <div class="switch_info" style="display: block;">
                            <input type="hidden" name="id" value="{{$storeInfo['id']}}" >
                            <input type="hidden" name="currentPage" value="{{$currentPage}}" >

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;选择商家：</div>
                                <div class="label_value">
                                    <select style="height:30px;border:1px solid #dbdbdb;line-height:30px;float:left;" name="shop_id" id="shop_id" value="{{$storeInfo['shop_id']}}">
                                        <option value="">请选择商家</option>
                                    </select>
                                    <input type="hidden" name="shop_name" id="shop_name" value="{{$storeInfo['company_name']}}">
                                    <div style="margin-left: 10px;" class="form_prompt"></div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;店铺名称：</div>
                                <div class="label_value">
                                    <input type="text" name="store_name" class="text" value="{{$storeInfo['store_name']}}" maxlength="40" autocomplete="off" id="store_name">
                                    <div class="form_prompt"></div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="label">&nbsp;主营品类：</div>
                                <div class="label_value">
                                    <input type="text" name="main_cat" class="text" value="{{$storeInfo['main_cat']}}" maxlength="40" autocomplete="off" id="main_cat">
                                    <div class="form_prompt"></div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="label">&nbsp;主营品牌：</div>
                                <div class="label_value">
                                    <input type="text" name="main_brand" class="text" value="{{$storeInfo['main_brand']}}" maxlength="40" autocomplete="off" id="main_brand">
                                    <div class="form_prompt"></div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="label">&nbsp;规格：</div>
                                <div class="label_value">
                                    <input type="text" name="spec" class="text" value="{{$storeInfo['spec']}}" maxlength="40" autocomplete="off" id="spec">
                                    <div class="form_prompt"></div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="label">&nbsp;交货地：</div>
                                <div class="label_value">
                                    <input type="text" name="delivery_area" class="text" value="{{$storeInfo['delivery_area']}}" maxlength="40" autocomplete="off" id="delivery_area">
                                    <div class="form_prompt"></div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="label">&nbsp;交货方式：</div>
                                <div class="label_value">
                                    <input type="text" name="delivery_method" class="text" value="{{$storeInfo['delivery_method']}}" maxlength="40" autocomplete="off" id="delivery_method">
                                    <div class="form_prompt"></div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="label">商品图片：</div>
                                <div class="label_value">
                                    <button style="float: left;" type="button" class="layui-btn upload-file" data-type="" data-path="store" >
                                        <i class="layui-icon">&#xe681;</i> 上传图片
                                    </button>
                                    <input type="hidden" value="" class="text" id="store_img"  name="store_img" style="display:none;">
                                    <img @if(empty($storeInfo['store_img'])) style="width:60px;height:60px;display:none;margin-top:-5px;margin-left:10px;" @else src="{{getFileUrl($storeInfo['store_img'])}}" style="width:60px;height:60px;margin-top:-5px;margin-left:10px;" @endif class="layui-upload-img">
                                    <div style="margin-left: 10px;line-height:40px;" class="form_prompt"></div>
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

        $(function(){
            getShopList('{{$storeInfo['shop_id'] or 0}}');
            //表单验证
            $("#submitBtn").click(function(){
                if($("#store_form").valid()){
                    $("#store_form").submit();
                }
            });

            $('#store_form').validate({
                errorPlacement:function(error, element){
                    var error_div = element.parents('div.label_value').find('div.form_prompt');
                    element.parents('div.label_value').find(".notic").hide();
                    error_div.append(error);
                },
                ignore : [],
                rules:{
                    shop_name :{
                        required : true,
                    },
                    store_name :{
                        required : true,
                    },
                },
                messages:{
                    shop_name:{
                        required : '<i class="icon icon-exclamation-sign"></i>'+'必填项'
                    },
                    store_name:{
                        required : '<i class="icon icon-exclamation-sign"></i>'+'必填项'
                    },
                }
            });
            layui.use(['upload','layer'], function(){
                var upload = layui.upload;
                var layer = layui.layer;

                //文件上传
                upload.render({
                    elem: '.upload-file' //绑定元素
                    ,url: "/uploadImg" //上传接口
                    ,accept:'file'
                    ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                        this.data={'upload_type':this.item.attr('data-type'),'upload_path':this.item.attr('data-path')};
                    }
                    ,done: function(res){
                        //上传完毕回调
                        if(1 == res.code){
                            var item = this.item;
                            item.siblings('input').attr('value', res.data.path);
                            item.siblings('img').show().attr('src', res.data.url);
                            item.siblings('div').filter(".form_prompt").remove();
                        }else{
                            layer.msg(res.msg, {time:2000});
                        }
                    }
                });
            });
        });
        // 商家 请求所有的商家数据
        function getShopList(_id){
            $.ajax({
                url: "/admin/shop/ajax_list",
                dataType: "json",
                data:{},
                type:"POST",
                success:function(res){
                    if(res.code==1){
                        let data = res.data;
                        for(let i=0;i<data.length;i++){
                            if(_id == data[i].id){
                                $("#shop_id").append('<option value="'+data[i].id+'" selected>'+data[i].company_name+'</option>');
                            }else{
                                $("#shop_id").append('<option value="'+data[i].id+'">'+data[i].company_name+'</option>');
                            }

                        }
                    }
                }
            })
        }
    </script>


@stop
