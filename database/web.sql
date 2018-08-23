DROP TABLE IF EXISTS `sys_config`;
CREATE TABLE `sys_config` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `code` varchar(30) NOT NULL DEFAULT '' COMMENT '编码',
  `type` varchar(10) NOT NULL DEFAULT '' COMMENT '输入类型',
  `store_range` varchar(255) NOT NULL DEFAULT '' COMMENT '存储值范围',
  `store_dir` varchar(255) NOT NULL DEFAULT '' COMMENT '存储路径',
  `name` varchar(50) NOT NULL COMMENT '展示名称',
  `value` text NOT NULL COMMENT '值',
  `config_desc` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  `config_group` varchar(250) NOT NULL DEFAULT '' COMMENT '配置组',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统设置表';
insert into sys_config(parent_id,code,type,store_range,store_dir,name,value,config_desc,config_group) VALUES
(0, 'shop_info', 'group','','','平台信息','','',''),
(0, 'basic', 'group','','','基本信息','','',''),
(0, 'display', 'group','','','显示设置','','',''),
(0, 'path', 'group','','','路径设置','','',''),
(1, 'shop_name', 'text','','','平台名称','','',''),
(1, 'shop_title', 'text','','','商店标题','','商店的标题将显示在浏览器的标题栏',''),
(1, 'shop_desc', 'text','','','商店描述','','',''),
(1, 'shop_keywords', 'text','','','商店关键字','','',''),
(1, 'service_phone', 'text','','','客服电话','4000-000-000','',''),
(1, 'service_email', 'text','','','客服邮箱','kefu@company.com','',''),
(1, 'shop_closed', 'select','0|否,1|是','','暂时关闭网站','0','',''),
(1, 'close_comment', 'textarea','','','关闭的原因','','',''),
(1, 'shop_logo', 'file','','../logo/images/','商店 Logo','../logo/images/logo.gif','',''),
(1, 'individual_reg_closed', 'select','0|否,1|是','','是否关闭个人注册','0','',''),
(1, 'individual_reg_check', 'select','0|否,1|是','','个人注册是否需要审核','0','',''),
(1, 'individual_trade_closed', 'select','0|否,1|是','','是否关闭个人交易','1','',''),
(1, 'firm_reg_closed', 'select','0|否,1|是','','是否关闭企业注册','0','',''),
(1, 'firm_reg_check', 'select','0|否,1|是','','企业注册是否需要审核','1','',''),
(1, 'firm_stock_closed', 'select','0|否,1|是','','是否关闭企业库存管理','0','',''),
(1, 'copyright', 'text','','','版权','© 2018-2019 塑创电商 版权所有','',''),
(1, 'powered_by ', 'text','','','技术支持','塑创电商','',''),
(1, 'template', 'select','default|默认','','显示模板','default','',''),
(2, 'icp_number', 'text','','','ICP证书或ICP备案证书号','ICP00000123','',''),
(2, 'icp_file', 'file','','../cert/','ICP 备案证书文件','','',''),
(2, 'stats_code', 'textarea','','','统计代码','','您可以将其他访问统计服务商提供的代码添加到每一个页面。',''),
(2, 'individual_register_points', 'text','','','个人注册赠送积分','0','',''),
(2, 'firm_register_points', 'text','','','企业注册赠送积分','0','',''),
(2, 'upload_size_limit', 'select','0|不限,64|64KB,128|128KB,256|256KB,512|512KB,1024|1M,2048|2M,4096|4M','','附件上传大小','64','',''),
(2, 'visit_stats', 'select','0|关闭,1|开启','','站点访问统计','1','',''),
(3, 'search_keywords', 'text','','','首页搜索的关键字','周大福,内衣,Five Plus,手机','首页显示的搜索关键字,请用半角逗号(,)分隔多个关键字',''),
(3, 'date_format', 'text','','','日期格式','Y-m-d','',''),
(3, 'time_format', 'text','','','时间格式','Y-m-d H:i:s','',''),
(3, 'currency_format', 'text','','','货币格式','<em>¥</em>%s','',''),
(3, 'thumb_width', 'text','','','缩略图宽度','240','',''),
(3, 'thumb_height', 'text','','','缩略图高度','240','',''),
(4, 'site_domain', 'text','','','网站域名','','请输入您当前网站的域名，避免资源找不到（如：http://www.xxxx.com/）',''),
(4, 'article_path', 'text','','','文章资源路径','../article','',''),
(4, 'friend_link_path', 'text','','','友情链接路径','../friend_link','',''),
(4, 'firm_path', 'text','','','企业资质路径','../firm','','');

