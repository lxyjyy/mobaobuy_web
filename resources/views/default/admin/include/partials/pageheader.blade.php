<div class="admin-header clearfix" style="min-width:1280px;">
    <div class="bgSelector"></div>
    <div class="admin-logo">
        <a href="/admin/home"  data-param="home" target="workspace">
            <img style="max-height: 40px" src="{{getFileUrl(getConfig('shop_logo', asset('images/logo.png')))}}">
        </a>
        <div class="foldsider"><i class="icon icon-indent-right"></i></div>
    </div>
    <div class="module-menu">
        <ul>
            <li data-param="menuplatform"><a href="javascript:void(0);">平台</a></li>
            <li data-param="menushopping"><a href="javascript:void(0);">商城</a></li>
        {{--    <li data-param="finance"><a href="javascript:void(0);">财务</a></li>
            <li data-param="third_party"><a href="javascript:void(0);">第三方服务</a></li>--}}
        </ul>
    </div>
    <div class="admin-header-right">
        <div class="manager">
            <dl>
                <dt class="name">{{session('_admin_user_info')['user_name']}}</dt>
                <dd class="group">{{session('_admin_user_info')['real_name']}}</dd>
            </dl>
            <span class="avatar">
				<form action="index.php" id="fileForm" method="post" enctype="multipart/form-data">
					<input name="img" type="file" class="admin-avatar-file" id="_pic" title="设置管理员头像">
				</form>
				<img nctype="admin_avatar" src="{{getFileUrl(getNotEmptyValue(session('_admin_user_info'),'avatar',asset('images/admin.png')))}}">
			</span>
            <div id="admin-manager-btn" class="admin-manager-btn"><i class="arrow"></i></div>
            <div class="manager-menu">
                <div class="title">
                    <h4>最后登录</h4>
                    <a href="privilege.php?act=edit&amp;id=60" target="workspace" class="edit_pwd">修改密码</a>
                </div>
                <div class="login-date">
                    <strong>{{session('_admin_user_info')['last_time']}}</strong>
                    <span>(IP:{{getNotEmptyValue(session('_admin_user_info'),'last_ip','0.0.0.0')}})</span>
                </div>
                <div class="title mt10">
                    <h4>常用操作</h4>
                    <a href="javascript:;" class="add_nav">添加菜单</a>
                </div>
                <div class="quick_link">
                    <ul>
                    </ul>
                </div>
            </div>
        </div>
        <div class="operate">
            <li style="position: relative;" ectype="oper_msg">
                <a href="javascript:void(0);" class="msg" title="查看消息">&nbsp;</a><s id="total">0</s>
                <div id="msg_Container">
                    <div class="item">
                        <h3 class="order_msg" ectype="msg_tit">订单提示<em class="iconfont icon-up"></em></h3><s id="order_total">0</s>
                        <div class="msg_content" ectype="orderMsg" style="display:block;">
                            <p>
                                <a href="javascript:void(0);" data-url="/admin/orderinfo/list" data-param="menushopping|02_order_list" target="workspace" class="message">您有新订单</a>
                                <span class="tiptool">（<em id="new_orders">0</em>）</span>
                            </p>
                            <p>
                                <a href="javascript:void(0);" data-url="/admin/orderinfo/list" data-param="menushopping|02_order_list" target="workspace" class="message">待发货订单</a>
                                <span class="tiptool">（<em id="no_paid">0</em>）</span>
                            </p>

                        </div>
                    </div>



                    <div class="item">
                        <h3 class="shop_msg" ectype="msg_tit">商家审核提示<em class="iconfont icon-down"></em></h3><s id="shop_total">0</s>
                        <div class="msg_content" ectype="sellerMsg">
                            <p>
                                <a href="javascript:void(0);" data-url="/admin/shop/list" data-param="menushopping|02_merchants_users_list" target="workspace" class="message">未审核商家</a>
                                <span class="tiptool">（<em id="shop_account">0</em>）</span>
                            </p>
                        </div>
                    </div>

                    <div class="item">
                        <h3 class="user_msg" ectype="msg_tit">会员提醒<em class="iconfont icon-down"></em></h3><s id="user_total">0</s>
                        <div class="msg_content" ectype="userMsg">
                            <p>
                                <a href="javascript:void(0);" data-url="/admin/user/list?is_firm=-1" data-param="menuplatform|03_users_list" target="workspace" class="message">会员实名认证</a>
                                <span class="tiptool">（<em id="user_account">0</em>）</span>
                            </p>
                        </div>
                    </div>

                    <div class="item">
                        <h3 class="campaign_msg" ectype="msg_tit">活动提醒<em class="iconfont icon-down"></em></h3><s id="activity_total">0</s>
                        <div class="msg_content" ectype="promotionMsg">

                            <p>
                                <a href="javascript:void(0);" data-url="/admin/activity/wholesale" data-param="menushopping|08_group_buy" target="workspace" class="message">集采拼团</a>
                                <span class="tiptool">（<em id="activity_promote">0</em>）</span>
                            </p>

                            <p>
                                <a href="javascript:void(0);" data-url="/admin/promote/list" data-param="menushopping|12_favourable" target="workspace" class="message">优惠活动</a>
                                <span class="tiptool">（<em id="activity_wholesale">0</em>）</span>
                            </p>
                            <p>
                                <a href="javascript:void(0);" data-url="/admin/activity/consign" data-param="menushopping|16_presale" target="workspace" class="message">清仓特卖</a>
                                <span class="tiptool">（<em id="activity_bargin">0</em>）</span>
                            </p>


                        </div>
                    </div>
                </div>
            </li>
            <i></i>
            <li><a href="{{asset('')}}" target="_blank" class="home" title="新窗口打开商城首页">&nbsp;</a></li>
            <i></i>
            <li><a href="{{asset('admin/clear')}}" class="clear" target="workspace" title="清除缓存">&nbsp;</a></li>
            <i></i>
            <li><a href="javascript:void(0);" class="prompt" id="out"  title="安全退出管理中心">&nbsp;</a></li>
        </div>
    </div>
