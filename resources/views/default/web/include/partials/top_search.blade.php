<div class="top-search-box clearfix">
    <div class="top-search-div">
        <div class="search-div">
            <div class="logo">
                <a href="/">
                    <img src="{{getFileUrl(getConfig('shop_logo', asset('images/logo.png')))}}">
                </a>
            </div>
            <div class="search-box">
                <input type="text" class="search-input" placeholder="请输入关键词、类别进行搜索"/>
                <input type="button" class="opt-btn" value="搜 索"/>
                <a class="contact_artificial tac br1 db fl ml10">联系人工找货</a>

                @if(!empty(getConfig('search_keywords')))
                    <div class="hot_search_m">热门推荐：
                        @foreach(explode(',',getConfig('search_keywords')) as $item)
                            <a href="#.html" target="_blank">{{$item}}</a>
                        @endforeach
                    </div>
                @endif
            </div>
            <a class="shopping_cart mt40 tac"><span class="fl ml25"><i class="iconfont icon-gouwuche"></i>我的购物车</span><span id="shopping-amount" class="pro_cart_num">0</span></a>
        </div>
        <div class="clearfix nav-div">
            <div class="nav-cate">
                <div class="cate_title"><span class="ml30">原料分类</span><i class="iconfont icon-menu mr20 fr fs22"></i></div>
                <ul class="ass_menu">
                    @foreach($cat_tree as $level1_item)
                        <li><span class="ass_title">{{$level1_item['cat_name']}}</span>
                            <i class="iconfont icon-right fr mr20"></i>
                            <div class="ass_fn whitebg">
                                <ul class="ass_fn_list">
                                    @foreach($level1_item['_child'] as $level2_item)
                                        <li>
                                            <h1 class="fn_title fl">{{$level2_item['cat_name']}}</h1>
                                            @if(isset($level2_item['_child']))
                                                <div class="ass_fn_list_that ml35 ovh fl">
                                                    @foreach($level2_item['_child'] as $level3_item)
                                                        <span>{{$level3_item['cat_name']}}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="nav-menu">
                <ul>
                    <a href="/"><li>首页</li></a>
                    @foreach(getPositionNav('middle') as $item)
                        <a href="{{$item['url']}}"><li>{{$item['name']}}</li></a>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