CREATE TABLE `friend_link` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `link_name` varchar(255) NOT NULL DEFAULT '' COMMENT '链接名称',
  `link_url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接URL',
  `link_logo` varchar(255) NOT NULL DEFAULT '' COMMENT '链接logo',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='友情链接';
insert into friend_link(link_name,link_url,link_logo)
VALUE('塑米城','http://www.sumibuy.com','');

CREATE TABLE `nav` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `is_show` tinyint(1) NOT NULL COMMENT '是否显示 0-否 1-是',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  `opennew` tinyint(1) NOT NULL COMMENT '是否新窗口 0-否 1-是',
  `url` varchar(255) NOT NULL COMMENT '链接地址',
  `type` varchar(10) NOT NULL COMMENT '显示位置 top-顶部 middle-中间 bottom-底部',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `is_show` (`is_show`),
  KEY `sort_order` (`sort_order`),
  KEY `opennew` (`opennew`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='导航栏设置表';

CREATE TABLE `seo` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keywords` varchar(255) NOT NULL COMMENT '关键词',
  `description` text NOT NULL COMMENT '描述',
  `type` varchar(20) NOT NULL COMMENT '类型',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='SEO设置表';
insert into seo(title,keywords,description,type) VALUES
('首页', '首页','首页','index'),
('文章分类列表', '文章分类列表','文章分类列表','article'),
('文章内容', '文章内容','文章内容','article_content'),
('商品', '商品','商品','goods'),
('品牌', '品牌','品牌','brand_list'),
('品牌商品列表', '品牌商品列表','品牌商品列表','brand'),
('分类', '分类','分类','category'),
('搜索', '搜索','搜索','search');

