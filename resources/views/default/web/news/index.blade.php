@extends(themePath('.','web').'web.include.layouts.wall_news')
@section('title', '首页')
@section('style')
    <style>
        .crumbs {padding: 5px 0;overflow: hidden;clear: both;zoom: 1;}
        .crumbs a {padding: 0 5px;}
        .crumbs span {padding-left: 5px;}
        .today_news{width: 912px;height: auto;}
        .today_news_top{height: 50;line-height: 50px;border-bottom:2px solid #75b335;}
        .today_news_list li{border-bottom: 1px solid #DEDEDE;margin-top:10px;overflow: hidden;}
        .news_content{width: 600px;margin-top: 10px;color: #666;}

        .news_pages{margin: 20px auto;}
        .news_pages ul.pagination {text-align: center;}
        .news_pages ul.pagination li {display: inline-block;}
        .news_pages ul.pagination li a {color: #ccc;float: left;padding: 4px 16px;text-decoration: none;transition: background-color .3s;
            border: 1px solid #ddd;margin: 0 4px;}
        .news_pages ul.pagination li a.active {color: #75b335;border: 1px solid #75b335;}
        .news_pages ul.pagination li a:hover:not(.active) {color: #75b335;border: 1px solid #75b335;}

        .today_news_search{width: 272px;overflow: hidden;}
        .search_input{height: 38px;line-height: 38px;box-sizing: border-box;padding: 6px;}
        .search_btn{width: 65px;height: 38px;line-height: 38px;}
        .news_center {width: 245px;margin: 10px auto;}
        .news_center li{line-height: 45px;border-bottom: 1px dashed #DEDEDE;}
        .news_center li a{display: block;}
        .news_center li:last-child{border-bottom: none;}

        .news_Hot{width: 245px;margin: 10px auto;}
        .news_Hot li{color:#999;font-size:16px;line-height: 35px;height: 35px;border: none;display: block;}
        .news_Hot li a:hover{text-decoration: underline;color: #ff6f17;}
        .news_list_num{width: 18px;line-height: 18px;margin-top:8px;text-align: center;color: #fff;}
        .cdbg{background-color: #cdcdcd;}

        .code_greenbg{background-color: #75b335;}
    </style>
@endsection
@section('content')
    <div class="today_news whitebg fl">
        <h1 class="today_news_top ovh"><span class="fs16 ml15 fl">今日资讯</span><span class="fr mr10">共<span class="orange">16</span>条数据</span></h1>
        <ul class="ovh ml15 today_news_list mt15">
            <li>
                <div class="fl mb15"><img src="img/new_img01.png"/></div>
                <div class="fl ml20">
                    <h1 class="fs18 mt10">科学家揭露真相</h1>
                    <div class="mt30 gray"><span class="ovh">时间：2018-09-21</span><span class="ml25">浏览量：20</span><span class="ml25">来源：秣宝网</span></div>
                    <p class="news_content ovhwp">2013年12月17日美国权威性学术杂志，美国内科协会年鉴杂志上，出现了</p>
                </div>
            </li>
            <li>
                <div class="fl mb15"><img src="img/new_img01.png"/></div>
                <div class="fl ml20">
                    <h1 class="fs18 mt10">科学家揭露真相</h1>
                    <div class="mt30 gray"><span class="ovh">时间：2018-09-21</span><span class="ml25">浏览量：20</span><span class="ml25">来源：秣宝网</span></div>
                    <p class="news_content ovhwp">2013年12月17日美国权威性学术杂志，美国内科协会年鉴杂志上，出现了</p>
                </div>
            </li>
            <li>
                <div class="fl mb15"><img src="img/new_img01.png"/></div>
                <div class="fl ml20">
                    <h1 class="fs18 mt10">科学家揭露真相</h1>
                    <div class="mt30 gray"><span class="ovh">时间：2018-09-21</span><span class="ml25">浏览量：20</span><span class="ml25">来源：秣宝网</span></div>
                    <p class="news_content ovhwp">2013年12月17日美国权威性学术杂志，美国内科协会年鉴杂志上，出现了</p>
                </div>
            </li>
            <li>
                <div class="fl mb15"><img src="img/new_img01.png"/></div>
                <div class="fl ml20">
                    <h1 class="fs18 mt10">科学家揭露真相</h1>
                    <div class="mt30 gray"><span class="ovh">时间：2018-09-21</span><span class="ml25">浏览量：20</span><span class="ml25">来源：秣宝网</span></div>
                    <p class="news_content ovhwp">2013年12月17日美国权威性学术杂志，美国内科协会年鉴杂志上，出现了</p>
                </div>
            </li>
        </ul>
        <!--页码-->
        <div class="news_pages">
            <ul class="pagination">
                <li><a href="#">首页</a></li>
                <li><a href="#">上一页</a></li>
                <li><a href="#">1</a></li>
                <li><a class="active" href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">下一页</a></li>
                <li><a href="#">尾页</a></li>
            </ul>
        </div>
    </div>
@endsection