</div>
<script>
    $("#out").click(function(){
        window.location.href="/admin/logout";
    });

    window.onload=function(){
        let order_waitAffirm = 0; //待商家确认
        let order_waitSend = 0;//待发货
        let shop_waitvalidate = 0;//待审核商家
        let user_certification= 0;//待实名审核
        let activity_promote = 0;//优惠活动
        let activity_consign = 0;//清仓特卖
        let activity_wholesale = 0;//集采拼团
        let order_total = 0;//订单提示总量
        let shop_total = 0;//商家提示总量
        let user_total = 0;//会员提示总量
        let activity_total = 0;//活动提示总量
        $.ajax({
            url: "/admin/getActivityCount",
            dataType: "json",
            data:{},
            type:"POST",
            success:function(res){
                if(res.code==200){
                    data = res.data;
                    activity_promote = data.promote_count;
                    activity_consign = data.consign_count;
                    activity_wholesale = data.wholesale_count;
                    order_waitAffirm = data.order_status.waitAffirm;
                    order_waitSend = data.order_status.waitSend;
                    shop_waitvalidate = data.shop_waitvalidate;
                    user_certification = data.user_certification;
                    $("#new_orders").text(order_waitAffirm);
                    $("#no_paid").text(order_waitSend);
                    $("#order_total").text(order_waitAffirm+order_waitSend);
                    order_total = order_waitAffirm+order_waitSend;

                    $("#shop_account").text(shop_waitvalidate);
                    $("#shop_total").text(shop_waitvalidate);
                    shop_total = shop_waitvalidate;

                    $("#user_account").text(user_certification);
                    $("#user_total").text(user_certification);
                    user_total = user_certification;

                    $("#activity_promote").text(activity_promote);
                    $("#activity_consign").text(activity_consign);
                    $("#activity_wholesale").text(activity_wholesale);
                    activity_total = parseInt(activity_promote)+parseInt(activity_consign)+parseInt(activity_wholesale);
                    $("#activity_total").text(activity_total);
                    $("#total").text(order_total+shop_total+user_total+activity_total);
                }else{

                }
            }
        });
        setInterval(function(){
            let order_waitAffirm = 0; //待商家确认
            let order_waitSend = 0;//待发货
            let shop_waitvalidate = 0;//待审核商家
            let user_certification= 0;//待实名审核
            let activity_promote = 0;//优惠活动
            let activity_consign = 0;//清仓特卖
            let activity_wholesale = 0;//集采拼团
            let order_total = 0;//订单提示总量
            let shop_total = 0;//商家提示总量
            let user_total = 0;//会员提示总量
            let activity_total = 0;//活动提示总量
            $.ajax({
                url: "/admin/getActivityCount",
                dataType: "json",
                data:{},
                type:"POST",
                success:function(res){
                    if(res.code==200){
                        data = res.data;
                        console.log(data);
                        activity_promote = data.promote_count;
                        activity_consign = data.consign_count;
                        activity_wholesale = data.wholesale_count;
                        order_waitAffirm = data.order_status.waitAffirm;
                        order_waitSend = data.order_status.waitSend;
                        shop_waitvalidate = data.shop_waitvalidate;
                        user_certification = data.user_certification;
                        $("#new_orders").text(order_waitAffirm);
                        $("#no_paid").text(order_waitSend);
                        $("#order_total").text(order_waitAffirm+order_waitSend);
                        order_total = order_waitAffirm+order_waitSend;

                        $("#shop_account").text(shop_waitvalidate);
                        $("#shop_total").text(shop_waitvalidate);
                        shop_total = shop_waitvalidate;

                        $("#user_account").text(user_certification);
                        $("#user_total").text(user_certification);
                        user_total = user_certification;

                        $("#activity_promote").text(activity_promote);
                        $("#activity_consign").text(activity_consign);
                        $("#activity_wholesale").text(activity_wholesale);
                        activity_total = parseInt(activity_promote)+parseInt(activity_consign)+parseInt(activity_wholesale);
                        $("#activity_total").text(activity_total);
                        $("#total").text(order_total+shop_total+user_total+activity_total);
                    }else{

                    }
                }
            });
        }, 1000*60*3);


    }
</script>