CREATE TABLE `article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cat_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '文章分类ID',
  `title` varchar(150) NOT NULL DEFAULT '' COMMENT '标题',
  `content` longtext NOT NULL COMMENT '正文',
  `author` varchar(30) NOT NULL DEFAULT '' COMMENT '作者',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示 0-否 1-是',
  `add_time` timestamp NOT NULL COMMENT '创建时间',
  `file_url` varchar(255) NOT NULL DEFAULT '' COMMENT '外部链接',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `sort_order` smallint(8) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `click` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击次数',
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`),
  KEY `is_show` (`is_show`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';
insert into article(cat_id,title,content,author,keywords,add_time,file_url) VALUES
(4,'订购方式','','','', now(), 'http://'),
(4,'购物流程','','','', now(), 'http://'),
(4,'售后流程','','','', now(), 'http://'),
(5,'上门自提','','','', now(), 'http://'),
(5,'支付方式说明','','','', now(), 'http://'),
(5,'配送支持','','','', now(), 'http://'),
(6,'联系方式','','','', now(), 'http://'),
(6,'网站故障报告','','','', now(), 'http://'),
(6,'投诉与建议','','','', now(), 'http://'),
(6,'我的订单','','','', now(), 'http://'),
(6,'我的收藏','','','', now(), 'http://'),
(7,'产品质量保障','','','', now(), 'http://'),
(7,'售后服务保障','','','', now(), 'http://'),
(7,'退换货原则','','','', now(), 'http://');

CREATE TABLE `article_cat` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cat_name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  PRIMARY KEY (`id`),
  KEY `sort_order` (`sort_order`),
  KEY `parent_id` (`parent_id`),
  KEY `cat_name` (`cat_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类表';
insert into article_cat(cat_name,sort_order,parent_id) VALUES
('帮助中心', '1','0'),
('新闻中心', '1','0'),
('系统分类', '1','1'),
('新手上路', '1','3'),
('配送与支付', '2','3'),
('联系我们', '3','3'),
('会员中心', '4','3'),
('服务保证', '5','3'),
('发票问题', '1','1');

CREATE TABLE `stats` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `access_time`  varchar(10) NOT NULL COMMENT '访问日期',
  `ip_address` varchar(15) NOT NULL DEFAULT '' COMMENT '用户IP',
  `visit_times` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '用户访问次数',
  `browser` varchar(60) NOT NULL DEFAULT '' COMMENT '用户浏览器',
  `system` varchar(20) NOT NULL DEFAULT '' COMMENT '用户操作系统',
  `area` varchar(30) NOT NULL DEFAULT '' COMMENT '用户所在城市',
  `referer_domain` varchar(100) NOT NULL DEFAULT '' COMMENT '来源域名',
  `referer_path` varchar(200) NOT NULL DEFAULT '' COMMENT '来源路径',
  `access_url` varchar(255) NOT NULL DEFAULT '' COMMENT '访问路径',
  PRIMARY KEY (`id`),
  KEY `access_time` (`access_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='统计表';

DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`is_freeze` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否冻结 0-否 1-是',
	`created_at` timestamp NOT NULL COMMENT '创建时间',
	`created_by` int(10) NOT NULL DEFAULT '0' COMMENT '创建人',
	`updated_at` timestamp DEFAULT NULL COMMENT '更新时间',
	`updated_by` int(10) NOT NULL DEFAULT '0' COMMENT '更新人',
	`user_name` varchar(32) NOT NULL COMMENT '账号',
	`password` varchar(100) DEFAULT NULL COMMENT '密码',
	`real_name` varchar(32) DEFAULT NULL COMMENT '真实姓名',
	`sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别 0-保密 1-男 2-女',
	`mobile` varchar(16) DEFAULT NULL COMMENT '手机号',
	`email` varchar(32) DEFAULT NULL COMMENT '邮件',
	`last_time` timestamp DEFAULT NULL COMMENT '上次登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '上次登录IP',
  `visit_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
	`is_super` tinyint(1) DEFAULT '0' COMMENT '是否为超级管理员',
  `avatar` varchar(500) DEFAULT '' COMMENT '头像',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';
insert into admin_user(created_at,updated_at,user_name,password,real_name,is_super)
value(now(),now(),'admin','$2y$10$3Jiq1ebcHWRzi5GjIFEgYutuQdRUZ0cUd67HhuuEkxKCgrsBAwUJm','超级管理员',1);

DROP TABLE IF EXISTS `admin_log`;
CREATE TABLE `admin_log` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`admin_id` int(10) NOT NULL COMMENT '会员ID',
	`real_name` varchar(60) NOT NULL DEFAULT '' COMMENT '真实名',
  `log_time` timestamp NOT NULL COMMENT '日志时间',
  `ip_address` varchar(15) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `log_info` varchar(255) NOT NULL DEFAULT '' COMMENT '日志信息',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员日志表';

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '登录用户名(手机)',
	`nick_name` varchar(60) NOT NULL DEFAULT '' COMMENT '昵称',
	`password` varchar(100) NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT '邮箱',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别 0-保密 1-男 2-女',
  `birthday` varchar(10) NOT NULL DEFAULT '' COMMENT '生日',
  `qq` varchar(20) NOT NULL DEFAULT '' COMMENT 'QQ号',
  `avatar` varchar(500) NOT NULL DEFAULT '' COMMENT '用户头像',
  `reg_time` timestamp NOT NULL COMMENT '注册时间',
  `last_time` timestamp DEFAULT NULL COMMENT '上次登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '上次登录IP',
  `visit_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '邀请人',
  `is_validated` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否通过审核 0-否 1-是',
  `is_freeze` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否冻结 0-否 1-是',
  `real_name` varchar(60) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `id_card` varchar(255) NOT NULL DEFAULT '' COMMENT '身份证号',
  `front_of_id_card` varchar(60) NOT NULL DEFAULT '' COMMENT '身份证正面',
  `reverse_of_id_card` varchar(60) NOT NULL DEFAULT '' COMMENT '身份证反面',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='个人会员表';

DROP TABLE IF EXISTS `user_log`;
CREATE TABLE `user_log` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`user_id` int(10) NOT NULL COMMENT '会员ID',
	`user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '登录用户名',
  `log_time` timestamp NOT NULL COMMENT '日志时间',
  `ip_address` varchar(15) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `log_info` varchar(255) NOT NULL DEFAULT '' COMMENT '日志信息',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='个人会员日志表';

CREATE TABLE `shipping_address` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `address_name` varchar(50) NOT NULL DEFAULT '' COMMENT '地址别名',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '个人用户ID',
  `firm_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '企业用户ID',
  `consignee` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人',
  `country` smallint(5) NOT NULL DEFAULT '0' COMMENT '国家',
  `province` smallint(5) NOT NULL DEFAULT '0' COMMENT '省',
  `city` smallint(5) NOT NULL DEFAULT '0' COMMENT '市',
  `district` smallint(5) NOT NULL DEFAULT '0' COMMENT '县',
  `street` smallint(5) NOT NULL DEFAULT '0' COMMENT '街道',
  `address` varchar(120) NOT NULL DEFAULT '' COMMENT '详细地址',
  `zipcode` varchar(60) NOT NULL DEFAULT '' COMMENT '邮编',
  `tel` varchar(60) NOT NULL DEFAULT '' COMMENT '电话号码',
  `mobile` varchar(60) NOT NULL DEFAULT '' COMMENT '手机号码',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收货地址表';

DROP TABLE IF EXISTS `firm`;
CREATE TABLE `firm` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`firm_name` varchar(60) NOT NULL DEFAULT '' COMMENT '企业全称',
	`user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '登录用户名',
	`password` varchar(60) NOT NULL DEFAULT '' COMMENT '密码',
  `contactName` varchar(255) NOT NULL DEFAULT '' COMMENT '负责人姓名',
  `contactPhone` varchar(255) NOT NULL DEFAULT '' COMMENT '负责人手机',
  `points` int(10) NOT NULL DEFAULT '0' COMMENT '企业可用积分',
  `attorney_letter_fileImg` varchar(255) NOT NULL DEFAULT '' COMMENT '授权委托书电子版',
  `business_license_id` varchar(255) NOT NULL DEFAULT '' COMMENT '营业执照注册号',
  `license_fileImg` varchar(255) NOT NULL DEFAULT '' COMMENT '营业执照副本电子版',
  `taxpayer_id` varchar(255) NOT NULL DEFAULT '' COMMENT '纳税人识别号',
  `reg_time` timestamp NOT NULL COMMENT '注册时间',
  `last_time` timestamp DEFAULT NULL COMMENT '上次登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '上次登录IP',
  `visit_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '访问次数',
  `is_validated` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否通过审核 0-否 1-是',
  `is_freeze` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否冻结 0-否 1-是',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业会员表';

DROP TABLE IF EXISTS `firm_log`;
CREATE TABLE `firm_log` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`firm_id` int(10) NOT NULL COMMENT '会员ID',
	`firm_name` varchar(60) NOT NULL DEFAULT '' COMMENT '登录用户名',
  `log_time` timestamp NOT NULL COMMENT '日志时间',
  `ip_address` varchar(15) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `log_info` varchar(255) NOT NULL DEFAULT '' COMMENT '日志信息',
  PRIMARY KEY (`id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业会员日志表';

DROP TABLE IF EXISTS `firm_blacklist`;
CREATE TABLE `firm_blacklist` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`firm_name` varchar(60) NOT NULL DEFAULT '' COMMENT '登录用户名',
	`taxpayer_id` varchar(255) NOT NULL DEFAULT '' COMMENT '纳税人识别号',
  `add_time` timestamp NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业黑名单表';

DROP TABLE IF EXISTS `firm_points_flow`;
CREATE TABLE `firm_points_flow` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`firm_id` int(10) NOT NULL COMMENT '会员ID',
	`change_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型 1-增加 2-减少',
  `change_time` timestamp NOT NULL COMMENT '变动时间',
  `points` int(10) NOT NULL DEFAULT '0' COMMENT '变动积分数',
  `change_info` varchar(255) NOT NULL DEFAULT '' COMMENT '变动信息',
  PRIMARY KEY (`id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业积分流水表';

DROP TABLE IF EXISTS `firm_stock`;
CREATE TABLE `firm_stock` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`firm_id` int(10) NOT NULL COMMENT '会员ID',
	`goods_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品ID',
	`goods_name` varchar(100) NOT NULL COMMENT '商品名称',
	`number` int(10) NOT NULL COMMENT '库存数',
  PRIMARY KEY (`id`),
  KEY `firm_id` (`firm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业库存表';

DROP TABLE IF EXISTS `firm_stock_flow`;
CREATE TABLE `firm_stock_flow` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`firm_id` int(10) NOT NULL COMMENT '企业会员ID',
	`partner_name` varchar(50) NOT NULL DEFAULT '' COMMENT '业务伙伴名称',
	`flow_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '流水类型 1-平台购物入库 2-其它入库 3-库存出库',
	`goods_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品ID',
	`goods_name` varchar(100) NOT NULL COMMENT '商品名称',
	`number` int(10) NOT NULL COMMENT '出入库数量',
  `flow_desc` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `flow_time` timestamp NOT NULL COMMENT '流水日期',
  `order_sn` varchar(20) NOT NULL DEFAULT '' COMMENT '订单号',
  `created_by` int(10) NOT NULL DEFAULT 0 COMMENT '创建人ID',
  PRIMARY KEY (`id`),
  KEY `firm_id` (`firm_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业库存流水表';

DROP TABLE IF EXISTS `firm_user`;
CREATE TABLE `firm_user` (
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`firm_id` int(10) NOT NULL COMMENT '企业会员ID',
	`user_id` int(10) NOT NULL COMMENT '个人会员ID',
	`real_name` varchar(20) NOT NULL DEFAULT '' COMMENT '员工真实姓名',
	`can_po` tinyint(1) NOT NULL DEFAULT '0' COMMENT '能采购 0-否 1-是',
	`can_pay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '能付款 0-否 1-是',
	`can_confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '能确认收货 0-否 1-是',
	`can_stock_in` tinyint(1) NOT NULL DEFAULT '0' COMMENT '能其他入库 0-否 1-是',
	`can_stock_out` tinyint(1) NOT NULL DEFAULT '0' COMMENT '能库存出库 0-否 1-是',
  PRIMARY KEY (`id`),
  KEY `firm_id` (`firm_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业用户表';

DROP TABLE IF EXISTS `region`;
CREATE TABLE `region` (
  `region_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `region_name` varchar(120) NOT NULL DEFAULT '' COMMENT '名称',
  `region_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '层级 0为国家级',
  PRIMARY KEY (`region_id`),
  KEY `parent_id` (`parent_id`),
  KEY `region_type` (`region_type`),
  KEY `region_name` (`region_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='省市区数据表';

DROP TABLE IF EXISTS `goods_category`;
CREATE TABLE `goods_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cat_name` varchar(90) NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级分类ID',
  `sort_order` smallint(8) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `is_nav_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示在导航条 0-否 1-是',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示 0-否 1-是',
  `cat_icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `is_top_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否在顶级分类页显示 0-否 1-是',
  `category_links` varchar(200) NOT NULL DEFAULT '' COMMENT '分类链接',
  `cat_alias_name` varchar(90) NOT NULL DEFAULT '' COMMENT '分类别名，多个之间用|分隔',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `is_show` (`is_show`),
  KEY `cat_name` (`cat_name`),
  KEY `is_nav_show` (`is_nav_show`),
  KEY `is_top_show` (`is_top_show`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品分类表';

CREATE TABLE `dsc_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `user_cat` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL,
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `bar_code` varchar(60) NOT NULL,
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `goods_name_style` varchar(60) NOT NULL DEFAULT '+',
  `click_count` int(10) unsigned NOT NULL DEFAULT '0',
  `brand_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `provider_name` varchar(100) NOT NULL DEFAULT '',
  `goods_number` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_weight` decimal(10,3) unsigned NOT NULL DEFAULT '0.000',
  `default_shipping` int(11) unsigned NOT NULL,
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `shop_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `promote_start_date` int(11) unsigned NOT NULL DEFAULT '0',
  `promote_end_date` int(11) unsigned NOT NULL DEFAULT '0',
  `warn_number` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `goods_brief` varchar(255) NOT NULL DEFAULT '',
  `goods_desc` text NOT NULL,
  `desc_mobile` text NOT NULL,
  `goods_thumb` varchar(255) NOT NULL DEFAULT '',
  `goods_img` varchar(255) NOT NULL DEFAULT '',
  `original_img` varchar(255) NOT NULL DEFAULT '',
  `is_real` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `is_on_sale` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_alone_sale` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `integral` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` smallint(4) unsigned NOT NULL DEFAULT '100',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_promote` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_volume` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_fullcut` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bonus_type_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_update` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_type` smallint(5) unsigned NOT NULL DEFAULT '0',
  `seller_note` varchar(255) NOT NULL DEFAULT '',
  `give_integral` int(11) NOT NULL DEFAULT '-1',
  `rank_integral` int(11) NOT NULL DEFAULT '-1',
  `suppliers_id` smallint(5) unsigned DEFAULT NULL,
  `is_check` tinyint(1) unsigned DEFAULT NULL,
  `store_hot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `store_new` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `store_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `group_number` smallint(8) unsigned NOT NULL DEFAULT '0',
  `is_xiangou` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否限购',
  `xiangou_start_date` int(11) NOT NULL DEFAULT '0' COMMENT '限购开始时间',
  `xiangou_end_date` int(11) NOT NULL DEFAULT '0' COMMENT '限购结束时间',
  `xiangou_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '限购设定数量',
  `review_status` tinyint(1) NOT NULL DEFAULT '1',
  `review_content` varchar(255) NOT NULL,
  `goods_shipai` text NOT NULL,
  `comments_number` int(10) unsigned NOT NULL DEFAULT '0',
  `sales_volume` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_num` int(10) unsigned NOT NULL DEFAULT '0',
  `model_price` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `model_inventory` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `model_attr` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `largest_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `pinyin_keyword` text,
  `goods_product_tag` varchar(2000) DEFAULT NULL,
  `goods_tag` varchar(255) DEFAULT NULL COMMENT '商品标签',
  `stages` varchar(512) NOT NULL DEFAULT '',
  `stages_rate` decimal(10,2) NOT NULL DEFAULT '0.50',
  `freight` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_unit` varchar(15) NOT NULL DEFAULT '个',
  `goods_cause` varchar(10) NOT NULL,
  `commission_rate` varchar(10) NOT NULL DEFAULT '0',
  `from_seller` int(11) NOT NULL DEFAULT '0',
  `user_brand` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '品牌统一使用平台品牌ID异步操作',
  `product_table` varchar(60) NOT NULL DEFAULT 'products',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品默认勾选属性货品',
  `product_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品默认勾选属性货品价格',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `product_promote_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `cloud_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cloud_goodsname` varchar(255) NOT NULL,
  `goods_video` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`goods_id`),
  KEY `goods_sn` (`goods_sn`),
  KEY `cat_id` (`cat_id`),
  KEY `last_update` (`last_update`),
  KEY `brand_id` (`brand_id`),
  KEY `goods_weight` (`goods_weight`),
  KEY `promote_end_date` (`promote_end_date`),
  KEY `promote_start_date` (`promote_start_date`),
  KEY `goods_number` (`goods_number`),
  KEY `sort_order` (`sort_order`),
  KEY `sales_volume` (`sales_volume`),
  KEY `xiangou_start_date` (`xiangou_start_date`),
  KEY `xiangou_end_date` (`xiangou_end_date`),
  KEY `user_id` (`user_id`),
  KEY `is_on_sale` (`is_on_sale`),
  KEY `is_alone_sale` (`is_alone_sale`),
  KEY `is_delete` (`is_delete`),
  KEY `user_cat` (`user_cat`),
  KEY `freight` (`freight`),
  KEY `tid` (`tid`),
  KEY `review_status` (`review_status`),
  KEY `user_brand` (`user_brand`),
  KEY `from_seller` (`from_seller`)
) ENGINE=MyISAM AUTO_INCREMENT=909 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `rec_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `session_id` varchar(255) DEFAULT NULL,
  `goods_id` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_sn` varchar(60) NOT NULL DEFAULT '',
  `product_id` varchar(255) NOT NULL,
  `group_id` varchar(255) NOT NULL,
  `goods_name` varchar(120) NOT NULL DEFAULT '',
  `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `goods_number` int(10) unsigned NOT NULL DEFAULT '0',
  `goods_attr` text NOT NULL,
  `is_real` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `extension_code` varchar(30) NOT NULL DEFAULT '',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rec_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_gift` int(10) unsigned NOT NULL DEFAULT '0',
  `is_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `can_handsel` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `model_attr` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `goods_attr_id` text NOT NULL,
  `ru_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shopping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `warehouse_id` int(10) unsigned NOT NULL DEFAULT '0',
  `area_id` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL,
  `stages_qishu` varchar(4) NOT NULL DEFAULT '-1',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `freight` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `shipping_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `store_mobile` varchar(20) NOT NULL,
  `take_time` timestamp NOT NULL DEFAULT '1000-01-01 00:00:00',
  `is_checked` tinyint(1) NOT NULL DEFAULT '1' COMMENT '选中状态，0未选中，1选中',
  `commission_rate` varchar(10) NOT NULL DEFAULT '0',
  `is_invalid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '购物车商品是否无效',
  PRIMARY KEY (`rec_id`),
  KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`),
  KEY `product_id` (`product_id`),
  KEY `is_real` (`is_real`),
  KEY `parent_id` (`parent_id`),
  KEY `is_shipping` (`is_shipping`),
  KEY `ru_id` (`ru_id`),
  KEY `store_id` (`store_id`),
  KEY `freight` (`freight`),
  KEY `tid` (`tid`),
  KEY `is_checked` (`is_checked`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `area_id` (`area_id`),
  KEY `is_gift` (`is_gift`),
  KEY `rec_type` (`rec_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车表';
