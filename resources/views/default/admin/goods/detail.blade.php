@extends(themePath('.')."admin.include.layouts.master")
@section('iframe')
    <div class="warpper">
        <div class="title"><a href="/admin/goods/list?currpage={{$currpage}}" class="s-back">返回</a>会员 - 产品列表</div>
        <div class="content">


            <div class="flexilist">
                <div class="mian-info">
                    <div class="switch_info user_basic" style="display:block;">
                        <form method="post" action="" name="theForm" id="user_update" novalidate="novalidate">

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;产品名称：</div>
                                <div class="label_value font14">{{$good['goods_name']}}</div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;产品编码：</div>
                                <div class="label_value font14">{{$good['goods_sn']}}</div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;关键词：</div>
                                <div class="label_value font14">{{$good['keywords']}}</div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;所属品牌：</div>
                                <div class="label_value font14">{{$good['brand_name']}}</div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;所属分类：</div>
                                <div class="label_value font14">
                                    @foreach($cates as $cate)
                                        @if($cate['id']==$good['cat_id'])
                                            {{$cate['cat_name']}}
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;单位名称：</div>
                                <div class="label_value font14">{{$good['unit_name']}}</div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;产品型号：</div>
                                <div class="label_value font14">{{$good['goods_model']}}</div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;包装规格：</div>
                                <div class="label_value font14">{{$good['packing_spec']}}</div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;产品小图：</div>
                                <div class="label_value font14">
                                    <div  path="{{$good['goods_thumb']}}" class="layui-btn viewPic">点击查看</div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;产品大图：</div>
                                <div class="label_value font14">
                                    <div  path="{{$good['goods_img']}}" class="layui-btn viewPic">点击查看</div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;产品原图：</div>
                                <div class="label_value font14">
                                    <div  path="{{$good['original_img']}}" class="layui-btn viewPic">点击查看</div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;产品属性：</div>
                                <div class="label_value font14">{{$good['goods_attr']}}</div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;市场价：</div>
                                <div class="label_value font14">{{$good['market_price']}}</div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;产品重量：</div>
                                <div class="label_value font14">{{$good['goods_weight']}}</div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;pc商品详情：</div>
                                <div class="label_value font14">
                                    <div  content="{{$good['goods_desc']}}" class="layui-btn viewContent">点击查看</div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="label"><span class="require-field">*</span>&nbsp;移动端商品详情：</div>
                                <div class="label_value font14">
                                    <div  content="{{$good['desc_mobile']}}" class="layui-btn viewContent">点击查看</div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        layui.use(['upload','layer'], function() {
            var layer = layui.layer;

            $(".viewPic").click(function(){
                var src = $(this).attr('path');
                index = layer.open({
                    type: 1,
                    title: '大图',
                    area: ['700px', '600px'],
                    content: '<img src="'+src+'">'
                });
            });

            $(".viewContent").click(function(){
                var content = $(this).attr('content');
                index = layer.open({
                    type: 1,
                    title: '详情',
                    area: ['700px', '600px'],
                    content: content
                });
            });

        });
    </script>
@stop