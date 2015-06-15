# Identify: MTQzMzk4ODQ5NCxNQyAzLjQsMQ==
# <?exit();?>
# Modoer bakfile Multi-Volume Data Dump Vol.1
# Version: MC 3.4
# Time: 2015-06-11 10:08
# Website: http://www.modoer.com
# --------------------------------------------------------


SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary;

DROP TABLE IF EXISTS modoer_activity;
CREATE TABLE `modoer_activity` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `dateline` int(10) unsigned NOT NULL default '0',
  `uid` mediumint(8) NOT NULL default '0',
  `username` varchar(16) NOT NULL default '',
  `reviews` smallint(5) unsigned NOT NULL default '0',
  `subjects` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`aid`),
  KEY `reviews` (`reviews`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_adv_list;
CREATE TABLE `modoer_adv_list` (
  `adid` mediumint(8) unsigned NOT NULL auto_increment,
  `apid` smallint(5) unsigned NOT NULL default '0',
  `city_id` mediumint(8) unsigned NOT NULL default '0',
  `adname` varchar(60) NOT NULL default '',
  `sort` enum('word','flash','code','img') NOT NULL,
  `begintime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `config` text NOT NULL,
  `code` text NOT NULL,
  `attr` char(10) NOT NULL default '',
  `ader` varchar(255) NOT NULL default '',
  `adtel` varchar(255) NOT NULL default '',
  `ademail` varchar(255) NOT NULL default '',
  `enabled` char(1) NOT NULL default 'Y',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`adid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

INSERT INTO modoer_adv_list VALUES ('1','1','0','Modoer2.0发布','img','1289232000','1433692800','a:5:{s:9:\"img_title\";s:7:\"Modoer2\";s:7:\"img_src\";s:38:\"/uploads/adv/2010-12/13_1292772219.jpg\";s:9:\"img_width\";s:3:\"708\";s:10:\"img_height\";s:2:\"75\";s:8:\"img_href\";s:22:\"http://www.modoer.com/\";}','<a href=\"http://www.modoer.com/\" alt=\"Modoer2\" target=\"_blank\"><img src=\"/uploads/adv/2010-12/13_1292772219.jpg\" width=\"708\" height=\"75\" /></a>','','','','','Y','0');
INSERT INTO modoer_adv_list VALUES ('2','2','0','Modoer2.0发布','img','1289232000','1433692800','a:5:{s:9:\"img_title\";s:7:\"Modoer2\";s:7:\"img_src\";s:38:\"/uploads/adv/2010-12/29_1292772237.jpg\";s:9:\"img_width\";s:3:\"958\";s:10:\"img_height\";s:2:\"90\";s:8:\"img_href\";s:22:\"http://www.modoer.com/\";}','<a href=\"http://www.modoer.com/\" alt=\"Modoer2\" target=\"_blank\"><img src=\"/uploads/adv/2010-12/29_1292772237.jpg\" width=\"958\" height=\"90\" /></a>','','','','','Y','0');

DROP TABLE IF EXISTS modoer_adv_place;
CREATE TABLE `modoer_adv_place` (
  `apid` smallint(5) unsigned NOT NULL auto_increment,
  `templateid` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `des` varchar(255) NOT NULL default '',
  `template` text NOT NULL,
  `enabled` char(1) NOT NULL default 'Y',
  PRIMARY KEY  (`apid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 AUTO_INCREMENT=6;

INSERT INTO modoer_adv_place VALUES ('1','0','首页_中部广告','首页推荐主题下方广告位','<div class=\"ix_foo\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>$ad[code]</div>\r\n{/get}\r\n</div>','Y');
INSERT INTO modoer_adv_place VALUES ('2','0','新闻首页_广告','新闻模块的首页中午长条图片广告','<div class=\"art_ix\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>$ad[code]</div>\r\n{/get}\r\n</div>','Y');
INSERT INTO modoer_adv_place VALUES ('3','0','主题内容页_点评间广告','在主题内容页坐下点评列表第二行加入的广告','<div style=\"padding-bottom:10px;border-bottom:1px dashed #ddd;margin-bottom:10px;\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div style=\"text-align:center;\">$ad[code]</div>\r\n{getempty(ad)}\r\n<center>广告位招租</center>\r\n{/get}\r\n</div>','Y');
INSERT INTO modoer_adv_place VALUES ('4','0','主题列表页_列表间广告','在主题模块的列表页面，列表第二层加入一个广告','<div style=\"padding-bottom:5px;border-bottom:1px dashed #ddd;margin:5px 0;\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div style=\"text-align:center;\">$ad[code]</div>\r\n{getempty(ad)}\r\n<center>广告位招租</center>\r\n{/get}\r\n</div>','Y');
INSERT INTO modoer_adv_place VALUES ('5','0','商城_首页通栏','产品商城首页模版，分类下面一栏960px','{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>$ad[code]</div>\r\n{getempty(ad)}\r\n<div>960*60通栏广告位一</div>\r\n{/get}','Y');

DROP TABLE IF EXISTS modoer_album;
CREATE TABLE `modoer_album` (
  `albumid` mediumint(8) unsigned NOT NULL auto_increment,
  `city_id` smallint(5) unsigned NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(200) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `des` text NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL default '0',
  `num` mediumint(5) unsigned NOT NULL default '0',
  `pageview` mediumint(8) unsigned NOT NULL default '0',
  `comments` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`albumid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_album VALUES ('1','1','1','李兰兰默认相册','uploads/pictures/2015-06/thumb_15_1433743836.jpg','','1433743836','1','1','0');

DROP TABLE IF EXISTS modoer_announcements;
CREATE TABLE `modoer_announcements` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `city_id` smallint(5) NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `orders` smallint(5) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `author` varchar(50) NOT NULL default '',
  `pageview` int(10) NOT NULL default '0',
  `dateline` int(10) NOT NULL default '0',
  `available` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_area;
CREATE TABLE `modoer_area` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `attid` mediumint(8) unsigned NOT NULL default '0',
  `domain` varchar(20) NOT NULL default '',
  `initial` char(1) NOT NULL default '',
  `name` varchar(16) NOT NULL default '',
  `mappoint` varchar(50) NOT NULL default '',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  `templateid` smallint(5) unsigned NOT NULL default '0',
  `enabled` tinyint(1) unsigned NOT NULL default '1',
  `config` text NOT NULL,
  PRIMARY KEY  (`aid`),
  KEY `pid` (`pid`),
  KEY `level` (`level`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 AUTO_INCREMENT=8;

INSERT INTO modoer_area VALUES ('1','0','4','hz','H','杭州','121.565151,29.877309','1','0','0','1','a:4:{s:9:\"mapapikey\";s:0:\"\";s:8:\"sitename\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}');
INSERT INTO modoer_area VALUES ('2','1','5','','','西湖区','','2','0','0','1','');
INSERT INTO modoer_area VALUES ('5','2','8','','','灵隐街道','','3','0','0','1','');

DROP TABLE IF EXISTS modoer_article_category;
CREATE TABLE `modoer_article_category` (
  `catid` smallint(5) unsigned NOT NULL auto_increment,
  `pid` smallint(5) NOT NULL default '0',
  `name` varchar(20) NOT NULL default '',
  `listorder` smallint(5) NOT NULL default '0',
  `total` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

INSERT INTO modoer_article_category VALUES ('1','0','默认分类','0','0');
INSERT INTO modoer_article_category VALUES ('2','1','默认子分类','0','0');

DROP TABLE IF EXISTS modoer_article_data;
CREATE TABLE `modoer_article_data` (
  `articleid` mediumint(8) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`articleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_article_data VALUES ('1','\r\n            \r\n            \r\n        \r\n        <div class=\"content\">\r\n            <h3>商铺功能</h3>\r\n            <ul><li>可建立多板块的点评，例如（餐饮，旅游，购物，娱乐，服务等）</li><li>每个板块可以分类，并按类别输出信息（如餐饮板块可以建立火锅，海鲜等，出行/旅游板块可以建立汽车，旅行社\r\n等）</li><li>商铺可以设置，商铺名称，分店名称，主营菜系，地址，电话，手机，店铺标签(Tag)，并可增加分店</li><li>商家可通过认领功能，来管理自己的点评</li><li>商铺自定义风格功能</li><li>会员可补充商铺信息</li><li>已有商铺可增加分店</li><li>商铺可以根据环境，产品或者其他补充图片集展示，图片支持缩略图，水印功能</li><li>可自定义设置商铺封面</li><li>所有会员的提交信息可自动提交和后台管理审核</li><li>自定义城市区域，可以精确到街道</li><li>地图标注和地图报错功能</li><li>商铺视频功能</li><li>会员去过，想去的互动</li></ul>\r\n            <h3>点评功能</h3>\r\n            <ul><li>商铺可以针对各个板块可以自定义点评项名称和评分项数量），喜欢程度，人均消费，消费感受，适合类型进行点评，\r\n会员并可推荐产品以及设置店铺Tag，其他会员可以对点评进行献花和回应，反馈，举报点评</li><li>会员并可推荐产品以及设置店铺 Tag</li><li>其他会员可以对点评进行赠送鲜花和回应，反馈</li><li>可举报点评</li></ul>\r\n            <h3>会员卡模块</h3>\r\n            <ul><li>可自定义设置会员卡名称</li><li>可设置会员卡在商铺的折扣或者优惠活动和备注说明</li><li>可设置推荐加盟商家</li></ul>\r\n            <h3>兑奖中心模块</h3>\r\n            <ul><li>会员可通过点评，登记，回应等一系列互动操作得到金币积分，利用这些积分可对话相应积分的奖品</li><li>后台可添加和设置奖品以及奖品说明</li></ul>\r\n            <h3>优惠券模块</h3>\r\n            <ul><li>会员可上传优惠券，可供其他会员下载打印优惠券</li><li>后台可设置优惠券审核</li><li>其他会员可投票是否优惠券是否有用</li></ul>\r\n            <h3>新闻咨询模块</h3>\r\n            <ul><li>发布新闻资讯</li><li>商家可发布店铺的咨询信息</li><li>其他会员可投票是否优惠券是否有用</li></ul>\r\n            <h3>排行榜功能</h3>\r\n            <ul><li>餐厅排行（最佳餐厅、口味最佳、环境最佳、服务最佳）</li><li>最新餐厅（近一周加入、近一月加入、近三月加入）</li></ul>\r\n            <h3>会员功能</h3>\r\n            <ul><li>会员短信功能</li><li>个人主页功能（可以设置、更改个人主页名称和风格）</li><li>好友设置功能</li><li>个人留言版功能</li><li>会员积分功能</li><li>会员鲜花功能</li><li>收藏夹功能</li><li>积分等级</li></ul>\r\n            <h3>模块功能</h3>\r\n            <ul><li>Modoer系统以模块为基础组成</li><li>可以Modoer为平台开发安装模块</li></ul>\r\n            <h3>高级数据调用</h3>\r\n            <ul><li>利用内置的函数和SQL调用方式调用数据</li><li>可设置每个调用的模板和空数据的模板</li><li>调用数据可设置缓存，较少系统数据库资源消耗</li><li>支持本地和JS方式调用数据</li><li>\r\n			<br /></li></ul>\r\n			<h3>插件功能</h3>\r\n            <ul><li>利用插件接口可丰富系统功能</li><li>集成提供多个插件（图片轮换，网站公告）</li></ul>\r\n			<h3>系统整合</h3>\r\n			<ul><li>万能整合API，可与任何PHP程序进行整合</li><li>整合UCenter（账户，短信，好友，积分兑换，Feed推送，个人空间跳转UCH）</li></ul>\r\n			<h3>其他功能</h3>\r\n			<ul><li>词语过滤可设置不同的过滤方式：阻止，替换，审核</li><li>菜单管理可自定义模板显示的菜单，不需要再修改模板</li><li>伪静态功能优化SEO</li></ul>\r\n        </div>');

DROP TABLE IF EXISTS modoer_articles;
CREATE TABLE `modoer_articles` (
  `articleid` mediumint(8) unsigned NOT NULL auto_increment,
  `city_id` smallint(5) unsigned NOT NULL default '0',
  `catid` smallint(5) unsigned NOT NULL default '0',
  `sid` varchar(255) NOT NULL default '',
  `dateline` int(10) NOT NULL default '0',
  `att` tinyint(1) NOT NULL default '0',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  `havepic` tinyint(1) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `author` varchar(20) NOT NULL default '',
  `subject` varchar(60) NOT NULL default '',
  `keywords` varchar(100) NOT NULL default '',
  `pageview` mediumint(8) unsigned NOT NULL default '0',
  `grade` tinyint(1) unsigned NOT NULL default '0',
  `digg` mediumint(8) NOT NULL default '0',
  `closed_comment` tinyint(1) unsigned NOT NULL default '0',
  `comments` mediumint(8) unsigned NOT NULL default '0',
  `copyfrom` varchar(200) NOT NULL default '',
  `introduce` varchar(255) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `picture` varchar(255) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '1',
  `checker` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`articleid`),
  KEY `sid` (`sid`),
  KEY `uid` (`uid`),
  KEY `city_id` (`city_id`,`catid`),
  KEY `att` (`att`,`status`,`listorder`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_articles VALUES ('1','0','2','0','1275267913','1','0','0','0','admin','Modoer 点评系统','','3','0','0','0','0','','Modoer 是一款以本地分享，多功能的点评网站管理系统。采用 PHP+MYSQL 开发设计，开放全部源代码。因具有非凡的访问速度和卓越的负载能力而深受国内外朋友的喜爱。','','','1','');

DROP TABLE IF EXISTS modoer_att_cat;
CREATE TABLE `modoer_att_cat` (
  `catid` mediumint(8) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `des` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_att_list;
CREATE TABLE `modoer_att_list` (
  `attid` mediumint(8) unsigned NOT NULL auto_increment,
  `type` varchar(20) NOT NULL default '',
  `catid` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(20) NOT NULL default '',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  `icon` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`attid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 AUTO_INCREMENT=14;

INSERT INTO modoer_att_list VALUES ('1','category','1','美食','0','');
INSERT INTO modoer_att_list VALUES ('11','category','4','婚礼管家分类','0','');
INSERT INTO modoer_att_list VALUES ('4','area','1','宁波','0','');
INSERT INTO modoer_att_list VALUES ('5','area','2','江东区','0','');
INSERT INTO modoer_att_list VALUES ('8','area','5','天伦广场','0','');
INSERT INTO modoer_att_list VALUES ('13','category','6','婚礼套餐','0','');
INSERT INTO modoer_att_list VALUES ('12','category','5','婚礼套餐','0','');

DROP TABLE IF EXISTS modoer_bcastr;
CREATE TABLE `modoer_bcastr` (
  `bcastr_id` smallint(3) unsigned NOT NULL auto_increment,
  `city_id` smallint(5) unsigned NOT NULL default '0',
  `groupname` varchar(15) NOT NULL default 'index',
  `available` tinyint(1) NOT NULL default '1',
  `itemtitle` varchar(100) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `item_url` varchar(255) NOT NULL default '',
  `orders` smallint(3) NOT NULL default '0',
  PRIMARY KEY  (`bcastr_id`),
  KEY `groupname` (`groupname`,`available`,`orders`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AUTO_INCREMENT=4;

INSERT INTO modoer_bcastr VALUES ('1','0','index','1','Modoer点评系统','uploads/bcastr/25_1275267815.jpg','http://www.modoer.com','1');
INSERT INTO modoer_bcastr VALUES ('2','0','mobile','1','幻灯','uploads/bcastr/2015-06/91_1433924437.jpg','#','1');
INSERT INTO modoer_bcastr VALUES ('3','0','mobile','1','幻灯2','uploads/bcastr/2015-06/21_1433924450.jpg','#','2');

DROP TABLE IF EXISTS modoer_card_apply;
CREATE TABLE `modoer_card_apply` (
  `applyid` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `linkman` varchar(20) NOT NULL default '',
  `tel` varchar(20) NOT NULL default '',
  `mobile` varchar(20) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `postcode` varchar(10) NOT NULL default '',
  `num` smallint(5) unsigned NOT NULL default '1',
  `coin` int(10) NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `comment` text NOT NULL,
  `checker` varchar(30) NOT NULL default '',
  `checktime` int(10) NOT NULL default '0',
  `checkmsg` text NOT NULL,
  PRIMARY KEY  (`applyid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_card_discounts;
CREATE TABLE `modoer_card_discounts` (
  `sid` mediumint(8) unsigned NOT NULL auto_increment,
  `cardsort` enum('both','largess','discount') NOT NULL default 'discount',
  `discount` decimal(4,1) NOT NULL default '0.0',
  `largess` varchar(100) NOT NULL default '',
  `exception` varchar(255) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `available` tinyint(1) NOT NULL default '1',
  `finer` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`sid`),
  KEY `available` (`available`),
  KEY `finer` (`finer`,`available`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_category;
CREATE TABLE `modoer_category` (
  `catid` mediumint(8) unsigned NOT NULL auto_increment,
  `pid` mediumint(8) NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `modelid` smallint(5) NOT NULL default '0',
  `review_opt_gid` smallint(5) unsigned NOT NULL default '0',
  `attid` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(20) NOT NULL default '',
  `total` int(10) unsigned NOT NULL default '0',
  `config` text NOT NULL,
  `listorder` smallint(5) NOT NULL default '0',
  `enabled` tinyint(1) unsigned NOT NULL default '1',
  `subcats` varchar(255) NOT NULL,
  `nonuse_subcats` varchar(255) NOT NULL,
  PRIMARY KEY  (`catid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 AUTO_INCREMENT=7;

INSERT INTO modoer_category VALUES ('1','0','1','1','0','1','婚礼管家','0','a:37:{s:10:\"enable_add\";s:1:\"0\";s:11:\"relate_root\";s:1:\"0\";s:9:\"gusetbook\";s:1:\"0\";s:11:\"guest_gbook\";s:1:\"0\";s:5:\"forum\";s:1:\"0\";s:18:\"auto_subject_owner\";s:1:\"0\";s:13:\"subject_apply\";s:1:\"0\";s:19:\"subject_apply_uppic\";s:1:\"0\";s:24:\"subject_apply_uppic_name\";s:0:\"\";s:9:\"useeffect\";s:1:\"0\";s:7:\"effect1\";s:6:\"去过\";s:7:\"effect2\";s:6:\"想去\";s:13:\"use_subbranch\";s:1:\"0\";s:18:\"allow_edit_subject\";s:1:\"0\";s:17:\"subject_add_thumb\";s:1:\"0\";s:21:\"use_review_upload_pic\";s:1:\"0\";s:8:\"useprice\";s:1:\"0\";s:17:\"useprice_required\";s:1:\"0\";s:14:\"useprice_title\";s:12:\"人均消费\";s:13:\"useprice_unit\";s:7:\"元/人\";s:13:\"repeat_review\";s:1:\"0\";s:17:\"repeat_review_num\";s:1:\"0\";s:18:\"repeat_review_time\";s:1:\"0\";s:12:\"guest_review\";s:1:\"0\";s:12:\"voice_review\";s:1:\"0\";s:9:\"itemcheck\";s:1:\"0\";s:11:\"reviewcheck\";s:1:\"0\";s:12:\"picturecheck\";s:1:\"0\";s:14:\"guestbookcheck\";s:1:\"1\";s:10:\"templateid\";s:1:\"2\";s:19:\"detail_picture_hide\";s:1:\"0\";s:19:\"detail_content_hide\";s:1:\"0\";s:11:\"displaytype\";s:6:\"normal\";s:9:\"listorder\";s:5:\"finer\";s:4:\"icon\";s:0:\"\";s:13:\"meta_keywords\";s:12:\"婚礼管家\";s:16:\"meta_description\";s:12:\"婚礼管家\";}','0','1','4','');
INSERT INTO modoer_category VALUES ('4','1','2','1','0','11','婚礼管家分类','1','','0','1','','');
INSERT INTO modoer_category VALUES ('5','0','1','2','1','12','婚礼套餐','0','a:0:{}','0','1','6','');
INSERT INTO modoer_category VALUES ('6','5','2','2','0','13','婚礼套餐','1','','0','1','','');

DROP TABLE IF EXISTS modoer_comment;
CREATE TABLE `modoer_comment` (
  `cid` mediumint(8) unsigned NOT NULL auto_increment,
  `idtype` varchar(30) NOT NULL default '',
  `id` mediumint(8) unsigned NOT NULL default '0',
  `reply_cid` mediumint(8) unsigned NOT NULL default '0' COMMENT '回复评论的ID',
  `reply_user` varchar(255) NOT NULL default '',
  `root_cid` mediumint(8) unsigned NOT NULL default '0' COMMENT '回复评论的根ID',
  `root_subtotal` int(10) unsigned NOT NULL default '0' COMMENT '根总数（即root_cid=0）',
  `grade` tinyint(2) NOT NULL default '0',
  `effect1` int(10) unsigned NOT NULL default '0',
  `effect2` int(10) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `dateline` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  `extra_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `idtype` (`idtype`,`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_config;
CREATE TABLE `modoer_config` (
  `variable` varchar(32) NOT NULL default '',
  `value` text NOT NULL,
  `module` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`variable`,`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_config VALUES ('point','a:12:{s:11:\"add_subject\";a:7:{s:5:\"point\";i:15;s:6:\"point1\";i:15;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:10:\"add_review\";a:7:{s:5:\"point\";i:10;s:6:\"point1\";i:10;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_picture\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:13:\"add_guestbook\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_respond\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:14:\"update_subject\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:13:\"report_review\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_article\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:10:\"add_coupon\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:12:\"print_coupon\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_comment\";a:7:{s:5:\"point\";i:2;s:6:\"point1\";i:2;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:3:\"reg\";a:7:{s:5:\"point\";i:20;s:6:\"point1\";i:20;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}}','member');
INSERT INTO modoer_config VALUES ('point_group','a:6:{s:6:\"point1\";a:6:{s:7:\"enabled\";s:1:\"1\";s:4:\"name\";s:6:\"金币\";s:4:\"unit\";s:3:\"个\";s:2:\"in\";s:1:\"1\";s:3:\"out\";s:1:\"1\";s:4:\"rate\";s:0:\"\";}s:6:\"point2\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point3\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point4\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point5\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point6\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}}','member');
INSERT INTO modoer_config VALUES ('siteclose','0','modoer');
INSERT INTO modoer_config VALUES ('icpno','','modoer');
INSERT INTO modoer_config VALUES ('sitename','Modoer点评系统','modoer');
INSERT INTO modoer_config VALUES ('seccode','0','modoer');
INSERT INTO modoer_config VALUES ('useripaccess','','modoer');
INSERT INTO modoer_config VALUES ('adminipaccess','','modoer');
INSERT INTO modoer_config VALUES ('ban_ip','','modoer');
INSERT INTO modoer_config VALUES ('gzipcompress','0','modoer');
INSERT INTO modoer_config VALUES ('scriptinfo','1','modoer');
INSERT INTO modoer_config VALUES ('picture_upload_size','2000','modoer');
INSERT INTO modoer_config VALUES ('watermark','1','modoer');
INSERT INTO modoer_config VALUES ('jstransfer','1','modoer');
INSERT INTO modoer_config VALUES ('jsaccess','','modoer');
INSERT INTO modoer_config VALUES ('googlesearch','0','modoer');
INSERT INTO modoer_config VALUES ('googlesearch_website','modoer.com','modoer');
INSERT INTO modoer_config VALUES ('tplext','.htm','modoer');
INSERT INTO modoer_config VALUES ('mapapi','http://api.map.baidu.com/api?v=2.0&ak=yourkey','modoer');
INSERT INTO modoer_config VALUES ('datacall_dir','./data/datacall','modoer');
INSERT INTO modoer_config VALUES ('datacall_clearinterval','1','modoer');
INSERT INTO modoer_config VALUES ('datacall_cleartime','1','modoer');
INSERT INTO modoer_config VALUES ('search_limit','60','modoer');
INSERT INTO modoer_config VALUES ('search_maxspm','20','modoer');
INSERT INTO modoer_config VALUES ('search_maxresults','500','modoer');
INSERT INTO modoer_config VALUES ('search_cachelife','3600','modoer');
INSERT INTO modoer_config VALUES ('rewrite','0','modoer');
INSERT INTO modoer_config VALUES ('rewritecompatible','1','modoer');
INSERT INTO modoer_config VALUES ('subname','多功能点评系统','modoer');
INSERT INTO modoer_config VALUES ('titlesplit',',','modoer');
INSERT INTO modoer_config VALUES ('meta_keywords','Meta Keywords','modoer');
INSERT INTO modoer_config VALUES ('meta_description','Meta Description','modoer');
INSERT INTO modoer_config VALUES ('headhtml','','modoer');
INSERT INTO modoer_config VALUES ('templateid','0','member');
INSERT INTO modoer_config VALUES ('editor_relativeurl','1','modoer');
INSERT INTO modoer_config VALUES ('page_cachetime','0','modoer');
INSERT INTO modoer_config VALUES ('console_menuid','3','modoer');
INSERT INTO modoer_config VALUES ('closereg','0','member');
INSERT INTO modoer_config VALUES ('censoruser','*admin*\r\n*管理员*','member');
INSERT INTO modoer_config VALUES ('existsemailreg','0','member');
INSERT INTO modoer_config VALUES ('salutatory','1','member');
INSERT INTO modoer_config VALUES ('salutatory_msg','尊敬的$username：\r\n\r\n欢迎您加入$sitename大家庭！\r\n祝你在$sitename过得愉快！\r\n\r\n$sitename运营团队\r\n$time','member');
INSERT INTO modoer_config VALUES ('showregrule','1','member');
INSERT INTO modoer_config VALUES ('regrule','这里填写新用户的注册协议！','member');
INSERT INTO modoer_config VALUES ('pic_width','200','item');
INSERT INTO modoer_config VALUES ('pic_height','150','item');
INSERT INTO modoer_config VALUES ('video_width','250','item');
INSERT INTO modoer_config VALUES ('video_height','200','item');
INSERT INTO modoer_config VALUES ('review_min','10','review');
INSERT INTO modoer_config VALUES ('review_max','1500','review');
INSERT INTO modoer_config VALUES ('respond_min','10','review');
INSERT INTO modoer_config VALUES ('respond_max','500','review');
INSERT INTO modoer_config VALUES ('avatar_review','0','review');
INSERT INTO modoer_config VALUES ('pcatid','9','item');
INSERT INTO modoer_config VALUES ('list_num','20','item');
INSERT INTO modoer_config VALUES ('review_num','5','review');
INSERT INTO modoer_config VALUES ('respond_num','5','review');
INSERT INTO modoer_config VALUES ('classorder','order','item');
INSERT INTO modoer_config VALUES ('thumb','2','item');
INSERT INTO modoer_config VALUES ('show_thumb','1','item');
INSERT INTO modoer_config VALUES ('show_thumb_sort','small','item');
INSERT INTO modoer_config VALUES ('mapapi_charset','','modoer');
INSERT INTO modoer_config VALUES ('main_menuid','1','modoer');
INSERT INTO modoer_config VALUES ('respondcheck','0','item');
INSERT INTO modoer_config VALUES ('pid','1','item');
INSERT INTO modoer_config VALUES ('closenote','正在升级，请稍后访问...','modoer');
INSERT INTO modoer_config VALUES ('gbook','1','space');
INSERT INTO modoer_config VALUES ('gbook_guest','1','space');
INSERT INTO modoer_config VALUES ('gbook_seccode','1','space');
INSERT INTO modoer_config VALUES ('templateid','0','space');
INSERT INTO modoer_config VALUES ('recordguest','1','space');
INSERT INTO modoer_config VALUES ('spacename','{username}的个人空间','space');
INSERT INTO modoer_config VALUES ('spacedescribe','读万卷书模，行万里路！','space');
INSERT INTO modoer_config VALUES ('index_reviews','5','space');
INSERT INTO modoer_config VALUES ('index_gbooks','5','space');
INSERT INTO modoer_config VALUES ('reviews','10','space');
INSERT INTO modoer_config VALUES ('gbooks','10','space');
INSERT INTO modoer_config VALUES ('seccode_review','0','review');
INSERT INTO modoer_config VALUES ('seccode_picupload','1','item');
INSERT INTO modoer_config VALUES ('seccode_guestbook','0','item');
INSERT INTO modoer_config VALUES ('seccode_respond','1','review');
INSERT INTO modoer_config VALUES ('templateid','1','modoer');
INSERT INTO modoer_config VALUES ('foot_menuid','66','modoer');
INSERT INTO modoer_config VALUES ('scoretype','10','review');
INSERT INTO modoer_config VALUES ('decimalpoint','2','review');
INSERT INTO modoer_config VALUES ('seccode_review_guest','1','review');
INSERT INTO modoer_config VALUES ('seccode_subject','0','item');
INSERT INTO modoer_config VALUES ('tag_split_sp','1','item');
INSERT INTO modoer_config VALUES ('menuid','80','space');
INSERT INTO modoer_config VALUES ('space_menuid','80','space');
INSERT INTO modoer_config VALUES ('multi_upload_pic','1','item');
INSERT INTO modoer_config VALUES ('multi_upload_pic_num','10','item');
INSERT INTO modoer_config VALUES ('console_seccode','0','modoer');
INSERT INTO modoer_config VALUES ('console_total','1','modoer');
INSERT INTO modoer_config VALUES ('guest_post','0','comment');
INSERT INTO modoer_config VALUES ('member_seccode','0','comment');
INSERT INTO modoer_config VALUES ('guest_seccode','0','comment');
INSERT INTO modoer_config VALUES ('disable_comment','0','comment');
INSERT INTO modoer_config VALUES ('guest_comment','0','comment');
INSERT INTO modoer_config VALUES ('check_comment','0','comment');
INSERT INTO modoer_config VALUES ('filter_word','1','comment');
INSERT INTO modoer_config VALUES ('list_num','5','comment');
INSERT INTO modoer_config VALUES ('hidden_comment','0','comment');
INSERT INTO modoer_config VALUES ('comment_interval','5','comment');
INSERT INTO modoer_config VALUES ('mapflag','baidu','modoer');
INSERT INTO modoer_config VALUES ('seccode_reg','0','member');
INSERT INTO modoer_config VALUES ('seccode_login','0','member');
INSERT INTO modoer_config VALUES ('mail_debug','0','modoer');
INSERT INTO modoer_config VALUES ('ownernews','1','exchange');
INSERT INTO modoer_config VALUES ('ownernews_classid','1','exchange');
INSERT INTO modoer_config VALUES ('ownernews_check','0','exchange');
INSERT INTO modoer_config VALUES ('thumb_w','160','exchange');
INSERT INTO modoer_config VALUES ('thumb_h','100','exchange');
INSERT INTO modoer_config VALUES ('exchange_seccode','1','exchange');
INSERT INTO modoer_config VALUES ('keywords','礼品兑换,兑奖中心','exchange');
INSERT INTO modoer_config VALUES ('description','礼品兑换模块用户会员使用金币兑换网站提供的礼品','exchange');
INSERT INTO modoer_config VALUES ('picture_createthumb_level','80','modoer');
INSERT INTO modoer_config VALUES ('keywords','新闻模块','article');
INSERT INTO modoer_config VALUES ('description','文章信息，发布网站信息和主题资讯','article');
INSERT INTO modoer_config VALUES ('editor_image','1','article');
INSERT INTO modoer_config VALUES ('rss','1','article');
INSERT INTO modoer_config VALUES ('owner_post','1','article');
INSERT INTO modoer_config VALUES ('member_post','0','article');
INSERT INTO modoer_config VALUES ('post_check','1','article');
INSERT INTO modoer_config VALUES ('post_filter','1','article');
INSERT INTO modoer_config VALUES ('list_num','20','article');
INSERT INTO modoer_config VALUES ('owner_category','0','article');
INSERT INTO modoer_config VALUES ('member_category','0','article');
INSERT INTO modoer_config VALUES ('post_seccode','0','article');
INSERT INTO modoer_config VALUES ('member_bysubject','0','article');
INSERT INTO modoer_config VALUES ('meta_keywords','新闻模块','article');
INSERT INTO modoer_config VALUES ('meta_description','文章信息，发布网站信息和主题资讯','article');
INSERT INTO modoer_config VALUES ('post_comment','1','article');
INSERT INTO modoer_config VALUES ('att_custom','1|头条(默认显示2条)\r\n2|文字推荐(默认显示7条)\r\n3|图片推荐(默认显示3条)\r\n4|模块首页图片轮换(不宜过多)','article');
INSERT INTO modoer_config VALUES ('meta_keywords','兑奖中心','exchange');
INSERT INTO modoer_config VALUES ('meta_description','兑奖中心模块，用于消费金币','exchange');
INSERT INTO modoer_config VALUES ('map_view_level','15','modoer');
INSERT INTO modoer_config VALUES ('guestbook_min','10','item');
INSERT INTO modoer_config VALUES ('guestbook_max','50','item');
INSERT INTO modoer_config VALUES ('content_min','10','comment');
INSERT INTO modoer_config VALUES ('content_max','200','comment');
INSERT INTO modoer_config VALUES ('meta_keywords','友情链接','link');
INSERT INTO modoer_config VALUES ('meta_description','Modoer点评系统的友情链接模块！','link');
INSERT INTO modoer_config VALUES ('num_logo','5','link');
INSERT INTO modoer_config VALUES ('num_char','20','link');
INSERT INTO modoer_config VALUES ('open_apply','1','link');
INSERT INTO modoer_config VALUES ('apply','1','card');
INSERT INTO modoer_config VALUES ('applyseccode','1','card');
INSERT INTO modoer_config VALUES ('coin','10','card');
INSERT INTO modoer_config VALUES ('applynum','2','card');
INSERT INTO modoer_config VALUES ('applydes','这里填写申请提交时，显示给会员看的申请说明和条例。','card');
INSERT INTO modoer_config VALUES ('subtitle','最优惠的消费折扣卡','card');
INSERT INTO modoer_config VALUES ('meta_keywords','会员卡','card');
INSERT INTO modoer_config VALUES ('meta_description','modoer点评系统会员卡模块','card');
INSERT INTO modoer_config VALUES ('check','1','coupon');
INSERT INTO modoer_config VALUES ('post_item_owner','1','coupon');
INSERT INTO modoer_config VALUES ('watermark','1','coupon');
INSERT INTO modoer_config VALUES ('thumb_width','160','coupon');
INSERT INTO modoer_config VALUES ('thumb_height','100','coupon');
INSERT INTO modoer_config VALUES ('seccode','1','coupon');
INSERT INTO modoer_config VALUES ('listnum','10','coupon');
INSERT INTO modoer_config VALUES ('des','这是是优惠券发布的保证说明！','coupon');
INSERT INTO modoer_config VALUES ('subtitle','最优优惠','coupon');
INSERT INTO modoer_config VALUES ('meta_keywords','优惠券模块','coupon');
INSERT INTO modoer_config VALUES ('meta_description','Modoer点评系统之优惠券模块','coupon');
INSERT INTO modoer_config VALUES ('post_comment','1','coupon');
INSERT INTO modoer_config VALUES ('picture_createthumb_mod','0','modoer');
INSERT INTO modoer_config VALUES ('watermark_postion','0','modoer');
INSERT INTO modoer_config VALUES ('picture_ext','jpg jpeg png gif','modoer');
INSERT INTO modoer_config VALUES ('select_city','1','article');
INSERT INTO modoer_config VALUES ('copyright','&#169; 2007 - 2011 <a href=\"http://www.modoer.com\" target=\"_blank\">陌风软件</a> 版权所有','modoer');
INSERT INTO modoer_config VALUES ('buildinfo','1','modoer');
INSERT INTO modoer_config VALUES ('statement','免责声明：站内会员言论仅代表个人观点，并不代表本站同意其观点，本站不承担由此引起的法律责任。','modoer');
INSERT INTO modoer_config VALUES ('feed_enable','1','member');
INSERT INTO modoer_config VALUES ('watermark_text','Modoer点评系统','modoer');
INSERT INTO modoer_config VALUES ('city_id','1','modoer');
INSERT INTO modoer_config VALUES ('picture_max_width','800','modoer');
INSERT INTO modoer_config VALUES ('picture_max_height','600','modoer');
INSERT INTO modoer_config VALUES ('city_ip_location','0','modoer');
INSERT INTO modoer_config VALUES ('index_digst_rand_num','2','review');
INSERT INTO modoer_config VALUES ('index_pk_rand_num','1','review');
INSERT INTO modoer_config VALUES ('index_show_bad_review','1','review');
INSERT INTO modoer_config VALUES ('index_review_num','5','review');
INSERT INTO modoer_config VALUES ('index_review_gettype','rand','review');
INSERT INTO modoer_config VALUES ('content_min','10','article');
INSERT INTO modoer_config VALUES ('content_max','50000','article');
INSERT INTO modoer_config VALUES ('citypath_without','index/announcement\r\nfenlei/detail\r\nparty/detail\r\nask/detail\r\ntuan/detail\r\nproduct/detail\r\narticle/detail\r\nitem/detail\r\ncoupon/detail\r\nreview/detail\r\nexchange/gift\r\nspace/*\r\ngroup/*','modoer');
INSERT INTO modoer_config VALUES ('sellgroup_pointtype','point1','member');
INSERT INTO modoer_config VALUES ('sellgroup_useday','30','member');
INSERT INTO modoer_config VALUES ('passport_login','0','member');
INSERT INTO modoer_config VALUES ('passport_pw','0','member');
INSERT INTO modoer_config VALUES ('registered_again','0','member');
INSERT INTO modoer_config VALUES ('email_verify','0','member');
INSERT INTO modoer_config VALUES ('mobile_verify','0','member');
INSERT INTO modoer_config VALUES ('mobile_verify_message','$sitename 用户手机认证验证码：$serial','member');
INSERT INTO modoer_config VALUES ('sldomain','0','item');
INSERT INTO modoer_config VALUES ('base_sldomain','','item');
INSERT INTO modoer_config VALUES ('reserve_sldomain','','item');
INSERT INTO modoer_config VALUES ('selltpl_pointtype','point1','item');
INSERT INTO modoer_config VALUES ('selltpl_useday','180','item');
INSERT INTO modoer_config VALUES ('seccode_review','0','item');
INSERT INTO modoer_config VALUES ('seccode_review_guest','0','item');
INSERT INTO modoer_config VALUES ('review_min','10','item');
INSERT INTO modoer_config VALUES ('review_max','2000','item');
INSERT INTO modoer_config VALUES ('respond_min','10','item');
INSERT INTO modoer_config VALUES ('respond_max','500','item');
INSERT INTO modoer_config VALUES ('avatar_review','0','item');
INSERT INTO modoer_config VALUES ('search_location','0','item');
INSERT INTO modoer_config VALUES ('album_comment','1','item');
INSERT INTO modoer_config VALUES ('ajax_taoke','0','item');
INSERT INTO modoer_config VALUES ('review_num','','item');
INSERT INTO modoer_config VALUES ('show_detail_vs_review','0','item');
INSERT INTO modoer_config VALUES ('close_detail_total','0','item');
INSERT INTO modoer_config VALUES ('list_filter_li_collapse_num','','item');
INSERT INTO modoer_config VALUES ('pointgroup','point1','exchange');
INSERT INTO modoer_config VALUES ('pointgroup','point1','card');
INSERT INTO modoer_config VALUES ('city_sldomain','0','modoer');
INSERT INTO modoer_config VALUES ('utf8url','0','modoer');
INSERT INTO modoer_config VALUES ('picture_dir_mod','MONTH','modoer');
INSERT INTO modoer_config VALUES ('meta_keywords','','review');
INSERT INTO modoer_config VALUES ('meta_description','','review');
INSERT INTO modoer_config VALUES ('respondcheck','1','review');
INSERT INTO modoer_config VALUES ('tag_split_sp','0','review');
INSERT INTO modoer_config VALUES ('default_grade','0','review');
INSERT INTO modoer_config VALUES ('digest_price','10','review');
INSERT INTO modoer_config VALUES ('digest_pointtype','point1','review');
INSERT INTO modoer_config VALUES ('digest_gain','','review');
INSERT INTO modoer_config VALUES ('cmds','help,my,nearby,search,welcome','weixin');
INSERT INTO modoer_config VALUES ('version','MC 3.4','modoer');
INSERT INTO modoer_config VALUES ('build','20141229','modoer');
INSERT INTO modoer_config VALUES ('authkey','QVFC9212JL9X','modoer');
INSERT INTO modoer_config VALUES ('siteurl','http://www.5wed.com','modoer');
INSERT INTO modoer_config VALUES ('jscache_flag','688','article');
INSERT INTO modoer_config VALUES ('jscache_flag','833','item');
INSERT INTO modoer_config VALUES ('jscache_flag_area','736','modoer');
INSERT INTO modoer_config VALUES ('ajax_post_subject','0','item');
INSERT INTO modoer_config VALUES ('gourd_enabled','0','item');
INSERT INTO modoer_config VALUES ('gourd_buy_point','10','item');
INSERT INTO modoer_config VALUES ('gourd_buy_pointtype','','item');
INSERT INTO modoer_config VALUES ('gourd_condition','10','item');
INSERT INTO modoer_config VALUES ('gourd_total_min','5','item');
INSERT INTO modoer_config VALUES ('gourd_total_max','10','item');
INSERT INTO modoer_config VALUES ('gourd_point','10','item');
INSERT INTO modoer_config VALUES ('gourd_pointtype','','item');
INSERT INTO modoer_config VALUES ('use_nearby','0','item');
INSERT INTO modoer_config VALUES ('use_recycle','1','item');
INSERT INTO modoer_config VALUES ('taoke_appkey','','item');
INSERT INTO modoer_config VALUES ('taoke_appsecret','','item');
INSERT INTO modoer_config VALUES ('taoke_sessionkey','','item');
INSERT INTO modoer_config VALUES ('taoke_nick','','item');
INSERT INTO modoer_config VALUES ('show_qrcode','0','item');
INSERT INTO modoer_config VALUES ('item_album_mode','normal','item');
INSERT INTO modoer_config VALUES ('item_album_order','normal','item');
INSERT INTO modoer_config VALUES ('templateid','0','mobile');
INSERT INTO modoer_config VALUES ('auto_enter','1','mobile');
INSERT INTO modoer_config VALUES ('auto_switch','1','mobile');
INSERT INTO modoer_config VALUES ('add_closed','1','mylist');
INSERT INTO modoer_config VALUES ('ownernews','1','product');
INSERT INTO modoer_config VALUES ('ownernews_classid','1','product');
INSERT INTO modoer_config VALUES ('ownernews_check','0','product');
INSERT INTO modoer_config VALUES ('jscache_flag','102','product');
INSERT INTO modoer_config VALUES ('pointgroup','point1','product');
INSERT INTO modoer_config VALUES ('cash_rate','10','product');
INSERT INTO modoer_config VALUES ('giveintegral_percent','','product');
INSERT INTO modoer_config VALUES ('integral_acctype','1','product');
INSERT INTO modoer_config VALUES ('brokerage_enable','0','product');
INSERT INTO modoer_config VALUES ('brokerage','0','product');
INSERT INTO modoer_config VALUES ('brokerage_add_shipfee','0','product');
INSERT INTO modoer_config VALUES ('send_sms','0','product');
INSERT INTO modoer_config VALUES ('enablebuy','yes','product');
INSERT INTO modoer_config VALUES ('seccode_product','0','product');
INSERT INTO modoer_config VALUES ('product_copy_enable','0','product');
INSERT INTO modoer_config VALUES ('check_product','0','product');
INSERT INTO modoer_config VALUES ('post_comment','0','product');
INSERT INTO modoer_config VALUES ('thumb_width','','product');
INSERT INTO modoer_config VALUES ('thumb_height','','product');
INSERT INTO modoer_config VALUES ('use_itemtpl','0','product');
INSERT INTO modoer_config VALUES ('list_filter_li_collapse_num','','product');

DROP TABLE IF EXISTS modoer_coupon_category;
CREATE TABLE `modoer_coupon_category` (
  `catid` smallint(8) unsigned NOT NULL auto_increment,
  `name` varchar(40) NOT NULL default '',
  `num` mediumint(9) NOT NULL default '0',
  `listorder` smallint(5) NOT NULL default '0',
  PRIMARY KEY  (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 AUTO_INCREMENT=6;

INSERT INTO modoer_coupon_category VALUES ('1','美食','0','0');
INSERT INTO modoer_coupon_category VALUES ('2','购物','0','0');
INSERT INTO modoer_coupon_category VALUES ('3','休闲','0','0');
INSERT INTO modoer_coupon_category VALUES ('4','女性','0','0');
INSERT INTO modoer_coupon_category VALUES ('5','摄影','0','0');

DROP TABLE IF EXISTS modoer_coupon_print;
CREATE TABLE `modoer_coupon_print` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `couponid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  `point` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `couponid` (`couponid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_coupons;
CREATE TABLE `modoer_coupons` (
  `couponid` mediumint(8) unsigned NOT NULL auto_increment,
  `city_id` smallint(5) NOT NULL default '0',
  `catid` smallint(5) unsigned NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `picture` varchar(255) NOT NULL default '',
  `starttime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `subject` varchar(100) NOT NULL default '',
  `des` varchar(50) NOT NULL default '',
  `content` text NOT NULL,
  `effect1` mediumint(8) unsigned NOT NULL default '0',
  `prints` mediumint(8) unsigned NOT NULL default '0',
  `comments` mediumint(8) unsigned NOT NULL default '0',
  `sms_text` varchar(255) NOT NULL default '',
  `send_num` int(10) NOT NULL default '0',
  `grade` smallint(5) unsigned NOT NULL default '0',
  `flag` tinyint(1) unsigned NOT NULL default '1',
  `dateline` int(10) unsigned NOT NULL default '0',
  `pageview` int(10) NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `closed_comment` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`couponid`),
  KEY `sid` (`sid`),
  KEY `uid` (`uid`),
  KEY `city_id` (`city_id`,`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_datacall;
CREATE TABLE `modoer_datacall` (
  `callid` smallint(5) unsigned NOT NULL auto_increment,
  `module` varchar(60) NOT NULL default '',
  `calltype` varchar(60) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `fun` varchar(60) NOT NULL default '',
  `var` varchar(60) NOT NULL default '',
  `cachetime` mediumint(8) unsigned NOT NULL default '0',
  `expression` text NOT NULL,
  `tplname` varchar(200) NOT NULL default '',
  `empty_tplname` varchar(200) NOT NULL default '',
  `closed` tinyint(1) unsigned NOT NULL default '0',
  `hash` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`callid`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 AUTO_INCREMENT=17;

INSERT INTO modoer_datacall VALUES ('5','item','sql','主题_会员参与','sql','mydata','1000','a:6:{s:4:\"from\";s:72:\"{dbpre}membereffect_total mt LEFT JOIN {dbpre}subject s ON (mt.id=s.sid)\";s:6:\"select\";s:58:\"mt.{field:effect} as effect,s.sid,s.catid,s.name,s.subname\";s:5:\"where\";s:77:\"mt.idtype={idtype} AND mt.{field:effect}>0 AND s.city_id IN ({array:city_id})\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:22:\"mt.{field:effect} DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_effect_li','empty_li','0','');
INSERT INTO modoer_datacall VALUES ('8','item','sql','主题_相关主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:52:\"sid,pid,catid,name,subname,avgsort,pageviews,reviews\";s:5:\"where\";s:72:\"city_id IN ({array:city_id}) and name={name} and status=1 and sid!={sid}\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:12:\"addtime DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_li','empty_li','0','');
INSERT INTO modoer_datacall VALUES ('6','item','sql','主题_同类主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:52:\"sid,pid,catid,name,subname,avgsort,pageviews,reviews\";s:5:\"where\";s:74:\"city_id IN ({array:city_id}) and catid={catid} and status=1 and sid!={sid}\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:12:\"addtime DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_li','empty_li','0','');
INSERT INTO modoer_datacall VALUES ('7','item','sql','主题_附近主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:52:\"sid,pid,catid,name,subname,avgsort,pageviews,reviews\";s:5:\"where\";s:37:\"aid={aid} and status=1 and sid!={sid}\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:12:\"addtime DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_li','empty_li','0','');
INSERT INTO modoer_datacall VALUES ('11','item','sql','首页_推荐主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:46:\"sid,aid,name,subname,avgsort,thumb,description\";s:5:\"where\";s:67:\"city_id IN ({array:city_id}) AND pid={pid} AND status=1 AND finer>0\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:10:\"finer DESC\";s:5:\"limit\";s:3:\"0,8\";}','index_subject_finer','empty_div','0','');
INSERT INTO modoer_datacall VALUES ('16','product','sql','产品_主题产品','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}product\";s:6:\"select\";s:59:\"pid,catid,subject,grade,description,thumb,comments,pageview\";s:5:\"where\";s:22:\"sid={sid} AND status=1\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:24:\"grade DESC,comments DESC\";s:5:\"limit\";s:4:\"0,10\";}','product_pic_li','empty_li','0','');

DROP TABLE IF EXISTS modoer_dbcache;
CREATE TABLE `modoer_dbcache` (
  `cache_key` char(200) NOT NULL COMMENT '缓存键名',
  `cache_value` mediumtext NOT NULL COMMENT '缓存内容',
  `update_time` int(10) unsigned NOT NULL default '0' COMMENT '上次更新时间',
  `expire_time` int(10) unsigned NOT NULL default '0' COMMENT '缓存过期时间：0无限期',
  PRIMARY KEY  (`cache_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_dbcache VALUES ('comm_session_online','16	1433987945','1433987945','0');
INSERT INTO modoer_dbcache VALUES ('comm_task_nexttime','1434040200','1433987785','0');
INSERT INTO modoer_dbcache VALUES ('comm_session_expire','1433988388','1433988388','0');
INSERT INTO modoer_dbcache VALUES ('comm_dbcache_expire','1433987661','1433987661','0');

DROP TABLE IF EXISTS modoer_digest_pay;
CREATE TABLE `modoer_digest_pay` (
  `payid` mediumint(8) unsigned NOT NULL auto_increment,
  `id` mediumint(8) unsigned NOT NULL default '0',
  `idtype` char(15) NOT NULL default '',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` char(20) NOT NULL default '',
  `price` int(10) unsigned NOT NULL default '0',
  `pointtype` enum('point1','point2','point3','point4','point5','point6') NOT NULL default 'point1',
  `dateline` int(10) NOT NULL default '0',
  `gain_uid` mediumint(8) NOT NULL default '0',
  `gain_price` int(10) NOT NULL default '0',
  PRIMARY KEY  (`payid`),
  KEY `id` (`id`,`idtype`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_exchange_category;
CREATE TABLE `modoer_exchange_category` (
  `catid` smallint(8) unsigned NOT NULL auto_increment,
  `name` varchar(40) NOT NULL default '',
  `num` mediumint(9) NOT NULL default '0',
  `listorder` smallint(5) NOT NULL default '0',
  PRIMARY KEY  (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_exchange_category VALUES ('1','默认分类','0','0');

DROP TABLE IF EXISTS modoer_exchange_gifts;
CREATE TABLE `modoer_exchange_gifts` (
  `giftid` mediumint(8) unsigned NOT NULL auto_increment,
  `catid` smallint(5) unsigned NOT NULL default '0',
  `sid` smallint(5) unsigned NOT NULL default '0',
  `city_id` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(200) NOT NULL default '',
  `sort` tinyint(1) unsigned NOT NULL default '1',
  `pattern` tinyint(1) unsigned NOT NULL default '1',
  `reviewed` tinyint(1) unsigned NOT NULL default '0',
  `available` tinyint(1) NOT NULL default '0',
  `starttime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `randomcodelen` tinyint(1) NOT NULL default '0',
  `randomcode` varchar(50) NOT NULL default '',
  `displayorder` tinyint(3) NOT NULL default '0',
  `description` text NOT NULL,
  `price` int(10) unsigned NOT NULL default '0',
  `point` int(10) unsigned NOT NULL default '0',
  `point3` int(10) unsigned NOT NULL default '0',
  `point4` int(10) unsigned NOT NULL default '0',
  `pointtype` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `pointtype2` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `pointtype3` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `pointtype4` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `num` int(10) unsigned NOT NULL default '0',
  `timenum` int(10) unsigned NOT NULL default '0',
  `pageview` mediumint(8) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL default '',
  `picture` varchar(255) NOT NULL default '',
  `salevolume` int(11) unsigned NOT NULL default '0',
  `allowtime` varchar(255) NOT NULL default '',
  `usergroup` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`giftid`),
  KEY `available` (`available`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_exchange_log;
CREATE TABLE `modoer_exchange_log` (
  `exchangeid` mediumint(8) unsigned NOT NULL auto_increment,
  `city_id` smallint(5) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(25) NOT NULL default '',
  `giftid` mediumint(8) unsigned NOT NULL default '0',
  `giftname` varchar(200) NOT NULL default '',
  `price` int(10) unsigned NOT NULL default '0',
  `pointtype` enum('point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL default 'point1',
  `number` int(10) unsigned NOT NULL default '1',
  `pay_style` tinyint(1) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  `status_extra` varchar(255) NOT NULL default '',
  `exchangetime` int(10) NOT NULL default '0',
  `contact` mediumtext NOT NULL,
  `checker` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`exchangeid`),
  KEY `uid` (`uid`),
  KEY `giftid` (`giftid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_exchange_lottery;
CREATE TABLE `modoer_exchange_lottery` (
  `lid` mediumint(8) unsigned NOT NULL auto_increment,
  `giftid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) NOT NULL default '0',
  `lotterycode` varchar(50) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`lid`),
  KEY `giftid` (`giftid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_exchange_serial;
CREATE TABLE `modoer_exchange_serial` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `giftid` mediumint(8) unsigned NOT NULL default '0',
  `exchangeid` mediumint(8) NOT NULL default '0',
  `uid` mediumint(8) NOT NULL default '0',
  `serial` varchar(255) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `dateline` int(10) unsigned NOT NULL default '0',
  `sendtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `giftid` (`giftid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_favorites;
CREATE TABLE `modoer_favorites` (
  `fid` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `idtype` char(20) NOT NULL default '',
  `id` mediumint(8) unsigned NOT NULL default '0',
  `addtime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`fid`),
  KEY `addtime` (`addtime`),
  KEY `uid` (`uid`,`addtime`),
  KEY `idtype` (`idtype`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_favorites VALUES ('1','1','fex','subject','1','1433747574');

DROP TABLE IF EXISTS modoer_field;
CREATE TABLE `modoer_field` (
  `fieldid` mediumint(8) unsigned NOT NULL auto_increment,
  `idtype` varchar(30) NOT NULL default '',
  `id` smallint(5) NOT NULL default '0',
  `tablename` varchar(25) NOT NULL default '',
  `fieldname` varchar(50) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `unit` varchar(100) NOT NULL default '',
  `style` varchar(255) NOT NULL default '',
  `template` text NOT NULL,
  `note` mediumtext NOT NULL,
  `type` varchar(20) NOT NULL default '',
  `listorder` smallint(5) NOT NULL default '0',
  `allownull` tinyint(1) unsigned NOT NULL default '1',
  `enablesearch` tinyint(1) unsigned NOT NULL default '0',
  `iscore` tinyint(1) NOT NULL default '0',
  `isadminfield` varchar(1) NOT NULL default '0',
  `show_list` tinyint(1) unsigned NOT NULL default '0',
  `show_detail` tinyint(1) unsigned NOT NULL default '1',
  `regular` varchar(255) NOT NULL default '',
  `errmsg` varchar(255) NOT NULL default '',
  `datatype` varchar(60) NOT NULL default '',
  `config` text NOT NULL,
  `disable` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fieldid`),
  KEY `tablename` (`tablename`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_field VALUES ('1','product','1','product_data','content','详细介绍','','','','','textarea','90','0','0','1','0','0','1','','','MEDIUMTEXT','a:6:{s:5:\"width\";s:3:\"99%\";s:6:\"height\";s:5:\"200px\";s:4:\"html\";s:1:\"2\";s:7:\"default\";s:0:\"\";s:11:\"charnum_sup\";i:0;s:11:\"charnum_sub\";i:1000;}','0');

DROP TABLE IF EXISTS modoer_flowers;
CREATE TABLE `modoer_flowers` (
  `fid` mediumint(8) unsigned NOT NULL auto_increment,
  `idtype` char(20) NOT NULL default 'review',
  `id` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(16) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fid`),
  KEY `reviewid` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_friends;
CREATE TABLE `modoer_friends` (
  `fid` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `friend_uid` mediumint(8) unsigned NOT NULL default '0',
  `addtime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`fid`),
  KEY `addtime` (`addtime`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_gbooks;
CREATE TABLE `modoer_gbooks` (
  `gid` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `gbuid` mediumint(8) unsigned NOT NULL default '0',
  `gbusername` varchar(16) NOT NULL default '',
  `content` text NOT NULL,
  `posttime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`gid`),
  KEY `uid` (`uid`,`posttime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_getpassword;
CREATE TABLE `modoer_getpassword` (
  `getpwid` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `secode` varchar(8) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `sort` enum('get_password','email_verify') NOT NULL default 'get_password',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`getpwid`),
  KEY `secode` (`secode`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_group;
CREATE TABLE `modoer_group` (
  `gid` mediumint(8) NOT NULL auto_increment,
  `status` tinyint(1) NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `city_id` mediumint(8) unsigned NOT NULL default '0' COMMENT '所属城市（地方小组），0为全国',
  `catid` mediumint(8) NOT NULL default '0',
  `groupname` char(60) NOT NULL default '',
  `topics` mediumint(8) NOT NULL default '0',
  `replies` mediumint(8) unsigned NOT NULL default '0',
  `members` mediumint(8) NOT NULL default '0',
  `createtime` int(10) NOT NULL default '0',
  `uid` mediumint(8) NOT NULL default '0',
  `username` char(30) NOT NULL default '',
  `lastpost` int(10) NOT NULL default '0',
  `icon` char(255) NOT NULL default '',
  `tags` char(100) NOT NULL default '',
  `auth` tinyint(1) unsigned NOT NULL default '0',
  `finer` tinyint(1) unsigned NOT NULL default '0',
  `des` text NOT NULL,
  PRIMARY KEY  (`gid`),
  KEY `groupname` (`groupname`),
  KEY `status` (`status`,`members`),
  KEY `sid` (`sid`,`status`),
  KEY `uid` (`uid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_group_category;
CREATE TABLE `modoer_group_category` (
  `catid` mediumint(8) NOT NULL auto_increment,
  `pid` mediumint(8) NOT NULL default '0',
  `listorder` smallint(5) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `tags` text NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_group_member;
CREATE TABLE `modoer_group_member` (
  `id` mediumint(8) NOT NULL auto_increment,
  `gid` mediumint(8) NOT NULL default '0',
  `uid` mediumint(8) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '1',
  `jointime` int(10) NOT NULL default '0',
  `lastpost` int(10) unsigned NOT NULL default '0',
  `bantime` int(10) unsigned NOT NULL default '0',
  `usertype` tinyint(1) NOT NULL default '10',
  `posts` mediumint(8) unsigned NOT NULL default '0',
  `applydes` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`,`uid`),
  KEY `list` (`gid`,`status`,`jointime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_group_reply;
CREATE TABLE `modoer_group_reply` (
  `rpid` mediumint(8) unsigned NOT NULL auto_increment,
  `tpid` mediumint(8) unsigned NOT NULL default '0',
  `gid` mediumint(8) unsigned NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '10',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `pictures` text NOT NULL,
  `content` text NOT NULL,
  `source` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rpid`),
  KEY `tpid` (`tpid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_group_setting;
CREATE TABLE `modoer_group_setting` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `gid` mediumint(8) NOT NULL default '0',
  `variable` char(30) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`,`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_group_tag;
CREATE TABLE `modoer_group_tag` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `tagname` char(8) NOT NULL default '',
  `gid` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tagname` (`tagname`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_group_topic;
CREATE TABLE `modoer_group_topic` (
  `tpid` mediumint(8) unsigned NOT NULL auto_increment,
  `typeid` mediumint(8) unsigned NOT NULL default '0',
  `gid` mediumint(8) NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `pageview` int(10) unsigned NOT NULL default '0',
  `replies` mediumint(8) unsigned NOT NULL default '0',
  `replytime` int(10) unsigned NOT NULL default '0',
  `source` tinyint(1) unsigned NOT NULL default '0',
  `top` tinyint(1) NOT NULL default '0',
  `digest` int(1) unsigned NOT NULL default '0',
  `closed` tinyint(1) NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `dateline` int(10) unsigned NOT NULL default '0',
  `pictures` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`tpid`),
  KEY `list` (`gid`,`status`,`top`,`replytime`),
  KEY `uid` (`uid`,`status`,`dateline`),
  KEY `dateline` (`status`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_group_topic_type;
CREATE TABLE `modoer_group_topic_type` (
  `typeid` mediumint(8) unsigned NOT NULL auto_increment,
  `name` char(20) NOT NULL default '',
  `gid` mediumint(8) unsigned NOT NULL default '0',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`typeid`),
  KEY `gid` (`gid`,`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_guestbook;
CREATE TABLE `modoer_guestbook` (
  `guestbookid` mediumint(8) unsigned NOT NULL auto_increment,
  `sid` mediumint(8) NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `uid` mediumint(9) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `reply` text NOT NULL,
  `replytime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`guestbookid`),
  KEY `id` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_hook;
CREATE TABLE `modoer_hook` (
  `hookid` smallint(5) unsigned NOT NULL auto_increment,
  `hook_module` varchar(30) NOT NULL default '',
  `hook_position` varchar(60) NOT NULL default '',
  `module` varchar(30) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `disable` tinyint(1) unsigned NOT NULL default '0',
  `des` varchar(255) NOT NULL default '',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`hookid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_member_address;
CREATE TABLE `modoer_member_address` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(20) NOT NULL default '',
  `addr` tinytext NOT NULL,
  `postcode` varchar(60) NOT NULL default '',
  `mobile` varchar(60) NOT NULL default '',
  `is_default` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_member_address VALUES ('1','1','123','123123123123123123123','','13555555555','1');

DROP TABLE IF EXISTS modoer_member_feed;
CREATE TABLE `modoer_member_feed` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `flag` varchar(30) NOT NULL default '',
  `city_id` smallint(5) unsigned NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(25) NOT NULL default '',
  `module` varchar(15) NOT NULL default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  `icon` varchar(30) NOT NULL,
  `title` text NOT NULL,
  `body` text NOT NULL,
  `images` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_member_feed VALUES ('1','item','1','0','1','fex','','1433747574','favorite','<a href=\"http://www.5wed.com/space.php?act=index&uid=1\">fex</a> 关注了一名管家','<a href=\"http://www.5wed.com/item.php?act=detail&id=1\">李兰兰</a> (<a href=\"http://www.5wed.com/review.php?act=member&ac=add&type=item_subject&id=1\">点评</a>)','');

DROP TABLE IF EXISTS modoer_member_invite;
CREATE TABLE `modoer_member_invite` (
  `id` mediumint(8) NOT NULL auto_increment,
  `inviter_uid` mediumint(8) NOT NULL default '0',
  `inviter` char(30) NOT NULL default '',
  `invitee_uid` mediumint(8) NOT NULL default '0',
  `invitee` char(30) NOT NULL default '',
  `add_point` tinyint(1) NOT NULL default '0',
  `dateline` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `inviter_uid` (`inviter_uid`,`dateline`,`add_point`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_member_passport;
CREATE TABLE `modoer_member_passport` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `psname` enum('weibo','qq','taobao','google','renren','txweibo','twitter','facebook','wechat','yahoo','jd') NOT NULL,
  `psuid` char(60) NOT NULL default '',
  `uid` mediumint(10) unsigned NOT NULL default '0',
  `access_token` varchar(512) NOT NULL default '',
  `expired` int(10) NOT NULL default '0',
  `token_data` text,
  `isbind` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `psname_psuid` (`psname`,`psuid`),
  UNIQUE KEY `uid_psname` (`uid`,`psname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_member_point;
CREATE TABLE `modoer_member_point` (
  `uid` mediumint(8) unsigned NOT NULL auto_increment,
  `rmb` decimal(9,2) unsigned NOT NULL default '0.00',
  `point` int(10) NOT NULL default '0',
  `point1` int(10) NOT NULL default '0',
  `point2` int(10) NOT NULL default '0',
  `point3` int(10) NOT NULL default '0',
  `point4` int(10) NOT NULL default '0',
  `point5` int(10) NOT NULL default '0',
  `point6` int(10) NOT NULL default '0',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 AUTO_INCREMENT=59;

INSERT INTO modoer_member_point VALUES ('1','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('2','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('3','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('4','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('5','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('6','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('7','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('8','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('9','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('10','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('11','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('12','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('13','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('14','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('15','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('16','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('17','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('18','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('19','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('20','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('21','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('22','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('23','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('24','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('25','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('26','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('27','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('28','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('29','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('30','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('31','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('32','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('33','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('34','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('35','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('36','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('37','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('38','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('39','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('40','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('41','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('42','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('43','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('44','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('45','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('46','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('47','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('48','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('49','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('50','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('51','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('52','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('53','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('54','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('55','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('56','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('57','0.00','20','20','0','0','0','0','0');
INSERT INTO modoer_member_point VALUES ('58','0.00','20','20','0','0','0','0','0');

DROP TABLE IF EXISTS modoer_member_point_log;
CREATE TABLE `modoer_member_point_log` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `out_uid` mediumint(8) unsigned NOT NULL default '0',
  `out_username` varchar(25) NOT NULL default '',
  `out_point` varchar(20) NOT NULL default '',
  `out_value` decimal(9,2) unsigned NOT NULL default '0.00',
  `in_uid` mediumint(8) unsigned NOT NULL default '0',
  `in_username` varchar(25) NOT NULL default '',
  `in_point` varchar(20) NOT NULL default '',
  `in_value` decimal(9,2) unsigned NOT NULL default '0.00',
  `dateline` int(10) unsigned NOT NULL default '0',
  `des` text NOT NULL,
  `extra` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `out_uid` (`out_uid`,`in_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_member_profile;
CREATE TABLE `modoer_member_profile` (
  `uid` mediumint(8) NOT NULL,
  `realname` varchar(100) NOT NULL default '',
  `gender` tinyint(1) NOT NULL default '0',
  `birthday` date NOT NULL default '0000-00-00',
  `alipay` varchar(255) NOT NULL default '',
  `qq` varchar(255) NOT NULL default '',
  `wechat` varchar(255) NOT NULL default '',
  `msn` varchar(255) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `zipcode` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_member_profile VALUES ('1','uyuys','0','0000-00-00','','','','','','');
INSERT INTO modoer_member_profile VALUES ('5','','0','0000-00-00','','','','','','');

DROP TABLE IF EXISTS modoer_member_verify;
CREATE TABLE `modoer_member_verify` (
  `id` int(10) NOT NULL auto_increment,
  `hash` char(32) NOT NULL default '',
  `verify_code` char(8) NOT NULL default '',
  `action_flag` char(20) NOT NULL default '',
  `expriy_date` int(10) unsigned NOT NULL default '0',
  `uniq` char(16) NOT NULL default '',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `sender` enum('mobile','email') NOT NULL,
  `sender_id` char(100) NOT NULL default '',
  `send_time` int(10) NOT NULL default '0',
  `extra` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `verify_code_action_flag` (`verify_code`,`action_flag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_membereffect;
CREATE TABLE `modoer_membereffect` (
  `id` mediumint(8) unsigned NOT NULL default '0',
  `idtype` varchar(30) NOT NULL default '',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `dateline` int(10) NOT NULL default '0',
  `effect1` tinyint(1) unsigned NOT NULL default '0',
  `effect2` tinyint(1) unsigned NOT NULL default '0',
  `effect3` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`,`idtype`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_membereffect VALUES ('1','subject_shops','1','fex','1433747556','0','0','1');

DROP TABLE IF EXISTS modoer_membereffect_total;
CREATE TABLE `modoer_membereffect_total` (
  `id` mediumint(8) unsigned NOT NULL default '0',
  `idtype` varchar(30) NOT NULL default '',
  `effect1` int(10) unsigned NOT NULL default '0',
  `effect2` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`,`idtype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS modoer_members;
CREATE TABLE `modoer_members` (
  `uid` mediumint(8) unsigned NOT NULL auto_increment,
  `email` varchar(60) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `paypw` varchar(32) NOT NULL default '',
  `username` varchar(16) NOT NULL default '',
  `mobile` char(11) NOT NULL default '',
  `rmb` decimal(9,2) unsigned NOT NULL default '0.00',
  `point` int(10) NOT NULL default '0',
  `point1` int(10) NOT NULL default '0',
  `point2` int(10) NOT NULL default '0',
  `point3` int(10) NOT NULL default '0',
  `point4` int(10) NOT NULL default '0',
  `point5` int(10) NOT NULL default '0',
  `point6` int(10) NOT NULL default '0',
  `newmsg` smallint(5) unsigned NOT NULL default '0',
  `regdate` int(10) unsigned NOT NULL default '0',
  `regip` char(15) NOT NULL default '',
  `logintime` int(10) unsigned NOT NULL default '0',
  `loginip` varchar(16) NOT NULL default '',
  `logincount` mediumint(8) unsigned NOT NULL default '0',
  `groupid` smallint(2) NOT NULL default '1',
  `nextgroupid` smallint(5) unsigned NOT NULL default '0',
  `nexttime` int(10) unsigned NOT NULL default '0',
  `subjects` int(10) unsigned NOT NULL default '0',
  `reviews` int(10) unsigned NOT NULL default '0',
  `responds` int(10) unsigned NOT NULL default '0',
  `flowers` int(10) unsigned NOT NULL default '0',
  `pictures` int(10) unsigned NOT NULL default '0',
  `follow` int(10) unsigned NOT NULL default '0',
  `fans` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `groupid` (`groupid`),
  KEY `point` (`point`),
  KEY `point1` (`point1`),
  KEY `regip` (`regip`,`regdate`),
  KEY `mobile` (`mobile`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 AUTO_INCREMENT=59;

INSERT INTO modoer_members VALUES ('1','wuxiaofengdeemail@126.com','e10adc3949ba59abbe56e057f20f883e','','fex','','0.00','20','20','0','0','0','0','0','1','1432371549','127.0.0.1','1433926071','115.205.211.141','48','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('2','wuxiaofmail@126.com','e10adc3949ba59abbe56e057f20f883e','','fex2','','0.00','20','20','0','0','0','0','0','1','1433513427','127.0.0.1','1433839189','125.118.171.170','7','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('3','engdeemail@126.com','e10adc3949ba59abbe56e057f20f883e','','fex3','','0.00','20','20','0','0','0','0','0','1','1433595507','127.0.0.1','1433644112','127.0.0.1','2','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('4','email@126.com','e10adc3949ba59abbe56e057f20f883e','','fex4','','0.00','20','20','0','0','0','0','0','1','1433646966','127.0.0.1','1433646966','127.0.0.1','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('5','abcec@qq.com','e10adc3949ba59abbe56e057f20f883e','','abcec','','0.00','20','20','0','0','0','0','0','1','1433657109','125.118.241.2','1433657109','125.118.241.2','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('6','leonard.logveeva.76@mail.ru','f5659ac2881c1b23b22664fd8808cb43','','Raymondgype','','0.00','20','20','0','0','0','0','0','1','1433661006','31.184.238.101','1433661006','31.184.238.101','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('7','ira.fruntikova@mail.ru','b7b327cd04556de57d3dd633f76883e0','','Danielgen','','0.00','20','20','0','0','0','0','0','1','1433662567','159.224.160.164','1433662567','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('8','dima.bardosov.77@mail.ru','40ea9eacfd9947884fd5b3ebd7a0bcb1','','Norbertnus','','0.00','20','20','0','0','0','0','0','1','1433663046','31.184.238.101','1433663046','31.184.238.101','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('9','anya.tsyrina@mail.ru','fae0ff17f0fbd97c063cc05d2d00c346','','JoshuaTon','','0.00','20','20','0','0','0','0','0','1','1433667222','37.57.200.174','1433667222','37.57.200.174','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('10','chubanovl@mail.ru','ab3bfce5f3905949ff179e9bb9cf49ea','','JosephKr','','0.00','20','20','0','0','0','0','0','1','1433674747','159.224.160.172','1433674747','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('11','vita.savon@mail.ru','d3c2bda46c900dd93fcb6201288f88a0','','Merlincoag','','0.00','20','20','0','0','0','0','0','1','1433676425','159.224.160.172','1433676425','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('12','nice.tormyshev@mail.ru','c806e184a6338cd14e79ccfea18034ee','','WilliamEl','','0.00','20','20','0','0','0','0','0','1','1433677450','37.57.231.161','1433710447','37.57.231.161','2','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('13','khristina.girshiy.93@mail.ru','75c2cd21c63fe5bdd4d882c65b18372a','','Gilbertki','','0.00','20','20','0','0','0','0','0','1','1433687121','37.57.200.174','1433687121','37.57.200.174','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('14','nikutin.tolik@mail.ru','90a03900c61d4e96b77e17058dd1fca8','','RolandDon','','0.00','20','20','0','0','0','0','0','1','1433690163','37.57.231.161','1433690163','37.57.231.161','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('15','nastasiya.gayduchenko.93@mail.ru','3564c7f0307b13d4cd5032057d553790','','Steventug','','0.00','20','20','0','0','0','0','0','1','1433690764','159.224.160.164','1433690764','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('16','artem.makarushko.83@mail.ru','d41c731d04316025440866cefd2d0381','','GregoryFedo','','0.00','20','20','0','0','0','0','0','1','1433692634','159.224.160.172','1433692634','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('17','nazar.agabalyan.76@mail.ru','e95260de4c7533cebc9dcb88f0f84ced','','Matthewea','','0.00','20','20','0','0','0','0','0','1','1433694070','159.224.160.172','1433694070','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('18','tselyurika@mail.ru','c96df9bba750a80295a5d538475565e6','','Andreslep','','0.00','20','20','0','0','0','0','0','1','1433696048','159.224.160.164','1433696048','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('19','aleshonkova.toma@mail.ru','d0a1e2917b39e79ccef196c328edc6f4','','TimothyOi','','0.00','20','20','0','0','0','0','0','1','1433697680','37.57.231.161','1433697680','37.57.231.161','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('20','semerikova.sasha@mail.ru','0c830ba18c85b90e879e654a8d826498','','RaymondSt','','0.00','20','20','0','0','0','0','0','1','1433697932','37.57.231.161','1433697932','37.57.231.161','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('21','shardin1973@mail.ru','2b3c2eefc1e4cf42536c6f42f4479a9f','','JessieveR','','0.00','20','20','0','0','0','0','0','1','1433698782','31.184.238.101','1433698782','31.184.238.101','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('22','cool.strebulaev@mail.ru','1b5f355fa62e11f4f9f1bf9dc5e61d58','','RobertPam','','0.00','20','20','0','0','0','0','0','1','1433706118','37.57.200.174','1433706118','37.57.200.174','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('23','lashch.97@mail.ru','58599284f3ba51165b4e25d8a3b31dae','','JeffreyLep','','0.00','20','20','0','0','0','0','0','1','1433712872','159.224.160.164','1433712872','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('24','produnovm@mail.ru','c72fd93a465b4102dde1842a9e9d310d','','Roberttot','','0.00','20','20','0','0','0','0','0','1','1433714589','159.224.160.164','1433714589','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('25','klyatskayar@mail.ru','9b263dc5739734d28a8f497480809898','','HollisSacy','','0.00','20','20','0','0','0','0','0','1','1433716091','159.224.160.172','1433716091','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('26','galya.slikhova.81@mail.ru','b164302837d12c208e018683b09df5fb','','Michaelnub','','0.00','20','20','0','0','0','0','0','1','1433716442','159.224.160.172','1433716442','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('27','a.kamordina@mail.ru','5bd8f7f4d39a37b1d5e0908401d85719','','Enriquesn','','0.00','20','20','0','0','0','0','0','1','1433735871','159.224.160.164','1433735871','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('28','yulya.chechenkina.86@mail.ru','d195b95c3805374768f22ce859576441','','Vincenttymn','','0.00','20','20','0','0','0','0','0','1','1433745718','159.224.160.164','1433745718','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('29','palyutin.96@mail.ru','a6177b69197f1e0c44d96992ee341316','','Danieldecy','','0.00','20','20','0','0','0','0','0','1','1433748361','159.224.160.172','1433748361','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('30','bredung@mail.ru','62a8677f2da32febd1eb457523c458ff','','JosephEi','','0.00','20','20','0','0','0','0','0','1','1433749601','159.224.160.172','1433749601','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('31','valya.bolsheborodova@mail.ru','90957dbcb9776c8133870ec0f64299b7','','PhilipEa','','0.00','20','20','0','0','0','0','0','1','1433751379','31.184.238.101','1433751379','31.184.238.101','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('32','robert.muzyka.1988@mail.ru','c9954ed269d878d705ca8b00451c1714','','ThomasBug','','0.00','20','20','0','0','0','0','0','1','1433752744','37.57.231.161','1433752744','37.57.231.161','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('33','anfomenko78@gmail.com','6a039a4d3063e93c1e27b009c4621b6d','','esolisinna','','0.00','20','20','0','0','0','0','0','1','1433762620','91.193.172.136','1433762620','91.193.172.136','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('34','chulaeva92@mail.ru','7f00b73a1c2bb084c064f8b26491bf13','','DanielOr','','0.00','20','20','0','0','0','0','0','1','1433763547','159.224.160.172','1433763547','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('35','boss.semivolkov@mail.ru','60d1f4124767b7192484535cd957121a','','RobertNexy','','0.00','20','20','0','0','0','0','0','1','1433771965','31.184.238.101','1433771965','31.184.238.101','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('36','gena.nerozin.81@mail.ru','38c6195daa759774cab30cedce3ae1ee','','DanielPt','','0.00','20','20','0','0','0','0','0','1','1433775828','37.57.200.174','1433775828','37.57.200.174','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('37','akinfeeva_e@mail.ru','a009625e10931d1370e36ba581348fb3','','TimothyOl','','0.00','20','20','0','0','0','0','0','1','1433776973','159.224.160.172','1433776973','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('38','shveglo1994@mail.ru','3c4938dfafcfa1cf978ad4369aba39eb','','PhilipSr','','0.00','20','20','0','0','0','0','0','1','1433778340','37.57.231.161','1433778340','37.57.231.161','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('39','cheretsa@mail.ru','d5180e9050f90dc7cb657e03e01025ee','','Normanei','','0.00','20','20','0','0','0','0','0','1','1433779236','37.57.231.161','1433779236','37.57.231.161','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('40','mila.storozhilova@mail.ru','ac964ea27b467fa8c188981cf124dd48','','Vincentma','','0.00','20','20','0','0','0','0','0','1','1433780839','159.224.160.164','1433780839','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('41','konorev.o@mail.ru','962a1c3bfff1c6387456ee183792027d','','DonaldPl','','0.00','20','20','0','0','0','0','0','1','1433782184','31.184.238.101','1433782184','31.184.238.101','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('42','pugan.liza@mail.ru','01f30b97575177c41752b70a6c85f76b','','Philiprist','','0.00','20','20','0','0','0','0','0','1','1433782779','159.224.160.164','1433782779','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('43','prokin.trofim@mail.ru','b0e6760a8b8c17e422f3968bfeb89ede','','Albertokt','','0.00','20','20','0','0','0','0','0','1','1433785740','37.57.231.161','1433785740','37.57.231.161','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('44','bogdana.paina@mail.ru','e6a2299abcee7c43a6987aa1836f3ebe','','Marvinnete','','0.00','20','20','0','0','0','0','0','1','1433790256','159.224.160.172','1433790256','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('45','bekhtev83@mail.ru','6899245a2c6252523fefd32aa2d9e0fe','','RobertSt','','0.00','20','20','0','0','0','0','0','1','1433793623','159.224.160.164','1433793623','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('46','edik.mashninov@mail.ru','98ef43564be5daefd62b76d5a2944686','','Walterrof','','0.00','20','20','0','0','0','0','0','1','1433804626','37.57.231.161','1433804626','37.57.231.161','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('47','rogulkinat@mail.ru','03431d2424ee13a8c1b0ea2b90d4dad5','','Michealsl','','0.00','20','20','0','0','0','0','0','1','1433805107','37.57.231.161','1433805107','37.57.231.161','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('48','vladlen.svettsov@mail.ru','5316ff6dc0cc6e7df11f0b8b21c70ffe','','WilliamLer','','0.00','20','20','0','0','0','0','0','1','1433805527','159.224.160.172','1433805527','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('49','ursaki-k@mail.ru','cd730594c27d5be1c953a4ab11edfd28','','DennisMt','','0.00','20','20','0','0','0','0','0','1','1433809940','31.184.238.101','1433809940','31.184.238.101','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('50','nice.tayanovskiy@mail.ru','e0d99cbc740d41ca57b3a31216e01182','','MartinLip','','0.00','20','20','0','0','0','0','0','1','1433831660','159.224.160.164','1433831660','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('51','ruseevar@mail.ru','449d512895883cfd386d0485ac45391e','','Kennethol','','0.00','20','20','0','0','0','0','0','1','1433833855','159.224.160.164','1433833855','159.224.160.164','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('52','siminyakinak@mail.ru','c2f57aa3c068c8004ca28bc1d086959e','','Javiergraw','','0.00','20','20','0','0','0','0','0','1','1433834585','31.184.238.101','1433834585','31.184.238.101','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('53','v.molodetskiy@mail.ru','1d756d2743d54023338427af66d163ca','','PhilipBype','','0.00','20','20','0','0','0','0','0','1','1433836419','159.224.160.172','1433836419','159.224.160.172','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('54','v_filiptsova@mail.ru','e702580188edd77cb80b8bbffacfa9c3','','SidneyEn','','0.00','20','20','0','0','0','0','0','1','1433836883','31.184.238.101','1433836883','31.184.238.101','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('55','liza.elyutina.79@mail.ru','ae333c9346ef19244158f77380aa9a79','','DonaldOl','','0.00','20','20','0','0','0','0','0','1','1433850166','31.184.238.101','1433850166','31.184.238.101','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('56','nice.khryapkina@mail.ru','da52eae916bfe1fd9ab89a4194539378','','MichaelSa','','0.00','20','20','0','0','0','0','0','1','1433862597','37.57.231.161','1433862597','37.57.231.161','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('57','janinegox@mail.ru','f818d1a3c5f21688645682455424ba88','','JanineSart','','0.00','20','20','0','0','0','0','0','1','1433880744','83.143.240.10','1433880744','83.143.240.10','1','10','0','0','0','0','0','0','0','0','0');
INSERT INTO modoer_members VALUES ('58','vebnest@yandex.ru','cdb8dfc87394ba1e19ae883184b23497','','Thomassek','','0.00','20','20','0','0','0','0','0','1','1433950784','46.167.101.237','1433950784','46.167.101.237','1','10','0','0','0','0','0','0','0','0','0');

DROP TABLE IF EXISTS modoer_menus;
CREATE TABLE `modoer_menus` (
  `menuid` smallint(5) unsigned NOT NULL auto_increment,
  `parentid` smallint(5) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '1' COMMENT '菜单当前层级',
  `isclosed` tinyint(1) NOT NULL default '0',
  `isfolder` tinyint(1) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `scriptnav` varchar(60) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `icon` varchar(60) NOT NULL default '',
  `target` varchar(15) NOT NULL default '',
  `listorder` smallint(5) NOT NULL default '0',
  `top_level` tinyint(1) unsigned NOT NULL default '0' COMMENT '菜单子类数量，0表示无限级',
  `flag` char(20) NOT NULL default '' COMMENT '自定义标示，用于系统定位专用菜单',
  PRIMARY KEY  (`menuid`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 AUTO_INCREMENT=111;

INSERT INTO modoer_menus VALUES ('1','0','1','0','1','头部菜单','','','','','1','0','');
INSERT INTO modoer_menus VALUES ('49','1','1','0','0','首页','index','modoer/index','','_self','101','0','');
INSERT INTO modoer_menus VALUES ('3','0','1','0','1','后台快捷菜单','','','','','5','0','');
INSERT INTO modoer_menus VALUES ('53','3','1','0','0','调用管理','','?module=modoer&act=datacall&op=list','','main','3','0','');
INSERT INTO modoer_menus VALUES ('54','3','1','0','0','更新网站缓存','','?module=modoer&act=tools&op=cache','','main','4','0','');
INSERT INTO modoer_menus VALUES ('62','1','1','1','0','主题','item_category','item/category','','','4','0','');
INSERT INTO modoer_menus VALUES ('75','3','1','0','0','菜单管理','','?module=modoer&act=menu','','','5','0','');
INSERT INTO modoer_menus VALUES ('66','0','1','0','1','底部菜单','','','','','2','0','');
INSERT INTO modoer_menus VALUES ('68','66','1','0','0','联系我们','','#','','','0','0','');
INSERT INTO modoer_menus VALUES ('69','66','1','0','0','广告服务','','#','','','0','0','');
INSERT INTO modoer_menus VALUES ('70','66','1','0','0','服务条款','','#','','','0','0','');
INSERT INTO modoer_menus VALUES ('71','66','1','0','0','网站地图','','#','','','0','0','');
INSERT INTO modoer_menus VALUES ('72','66','1','0','0','使用帮助','','#','','','0','0','');
INSERT INTO modoer_menus VALUES ('73','66','1','0','0','诚聘英才','','#','','','0','0','');
INSERT INTO modoer_menus VALUES ('76','3','1','0','0','主题审核','','?module=item&act=subject_check','','','1','0','');
INSERT INTO modoer_menus VALUES ('77','3','1','0','0','点评审核','','?module=review&act=review&op=checklist','','','2','0','');
INSERT INTO modoer_menus VALUES ('88','1','1','1','0','礼品','exchange','exchange/index','','','9','0','');
INSERT INTO modoer_menus VALUES ('90','1','1','1','0','资讯','article','article/index','','','3','0','');
INSERT INTO modoer_menus VALUES ('93','1','1','1','0','会员卡','card','card/index','','','11','0','');
INSERT INTO modoer_menus VALUES ('94','1','1','1','0','优惠券','coupon','coupon/index','','','10','0','');
INSERT INTO modoer_menus VALUES ('95','1','1','1','0','相册','item_album','item/album','','','12','0','');
INSERT INTO modoer_menus VALUES ('97','1','1','1','0','点评','review','review/index','','','14','0','');
INSERT INTO modoer_menus VALUES ('98','1','1','1','0','排行榜','item_subject_tops','item/tops','','','15','0','');
INSERT INTO modoer_menus VALUES ('99','1','1','0','0','新人说','group','group/index','','','105','0','');
INSERT INTO modoer_menus VALUES ('100','1','1','1','0','榜单','mylist','mylist/index','','','17','0','');
INSERT INTO modoer_menus VALUES ('101','0','1','0','1','后台头部菜单','','','','','5','1','console_header');
INSERT INTO modoer_menus VALUES ('102','101','1','0','0','会员','','member','','','0','0','');
INSERT INTO modoer_menus VALUES ('103','101','1','0','0','主题','','item','','','0','0','');
INSERT INTO modoer_menus VALUES ('104','101','1','0','0','点评','','review','','','0','0','');
INSERT INTO modoer_menus VALUES ('105','101','1','0','0','资讯','','article','','','0','0','');
INSERT INTO modoer_menus VALUES ('106','101','1','0','0','小组','','group','','','0','0','');
INSERT INTO modoer_menus VALUES ('107','1','1','0','0','婚礼套餐','','#','','','102','0','');
INSERT INTO modoer_menus VALUES ('108','1','1','0','0','婚礼管家','','item/list&catid=1','','','103','0','');
INSERT INTO modoer_menus VALUES ('109','1','1','0','0','新人主页','','space/new','','','104','0','');
INSERT INTO modoer_menus VALUES ('110','101','1','0','0','商城','','product','','','0','0','');

DROP TABLE IF EXISTS modoer_mobile_verify;
CREATE TABLE `modoer_mobile_verify` (
  `id` mediumint(10) NOT NULL auto_increment,
  `uniq` char(32) NOT NULL default '',
  `mobile` char(20) NOT NULL default '',
  `serial` char(6) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  `dateline` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uniq` (`uniq`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_model;
CREATE TABLE `modoer_model` (
  `modelid` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `tablename` varchar(20) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `usearea` tinyint(1) NOT NULL default '0',
  `item_name` varchar(200) NOT NULL default '',
  `item_unit` varchar(200) NOT NULL default '',
  `tplname_list` varchar(200) NOT NULL default '',
  `tplname_detail` varchar(200) NOT NULL default '',
  `disable` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`modelid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

INSERT INTO modoer_model VALUES ('1','婚礼管家','subject_shops','','1','管家','名','item_subject_list','item_subject_detail','0');
INSERT INTO modoer_model VALUES ('2','婚礼套餐','subject_package','','1','套餐','份','item_subject_list','item_subject_detail','0');

DROP TABLE IF EXISTS modoer_modules;
CREATE TABLE `modoer_modules` (
  `moduleid` smallint(5) unsigned NOT NULL auto_increment,
  `flag` varchar(30) NOT NULL default '',
  `extra` varchar(20) NOT NULL default '',
  `iscore` tinyint(1) NOT NULL default '0',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `directory` varchar(100) NOT NULL default '',
  `disable` tinyint(1) unsigned NOT NULL default '0',
  `config` text NOT NULL,
  `version` varchar(60) NOT NULL default '',
  `releasetime` date NOT NULL default '0000-00-00',
  `reliant` varchar(255) NOT NULL default '',
  `author` varchar(255) NOT NULL default '',
  `introduce` text NOT NULL,
  `siteurl` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `copyright` varchar(255) NOT NULL default '',
  `checkurl` varchar(255) NOT NULL default '',
  `liccode` text NOT NULL,
  PRIMARY KEY  (`moduleid`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 AUTO_INCREMENT=20;

INSERT INTO modoer_modules VALUES ('1','member','','1','8','会员','member','0','','1.1','2008-09-30','','Moufer Studio',' ','http://www.modoer.com','moufer@163.com','Moufer Studio','','');
INSERT INTO modoer_modules VALUES ('2','item','','1','1','主题','item','0','','3.3','2014-03-25','','Moufer Studio','主题模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/item.php','');
INSERT INTO modoer_modules VALUES ('3','space','','1','9','个人空间','space','0','','1.1','2008-09-30','','Moufer Studio','','http://www.modoer.com','moufer@163.com','Moufer Studio','','');
INSERT INTO modoer_modules VALUES ('4','link','','0','10','友情链接','link','0','','2.0','2010-05-04','','moufer','友情链接模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/comment.php','');
INSERT INTO modoer_modules VALUES ('6','comment','','0','6','会员评论','comment','0','','1.0','2010-04-01','','moufer','评论模块可用于其他需要进行评论的模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/comment.php','');
INSERT INTO modoer_modules VALUES ('7','exchange','','0','5','礼品兑换','exchange','0','','3.0','2012-05-01','','moufer,轩','使用网站金币兑换礼品，抽奖，刺激玩家的积极性，消费金币','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/exchange.php','');
INSERT INTO modoer_modules VALUES ('8','article','','0','3','新闻资讯','article','0','','2.0','2010-04-14','','moufer','文章信息，发布网站信息和主题资讯','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/article.php','');
INSERT INTO modoer_modules VALUES ('9','card','','0','7','会员卡','card','0','','2.1','2010-09-29','item','moufer','会员卡模块用户管理消费类主题提供优惠折扣信息','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/card.php','');
INSERT INTO modoer_modules VALUES ('10','coupon','','0','4','优惠券','coupon','0','','2.0','2010-05-10','','moufer','优惠券模块，提供分享和打印折扣和优惠信息','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/coupon.php','');
INSERT INTO modoer_modules VALUES ('11','adv','','0','10','广告','adv','0','','2.0','2010-12-30','','moufer','自定义广告模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/adv.php','');
INSERT INTO modoer_modules VALUES ('12','review','','1','2','点评','review','0','','2.6','2014-03-25','','Moufer Studio','点评模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/review.php','');
INSERT INTO modoer_modules VALUES ('13','sms','','0','10','短信发送','sms','0','','1.0','2011-12-06','','moufer','短信发送模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/sms.php','');
INSERT INTO modoer_modules VALUES ('14','pay','','0','10','在线充值','pay','0','','2.2','2012-03-30','','moufer','在线积分充值模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/pay.php','');
INSERT INTO modoer_modules VALUES ('15','group','','0','10','小组','group','0','','1.2','2014-03-25','','moufer','网站会员小组讨论模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/group.php','');
INSERT INTO modoer_modules VALUES ('16','mobile','','0','11','手机浏览','mobile','0','','1.0','2012-10-19','item','moufer','基于HTML5的手机浏览模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/mobile.php','');
INSERT INTO modoer_modules VALUES ('17','mylist','','0','12','榜单','mylist','0','','1.0','2014-03-25','','moufer','会员聚合主题信息成为一个榜单，给共同喜好人群参考','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/mylist.php','');
INSERT INTO modoer_modules VALUES ('18','weixin','','0','13','微信公众平台','weixin','0','','1.0','2014-07-19','','moufer','通过微信公众号与网站进行操作互动','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/weixin.php','');
INSERT INTO modoer_modules VALUES ('19','product','pro','0','0','商城','','0','','3.4','2014-12-01','','moufer,风格店铺','用于商铺类产品销售','http://www.modoer.com','moufer@163.com,service@cmsky.org','MouferStudio,风格店铺','http://www.modoer.com/info/module/product.php','');

DROP TABLE IF EXISTS modoer_mylinks;
CREATE TABLE `modoer_mylinks` (
  `linkid` mediumint(8) unsigned NOT NULL auto_increment,
  `city_id` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(40) NOT NULL default '',
  `link` varchar(100) NOT NULL default '',
  `logo` varchar(100) NOT NULL default '',
  `des` varchar(255) NOT NULL default '',
  `displayorder` int(8) NOT NULL default '0',
  `ischeck` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`linkid`),
  KEY `list` (`ischeck`,`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_mylinks VALUES ('1','0','Modoer点评系统','http://www.modoer.com','','Modoer 是一款点评网站管理系统，采用 PHP+MYSQL 设计，开放全部源码','1','1');

DROP TABLE IF EXISTS modoer_mylist;
CREATE TABLE `modoer_mylist` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `city_id` mediumint(8) unsigned NOT NULL default '0',
  `catid` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `tags` varchar(255) NOT NULL default '',
  `num` int(10) unsigned NOT NULL default '0',
  `favorites` int(10) unsigned NOT NULL default '0',
  `flowers` int(10) unsigned NOT NULL default '0',
  `responds` int(10) unsigned NOT NULL default '0',
  `pageviews` int(10) unsigned NOT NULL default '0',
  `createtime` int(10) unsigned NOT NULL default '0',
  `modifytime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `thumb` varchar(255) NOT NULL default '',
  `intro` text NOT NULL,
  `digest` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `cat_time` (`city_id`,`catid`,`modifytime`),
  KEY `city_time` (`city_id`,`modifytime`),
  KEY `uid_modifytime` (`uid`,`modifytime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_mylist_category;
CREATE TABLE `modoer_mylist_category` (
  `catid` smallint(8) unsigned NOT NULL auto_increment,
  `name` varchar(40) NOT NULL default '',
  `num` mediumint(9) NOT NULL default '0',
  `listorder` smallint(5) NOT NULL default '0',
  PRIMARY KEY  (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_mylist_item;
CREATE TABLE `modoer_mylist_item` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `mylist_id` mediumint(8) unsigned NOT NULL default '0' COMMENT '榜单ID',
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '添加会员UID',
  `sid` mediumint(8) unsigned NOT NULL default '0' COMMENT '主题ID',
  `addtime` int(10) unsigned NOT NULL default '0' COMMENT '添加时间',
  `listorder` smallint(5) unsigned NOT NULL default '0' COMMENT '排序',
  `excuse` text NOT NULL COMMENT '推荐理由',
  `rid` mediumint(8) unsigned NOT NULL default '0' COMMENT '点评的ID',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_mylist_tag_data;
CREATE TABLE `modoer_mylist_tag_data` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `tag_id` mediumint(8) unsigned NOT NULL default '0' COMMENT '标签索引ID',
  `tag_name` char(30) NOT NULL COMMENT '标签名称',
  `mylist_id` mediumint(8) unsigned NOT NULL default '0' COMMENT '关联榜单ID',
  `city_id` mediumint(8) unsigned NOT NULL default '0' COMMENT '榜单城市ID',
  PRIMARY KEY  (`id`),
  KEY `city_id` (`city_id`,`tag_id`),
  KEY `mylist_id` (`mylist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_mylist_tag_index;
CREATE TABLE `modoer_mylist_tag_index` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` char(30) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_mysubject;
CREATE TABLE `modoer_mysubject` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `modelid` smallint(5) NOT NULL default '0',
  `expirydate` int(10) unsigned NOT NULL default '0',
  `lasttime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_mysubject VALUES ('1','2','2','0','0','1433783831');

DROP TABLE IF EXISTS modoer_mytask;
CREATE TABLE `modoer_mytask` (
  `uid` mediumint(8) unsigned NOT NULL,
  `taskid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `progress` smallint(3) unsigned NOT NULL default '0',
  `dateline` int(10) NOT NULL default '0',
  `applytime` int(10) unsigned NOT NULL default '0',
  `total` smallint(5) unsigned NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`uid`,`taskid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS modoer_notice;
CREATE TABLE `modoer_notice` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `module` varchar(15) NOT NULL default '',
  `type` varchar(15) NOT NULL default '',
  `isread` tinyint(1) NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `note` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`isread`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_pay;
CREATE TABLE `modoer_pay` (
  `payid` mediumint(8) unsigned NOT NULL auto_increment,
  `order_flag` varchar(30) NOT NULL default '',
  `orderid` int(10) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `order_name` varchar(255) NOT NULL default '',
  `payment_orderid` varchar(60) NOT NULL default '',
  `payment_name` varchar(60) NOT NULL default '',
  `creation_time` int(10) NOT NULL default '0',
  `pay_time` int(10) unsigned NOT NULL default '0',
  `price` decimal(9,2) NOT NULL default '0.00',
  `pay_status` tinyint(1) NOT NULL default '0',
  `my_status` tinyint(1) NOT NULL default '0',
  `notify_url` varchar(255) NOT NULL default '',
  `callback_url` varchar(255) NOT NULL default '',
  `callback_url_mobile` varchar(255) NOT NULL default '',
  `royalty` tinytext NOT NULL,
  `goods` tinytext NOT NULL,
  PRIMARY KEY  (`payid`),
  KEY `order_flag` (`order_flag`,`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_pay_card;
CREATE TABLE `modoer_pay_card` (
  `cardid` mediumint(8) unsigned NOT NULL auto_increment,
  `number` varchar(30) NOT NULL default '',
  `password` varchar(30) NOT NULL default '',
  `cztype` varchar(15) default '',
  `dateline` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `price` int(10) unsigned NOT NULL default '0',
  `usetime` int(10) unsigned NOT NULL default '0',
  `uid` mediumint(8) NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`cardid`),
  UNIQUE KEY `number` (`number`),
  KEY `status` (`status`,`endtime`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_pay_log;
CREATE TABLE `modoer_pay_log` (
  `orderid` int(10) unsigned NOT NULL auto_increment,
  `port_orderid` varchar(60) NOT NULL default '',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `price` decimal(9,2) unsigned NOT NULL default '0.00',
  `point` int(10) unsigned NOT NULL,
  `cztype` varchar(15) default '',
  `dateline` int(10) NOT NULL,
  `exchangetime` int(10) NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `retcode` varchar(10) NOT NULL default '',
  `ip` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_pay_withdraw;
CREATE TABLE `modoer_pay_withdraw` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `price` decimal(9,2) unsigned NOT NULL default '0.00',
  `charges` decimal(9,2) unsigned NOT NULL default '0.00',
  `realname` varchar(255) NOT NULL default '',
  `accounts` varchar(255) NOT NULL default '',
  `pointtype` char(6) NOT NULL default '',
  `applytime` int(10) unsigned NOT NULL default '0',
  `ip` varchar(16) NOT NULL default '',
  `status` enum('wait','succeed','fail','cancel') NOT NULL default 'wait',
  `status_des` varchar(255) NOT NULL default '',
  `optime` int(10) unsigned NOT NULL default '0',
  `opowner` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_pictures;
CREATE TABLE `modoer_pictures` (
  `picid` mediumint(8) unsigned NOT NULL auto_increment,
  `albumid` mediumint(8) unsigned NOT NULL default '0',
  `city_id` smallint(5) unsigned NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(16) NOT NULL default '',
  `title` varchar(60) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `width` smallint(5) unsigned NOT NULL default '0',
  `height` smallint(5) unsigned NOT NULL default '0',
  `size` int(10) unsigned NOT NULL default '0',
  `comments` varchar(60) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `sort` tinyint(1) NOT NULL default '0',
  `browse` int(10) NOT NULL default '0',
  `addtime` int(10) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`picid`),
  KEY `uid` (`uid`,`sid`),
  KEY `sid` (`sid`,`status`),
  KEY `city_id` (`city_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_pictures VALUES ('1','1','1','1','0','admin','1_21362','uploads/pictures/2015-06/thumb_15_1433743836.jpg','uploads/pictures/2015-06/15_1433743836.jpg','335','380','45301','','','0','0','1433743836','1');

DROP TABLE IF EXISTS modoer_plan_task;
CREATE TABLE `modoer_plan_task` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '主键自增ID',
  `filename` varchar(255) NOT NULL default '' COMMENT '执行脚本文件名',
  `time_exp` varchar(255) NOT NULL default '' COMMENT '执行时间表达式',
  `setting` text NOT NULL COMMENT '脚本配置信息',
  `lasttime` int(10) unsigned NOT NULL default '0' COMMENT '最后执行时间',
  `nexttime` int(10) unsigned NOT NULL default '0' COMMENT '下次执行时间',
  `run_count` int(10) unsigned NOT NULL default '0' COMMENT '已执行次数',
  PRIMARY KEY  (`id`),
  KEY `nexttime` (`nexttime`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

INSERT INTO modoer_plan_task VALUES ('1','delete_space_visitor','day=1|hour=0|minute=0','','1433426490','1435680000','2');
INSERT INTO modoer_plan_task VALUES ('2','delete_member_notice','day=1|hour=5|minute=0','','1433426490','1435698000','2');
INSERT INTO modoer_plan_task VALUES ('3','delete_search_cache','hour=0|minute=30','','1433953813','1434040200','18');
INSERT INTO modoer_plan_task VALUES ('4','delete_upload_temp','hour=3|minute=30','','1433969342','1434051000','18');

DROP TABLE IF EXISTS modoer_pmsgs;
CREATE TABLE `modoer_pmsgs` (
  `pmid` mediumint(8) unsigned NOT NULL auto_increment,
  `senduid` mediumint(8) unsigned NOT NULL default '0',
  `recvuid` mediumint(8) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `subject` varchar(60) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `new` tinyint(1) NOT NULL default '1',
  `delflag` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`pmid`),
  KEY `senduid` (`senduid`,`posttime`),
  KEY `recvuid` (`recvuid`,`posttime`),
  KEY `new` (`new`,`recvuid`,`posttime`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 AUTO_INCREMENT=59;

INSERT INTO modoer_pmsgs VALUES ('1','0','1','尊敬的fex：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-05-23 16:59:09','欢迎注册成为我们的会员！','1432371549','1','0');
INSERT INTO modoer_pmsgs VALUES ('2','0','2','尊敬的fex2：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-05 22:10:27','欢迎注册成为我们的会员！','1433513427','1','0');
INSERT INTO modoer_pmsgs VALUES ('3','0','3','尊敬的fex3：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-06 20:58:27','欢迎注册成为我们的会员！','1433595507','1','0');
INSERT INTO modoer_pmsgs VALUES ('4','0','4','尊敬的fex4：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 11:16:06','欢迎注册成为我们的会员！','1433646966','1','0');
INSERT INTO modoer_pmsgs VALUES ('5','0','5','尊敬的abcec：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 14:05:09','欢迎注册成为我们的会员！','1433657109','1','0');
INSERT INTO modoer_pmsgs VALUES ('6','0','6','尊敬的Raymondgype：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 15:10:06','欢迎注册成为我们的会员！','1433661006','1','0');
INSERT INTO modoer_pmsgs VALUES ('7','0','7','尊敬的Danielgen：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 15:36:07','欢迎注册成为我们的会员！','1433662567','1','0');
INSERT INTO modoer_pmsgs VALUES ('8','0','8','尊敬的Norbertnus：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 15:44:06','欢迎注册成为我们的会员！','1433663046','1','0');
INSERT INTO modoer_pmsgs VALUES ('9','0','9','尊敬的JoshuaTon：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 16:53:42','欢迎注册成为我们的会员！','1433667222','1','0');
INSERT INTO modoer_pmsgs VALUES ('10','0','10','尊敬的JosephKr：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 18:59:07','欢迎注册成为我们的会员！','1433674747','1','0');
INSERT INTO modoer_pmsgs VALUES ('11','0','11','尊敬的Merlincoag：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 19:27:05','欢迎注册成为我们的会员！','1433676425','1','0');
INSERT INTO modoer_pmsgs VALUES ('12','0','12','尊敬的WilliamEl：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 19:44:10','欢迎注册成为我们的会员！','1433677450','1','0');
INSERT INTO modoer_pmsgs VALUES ('13','0','13','尊敬的Gilbertki：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 22:25:21','欢迎注册成为我们的会员！','1433687121','1','0');
INSERT INTO modoer_pmsgs VALUES ('14','0','14','尊敬的RolandDon：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 23:16:03','欢迎注册成为我们的会员！','1433690163','1','0');
INSERT INTO modoer_pmsgs VALUES ('15','0','15','尊敬的Steventug：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 23:26:04','欢迎注册成为我们的会员！','1433690764','1','0');
INSERT INTO modoer_pmsgs VALUES ('16','0','16','尊敬的GregoryFedo：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 23:57:14','欢迎注册成为我们的会员！','1433692634','1','0');
INSERT INTO modoer_pmsgs VALUES ('17','0','17','尊敬的Matthewea：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 00:21:10','欢迎注册成为我们的会员！','1433694070','1','0');
INSERT INTO modoer_pmsgs VALUES ('18','0','18','尊敬的Andreslep：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 00:54:08','欢迎注册成为我们的会员！','1433696048','1','0');
INSERT INTO modoer_pmsgs VALUES ('19','0','19','尊敬的TimothyOi：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 01:21:20','欢迎注册成为我们的会员！','1433697680','1','0');
INSERT INTO modoer_pmsgs VALUES ('20','0','20','尊敬的RaymondSt：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 01:25:32','欢迎注册成为我们的会员！','1433697932','1','0');
INSERT INTO modoer_pmsgs VALUES ('21','0','21','尊敬的JessieveR：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 01:39:42','欢迎注册成为我们的会员！','1433698782','1','0');
INSERT INTO modoer_pmsgs VALUES ('22','0','22','尊敬的RobertPam：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 03:41:58','欢迎注册成为我们的会员！','1433706118','1','0');
INSERT INTO modoer_pmsgs VALUES ('23','0','23','尊敬的JeffreyLep：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 05:34:32','欢迎注册成为我们的会员！','1433712872','1','0');
INSERT INTO modoer_pmsgs VALUES ('24','0','24','尊敬的Roberttot：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 06:03:09','欢迎注册成为我们的会员！','1433714589','1','0');
INSERT INTO modoer_pmsgs VALUES ('25','0','25','尊敬的HollisSacy：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 06:28:11','欢迎注册成为我们的会员！','1433716091','1','0');
INSERT INTO modoer_pmsgs VALUES ('26','0','26','尊敬的Michaelnub：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 06:34:02','欢迎注册成为我们的会员！','1433716442','1','0');
INSERT INTO modoer_pmsgs VALUES ('27','0','27','尊敬的Enriquesn：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 11:57:51','欢迎注册成为我们的会员！','1433735871','1','0');
INSERT INTO modoer_pmsgs VALUES ('28','0','28','尊敬的Vincenttymn：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 14:41:58','欢迎注册成为我们的会员！','1433745718','1','0');
INSERT INTO modoer_pmsgs VALUES ('29','0','29','尊敬的Danieldecy：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 15:26:01','欢迎注册成为我们的会员！','1433748361','1','0');
INSERT INTO modoer_pmsgs VALUES ('30','0','30','尊敬的JosephEi：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 15:46:41','欢迎注册成为我们的会员！','1433749601','1','0');
INSERT INTO modoer_pmsgs VALUES ('31','0','31','尊敬的PhilipEa：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 16:16:19','欢迎注册成为我们的会员！','1433751379','1','0');
INSERT INTO modoer_pmsgs VALUES ('32','0','32','尊敬的ThomasBug：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 16:39:04','欢迎注册成为我们的会员！','1433752744','1','0');
INSERT INTO modoer_pmsgs VALUES ('33','0','33','尊敬的esolisinna：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 19:23:40','欢迎注册成为我们的会员！','1433762620','1','0');
INSERT INTO modoer_pmsgs VALUES ('34','0','34','尊敬的DanielOr：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 19:39:07','欢迎注册成为我们的会员！','1433763547','1','0');
INSERT INTO modoer_pmsgs VALUES ('35','0','35','尊敬的RobertNexy：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 21:59:25','欢迎注册成为我们的会员！','1433771965','1','0');
INSERT INTO modoer_pmsgs VALUES ('36','0','36','尊敬的DanielPt：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 23:03:48','欢迎注册成为我们的会员！','1433775828','1','0');
INSERT INTO modoer_pmsgs VALUES ('37','0','37','尊敬的TimothyOl：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 23:22:53','欢迎注册成为我们的会员！','1433776973','1','0');
INSERT INTO modoer_pmsgs VALUES ('38','0','38','尊敬的PhilipSr：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-08 23:45:40','欢迎注册成为我们的会员！','1433778340','1','0');
INSERT INTO modoer_pmsgs VALUES ('39','0','39','尊敬的Normanei：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 00:00:36','欢迎注册成为我们的会员！','1433779236','1','0');
INSERT INTO modoer_pmsgs VALUES ('40','0','40','尊敬的Vincentma：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 00:27:19','欢迎注册成为我们的会员！','1433780839','1','0');
INSERT INTO modoer_pmsgs VALUES ('41','0','41','尊敬的DonaldPl：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 00:49:44','欢迎注册成为我们的会员！','1433782184','1','0');
INSERT INTO modoer_pmsgs VALUES ('42','0','42','尊敬的Philiprist：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 00:59:39','欢迎注册成为我们的会员！','1433782779','1','0');
INSERT INTO modoer_pmsgs VALUES ('43','0','43','尊敬的Albertokt：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 01:49:00','欢迎注册成为我们的会员！','1433785740','1','0');
INSERT INTO modoer_pmsgs VALUES ('44','0','44','尊敬的Marvinnete：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 03:04:16','欢迎注册成为我们的会员！','1433790256','1','0');
INSERT INTO modoer_pmsgs VALUES ('45','0','45','尊敬的RobertSt：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 04:00:23','欢迎注册成为我们的会员！','1433793623','1','0');
INSERT INTO modoer_pmsgs VALUES ('46','0','46','尊敬的Walterrof：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 07:03:46','欢迎注册成为我们的会员！','1433804626','1','0');
INSERT INTO modoer_pmsgs VALUES ('47','0','47','尊敬的Michealsl：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 07:11:47','欢迎注册成为我们的会员！','1433805107','1','0');
INSERT INTO modoer_pmsgs VALUES ('48','0','48','尊敬的WilliamLer：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 07:18:47','欢迎注册成为我们的会员！','1433805527','1','0');
INSERT INTO modoer_pmsgs VALUES ('49','0','49','尊敬的DennisMt：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 08:32:20','欢迎注册成为我们的会员！','1433809940','1','0');
INSERT INTO modoer_pmsgs VALUES ('50','0','50','尊敬的MartinLip：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 14:34:20','欢迎注册成为我们的会员！','1433831660','1','0');
INSERT INTO modoer_pmsgs VALUES ('51','0','51','尊敬的Kennethol：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 15:10:55','欢迎注册成为我们的会员！','1433833855','1','0');
INSERT INTO modoer_pmsgs VALUES ('52','0','52','尊敬的Javiergraw：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 15:23:05','欢迎注册成为我们的会员！','1433834585','1','0');
INSERT INTO modoer_pmsgs VALUES ('53','0','53','尊敬的PhilipBype：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 15:53:39','欢迎注册成为我们的会员！','1433836419','1','0');
INSERT INTO modoer_pmsgs VALUES ('54','0','54','尊敬的SidneyEn：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 16:01:23','欢迎注册成为我们的会员！','1433836883','1','0');
INSERT INTO modoer_pmsgs VALUES ('55','0','55','尊敬的DonaldOl：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 19:42:46','欢迎注册成为我们的会员！','1433850166','1','0');
INSERT INTO modoer_pmsgs VALUES ('56','0','56','尊敬的MichaelSa：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-09 23:09:57','欢迎注册成为我们的会员！','1433862597','1','0');
INSERT INTO modoer_pmsgs VALUES ('57','0','57','尊敬的JanineSart：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-10 04:12:24','欢迎注册成为我们的会员！','1433880744','1','0');
INSERT INTO modoer_pmsgs VALUES ('58','0','58','尊敬的Thomassek：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-10 23:39:44','欢迎注册成为我们的会员！','1433950784','1','0');

DROP TABLE IF EXISTS modoer_point_log;
CREATE TABLE `modoer_point_log` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `flag` varchar(20) NOT NULL default '',
  `point_flow` enum('in','out') NOT NULL,
  `point_type` enum('point','rmb','point1','point2','point3','point4','point5','point6') NOT NULL default 'point',
  `point_value` decimal(9,2) unsigned NOT NULL default '0.00',
  `amount` decimal(9,2) NOT NULL default '0.00',
  `dateline` int(10) unsigned NOT NULL default '0',
  `remark` varchar(255) NOT NULL default '',
  `extra` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 AUTO_INCREMENT=117;

INSERT INTO modoer_point_log VALUES ('1','1','fex','reg','in','point','20.00','20.00','1432371549','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('2','1','fex','reg','in','point1','20.00','20.00','1432371549','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('3','2','fex2','reg','in','point','20.00','20.00','1433513427','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('4','2','fex2','reg','in','point1','20.00','20.00','1433513427','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('5','3','fex3','reg','in','point','20.00','20.00','1433595507','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('6','3','fex3','reg','in','point1','20.00','20.00','1433595507','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('7','4','fex4','reg','in','point','20.00','20.00','1433646966','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('8','4','fex4','reg','in','point1','20.00','20.00','1433646966','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('9','5','abcec','reg','in','point','20.00','20.00','1433657109','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('10','5','abcec','reg','in','point1','20.00','20.00','1433657109','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('11','6','Raymondgype','reg','in','point','20.00','20.00','1433661006','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('12','6','Raymondgype','reg','in','point1','20.00','20.00','1433661006','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('13','7','Danielgen','reg','in','point','20.00','20.00','1433662567','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('14','7','Danielgen','reg','in','point1','20.00','20.00','1433662567','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('15','8','Norbertnus','reg','in','point','20.00','20.00','1433663046','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('16','8','Norbertnus','reg','in','point1','20.00','20.00','1433663046','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('17','9','JoshuaTon','reg','in','point','20.00','20.00','1433667222','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('18','9','JoshuaTon','reg','in','point1','20.00','20.00','1433667222','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('19','10','JosephKr','reg','in','point','20.00','20.00','1433674747','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('20','10','JosephKr','reg','in','point1','20.00','20.00','1433674747','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('21','11','Merlincoag','reg','in','point','20.00','20.00','1433676425','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('22','11','Merlincoag','reg','in','point1','20.00','20.00','1433676425','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('23','12','WilliamEl','reg','in','point','20.00','20.00','1433677450','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('24','12','WilliamEl','reg','in','point1','20.00','20.00','1433677450','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('25','13','Gilbertki','reg','in','point','20.00','20.00','1433687121','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('26','13','Gilbertki','reg','in','point1','20.00','20.00','1433687121','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('27','14','RolandDon','reg','in','point','20.00','20.00','1433690163','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('28','14','RolandDon','reg','in','point1','20.00','20.00','1433690163','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('29','15','Steventug','reg','in','point','20.00','20.00','1433690764','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('30','15','Steventug','reg','in','point1','20.00','20.00','1433690764','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('31','16','GregoryFedo','reg','in','point','20.00','20.00','1433692634','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('32','16','GregoryFedo','reg','in','point1','20.00','20.00','1433692634','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('33','17','Matthewea','reg','in','point','20.00','20.00','1433694070','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('34','17','Matthewea','reg','in','point1','20.00','20.00','1433694070','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('35','18','Andreslep','reg','in','point','20.00','20.00','1433696048','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('36','18','Andreslep','reg','in','point1','20.00','20.00','1433696048','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('37','19','TimothyOi','reg','in','point','20.00','20.00','1433697680','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('38','19','TimothyOi','reg','in','point1','20.00','20.00','1433697680','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('39','20','RaymondSt','reg','in','point','20.00','20.00','1433697932','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('40','20','RaymondSt','reg','in','point1','20.00','20.00','1433697932','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('41','21','JessieveR','reg','in','point','20.00','20.00','1433698782','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('42','21','JessieveR','reg','in','point1','20.00','20.00','1433698782','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('43','22','RobertPam','reg','in','point','20.00','20.00','1433706118','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('44','22','RobertPam','reg','in','point1','20.00','20.00','1433706118','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('45','23','JeffreyLep','reg','in','point','20.00','20.00','1433712872','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('46','23','JeffreyLep','reg','in','point1','20.00','20.00','1433712872','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('47','24','Roberttot','reg','in','point','20.00','20.00','1433714589','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('48','24','Roberttot','reg','in','point1','20.00','20.00','1433714589','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('49','25','HollisSacy','reg','in','point','20.00','20.00','1433716091','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('50','25','HollisSacy','reg','in','point1','20.00','20.00','1433716091','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('51','26','Michaelnub','reg','in','point','20.00','20.00','1433716442','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('52','26','Michaelnub','reg','in','point1','20.00','20.00','1433716442','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('53','27','Enriquesn','reg','in','point','20.00','20.00','1433735871','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('54','27','Enriquesn','reg','in','point1','20.00','20.00','1433735871','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('55','28','Vincenttymn','reg','in','point','20.00','20.00','1433745718','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('56','28','Vincenttymn','reg','in','point1','20.00','20.00','1433745718','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('57','29','Danieldecy','reg','in','point','20.00','20.00','1433748361','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('58','29','Danieldecy','reg','in','point1','20.00','20.00','1433748361','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('59','30','JosephEi','reg','in','point','20.00','20.00','1433749601','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('60','30','JosephEi','reg','in','point1','20.00','20.00','1433749601','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('61','31','PhilipEa','reg','in','point','20.00','20.00','1433751379','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('62','31','PhilipEa','reg','in','point1','20.00','20.00','1433751379','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('63','32','ThomasBug','reg','in','point','20.00','20.00','1433752744','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('64','32','ThomasBug','reg','in','point1','20.00','20.00','1433752744','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('65','33','esolisinna','reg','in','point','20.00','20.00','1433762620','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('66','33','esolisinna','reg','in','point1','20.00','20.00','1433762620','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('67','34','DanielOr','reg','in','point','20.00','20.00','1433763547','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('68','34','DanielOr','reg','in','point1','20.00','20.00','1433763547','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('69','35','RobertNexy','reg','in','point','20.00','20.00','1433771965','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('70','35','RobertNexy','reg','in','point1','20.00','20.00','1433771965','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('71','36','DanielPt','reg','in','point','20.00','20.00','1433775828','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('72','36','DanielPt','reg','in','point1','20.00','20.00','1433775828','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('73','37','TimothyOl','reg','in','point','20.00','20.00','1433776973','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('74','37','TimothyOl','reg','in','point1','20.00','20.00','1433776973','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('75','38','PhilipSr','reg','in','point','20.00','20.00','1433778340','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('76','38','PhilipSr','reg','in','point1','20.00','20.00','1433778340','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('77','39','Normanei','reg','in','point','20.00','20.00','1433779236','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('78','39','Normanei','reg','in','point1','20.00','20.00','1433779236','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('79','40','Vincentma','reg','in','point','20.00','20.00','1433780839','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('80','40','Vincentma','reg','in','point1','20.00','20.00','1433780839','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('81','41','DonaldPl','reg','in','point','20.00','20.00','1433782184','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('82','41','DonaldPl','reg','in','point1','20.00','20.00','1433782184','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('83','42','Philiprist','reg','in','point','20.00','20.00','1433782779','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('84','42','Philiprist','reg','in','point1','20.00','20.00','1433782779','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('85','43','Albertokt','reg','in','point','20.00','20.00','1433785740','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('86','43','Albertokt','reg','in','point1','20.00','20.00','1433785740','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('87','44','Marvinnete','reg','in','point','20.00','20.00','1433790256','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('88','44','Marvinnete','reg','in','point1','20.00','20.00','1433790256','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('89','45','RobertSt','reg','in','point','20.00','20.00','1433793623','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('90','45','RobertSt','reg','in','point1','20.00','20.00','1433793623','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('91','46','Walterrof','reg','in','point','20.00','20.00','1433804626','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('92','46','Walterrof','reg','in','point1','20.00','20.00','1433804626','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('93','47','Michealsl','reg','in','point','20.00','20.00','1433805107','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('94','47','Michealsl','reg','in','point1','20.00','20.00','1433805107','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('95','48','WilliamLer','reg','in','point','20.00','20.00','1433805527','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('96','48','WilliamLer','reg','in','point1','20.00','20.00','1433805527','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('97','49','DennisMt','reg','in','point','20.00','20.00','1433809940','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('98','49','DennisMt','reg','in','point1','20.00','20.00','1433809940','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('99','50','MartinLip','reg','in','point','20.00','20.00','1433831660','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('100','50','MartinLip','reg','in','point1','20.00','20.00','1433831660','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('101','51','Kennethol','reg','in','point','20.00','20.00','1433833855','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('102','51','Kennethol','reg','in','point1','20.00','20.00','1433833855','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('103','52','Javiergraw','reg','in','point','20.00','20.00','1433834585','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('104','52','Javiergraw','reg','in','point1','20.00','20.00','1433834585','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('105','53','PhilipBype','reg','in','point','20.00','20.00','1433836419','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('106','53','PhilipBype','reg','in','point1','20.00','20.00','1433836419','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('107','54','SidneyEn','reg','in','point','20.00','20.00','1433836883','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('108','54','SidneyEn','reg','in','point1','20.00','20.00','1433836883','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('109','55','DonaldOl','reg','in','point','20.00','20.00','1433850166','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('110','55','DonaldOl','reg','in','point1','20.00','20.00','1433850166','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('111','56','MichaelSa','reg','in','point','20.00','20.00','1433862597','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('112','56','MichaelSa','reg','in','point1','20.00','20.00','1433862597','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('113','57','JanineSart','reg','in','point','20.00','20.00','1433880744','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('114','57','JanineSart','reg','in','point1','20.00','20.00','1433880744','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('115','58','Thomassek','reg','in','point','20.00','20.00','1433950784','用户行为积分变更(reg)','');
INSERT INTO modoer_point_log VALUES ('116','58','Thomassek','reg','in','point1','20.00','20.00','1433950784','用户行为积分变更(reg)','');

DROP TABLE IF EXISTS modoer_product;
CREATE TABLE `modoer_product` (
  `pid` mediumint(8) unsigned NOT NULL auto_increment,
  `modelid` smallint(5) unsigned NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `city_id` mediumint(8) unsigned NOT NULL default '0',
  `pgcatid` mediumint(8) unsigned NOT NULL default '0',
  `gcatid` mediumint(8) unsigned NOT NULL default '0',
  `catid` mediumint(8) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `subject` varchar(60) NOT NULL default '',
  `shape_code` char(15) NOT NULL default '',
  `brief_code` char(15) NOT NULL default '',
  `price` decimal(9,2) unsigned NOT NULL default '0.00',
  `user_price` varchar(255) NOT NULL default '',
  `usergroup` varchar(255) NOT NULL default '',
  `grade` smallint(5) NOT NULL default '0',
  `pageview` mediumint(8) unsigned NOT NULL default '0',
  `comments` mediumint(8) NOT NULL default '0',
  `picture` varchar(255) NOT NULL default '',
  `pictures` text NOT NULL,
  `thumb` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '1',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  `stock` int(11) unsigned NOT NULL default '0',
  `p_style` tinyint(1) unsigned NOT NULL default '0',
  `giveintegral` int(11) unsigned NOT NULL default '0',
  `integral` int(10) unsigned NOT NULL default '0',
  `promote` decimal(9,2) unsigned NOT NULL default '0.00',
  `promote_start` int(10) unsigned NOT NULL default '0',
  `promote_end` int(10) unsigned NOT NULL default '0',
  `is_on_sale` tinyint(1) unsigned NOT NULL default '0',
  `is_cod` tinyint(1) unsigned NOT NULL default '0',
  `is_freight` tinyint(1) unsigned NOT NULL default '1',
  `freight` tinyint(1) unsigned NOT NULL default '1',
  `free_shipping` tinyint(1) unsigned NOT NULL default '0',
  `tag_keyword` varchar(255) NOT NULL default '',
  `last_update` int(10) unsigned NOT NULL default '0',
  `sales` mediumint(8) unsigned NOT NULL default '0',
  `finer` tinyint(1) unsigned NOT NULL default '0',
  `weight` int(10) unsigned NOT NULL default '0',
  `shipping` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `last_update` (`last_update`),
  KEY `newproduct` (`status`,`is_on_sale`,`city_id`,`dateline`),
  KEY `gcatid` (`status`,`is_on_sale`,`gcatid`,`city_id`,`sales`),
  KEY `pgcatid` (`status`,`is_on_sale`,`pgcatid`,`city_id`,`sales`),
  KEY `finer` (`finer`,`pgcatid`),
  KEY `promote` (`promote`,`promote_start`,`promote_end`,`city_id`),
  KEY `catid` (`sid`,`catid`,`sales`),
  KEY `shape_code` (`shape_code`),
  KEY `brief_code` (`brief_code`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

INSERT INTO modoer_product VALUES ('1','1','2','0','1','3','2','1433753498','0','','XX婚礼酒店宴会厅','','','100000.00',',-1,-1,-1,-1,-1,',',10,12,13,14,15,','0','5','0','uploads/product/2015-06/33_1433753485.jpg','a:1:{s:13:\"33_1433753485\";s:41:\"uploads/product/2015-06/33_1433753485.jpg\";}','uploads/product/2015-06/thumb_33_1433753485.jpg','','1','0','0','1','0','0','0.00','0','0','1','0','1','0','0','|婚礼套餐|','1433753498','1','0','0','');
INSERT INTO modoer_product VALUES ('3','1','2','0','1','3','3','1433753562','0','','XX婚庆策划公司','','','50000.00',',-1,-1,-1,-1,-1,',',10,12,13,14,15,','0','3','0','uploads/product/2015-06/22_1433753548.jpg','a:1:{s:13:\"22_1433753548\";s:41:\"uploads/product/2015-06/22_1433753548.jpg\";}','uploads/product/2015-06/thumb_22_1433753548.jpg','','1','0','0','1','0','0','0.00','0','0','1','0','1','0','0','|婚礼套餐|','1433753562','1','0','0','');
INSERT INTO modoer_product VALUES ('4','1','2','0','1','3','4','1433753617','0','','XX婚纱摄影套餐','','','30000.00',',-1,-1,-1,-1,-1,',',10,12,13,14,15,','0','11','0','uploads/product/2015-06/81_1433753600.jpg','a:1:{s:13:\"81_1433753600\";s:41:\"uploads/product/2015-06/81_1433753600.jpg\";}','uploads/product/2015-06/thumb_81_1433753600.jpg','','1','0','4','1','0','0','0.00','0','0','1','0','1','0','0','|婚礼套餐|','1433753617','2','0','0','');

DROP TABLE IF EXISTS modoer_product_buyattr;
CREATE TABLE `modoer_product_buyattr` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `name` char(60) NOT NULL default '',
  `value` text NOT NULL,
  `listorder` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`,`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_product_cart;
CREATE TABLE `modoer_product_cart` (
  `cid` mediumint(8) unsigned NOT NULL auto_increment,
  `cartid` varchar(80) NOT NULL default '',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `pname` varchar(255) NOT NULL default '',
  `uid` tinyint(1) unsigned NOT NULL default '0',
  `p_image` varchar(255) NOT NULL default '',
  `p_style` tinyint(1) unsigned NOT NULL default '1',
  `quantity` int(10) unsigned NOT NULL default '0',
  `price` decimal(9,2) unsigned NOT NULL default '0.00',
  `buyattr` text NOT NULL,
  `overdue` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;


DROP TABLE IF EXISTS modoer_product_category;
CREATE TABLE `modoer_product_category` (
  `catid` mediumint(8) unsigned NOT NULL auto_increment,
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(20) NOT NULL default '',
  `listorder` smallint(5) NOT NULL default '0',
  `num` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`catid`),
  KEY `sid` (`sid`,`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

INSERT INTO modoer_product_category VALUES ('1','2','套餐','0','0');
INSERT INTO modoer_product_category VALUES ('2','2','酒店','0','0');
INSERT INTO modoer_product_category VALUES ('3','2','婚庆策划','0','0');
INSERT INTO modoer_product_category VALUES ('4','2','婚纱摄影','0','0');

DROP TABLE IF EXISTS modoer_product_data;
CREATE TABLE `modoer_product_data` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `pid` mediumint(8) unsigned NOT NULL,
  `fieldid` mediumint(8) unsigned NOT NULL,
  `fieldname` varchar(60) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `pid` (`pid`,`fieldid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

INSERT INTO modoer_product_data VALUES ('1','1','1','content','XX婚礼酒店XX宴会厅');
INSERT INTO modoer_product_data VALUES ('3','3','1','content','XX婚庆策划公司XX风格布置');
INSERT INTO modoer_product_data VALUES ('4','4','1','content','XX婚纱摄影XX主题摄影套餐');

DROP TABLE IF EXISTS modoer_product_field;
CREATE TABLE `modoer_product_field` (
  `pid` mediumint(8) unsigned NOT NULL,
  `use_delivery_time` tinyint(1) unsigned NOT NULL default '0',
  `use_dispatch_time` tinyint(1) unsigned NOT NULL default '0',
  `cod_price` decimal(9,2) unsigned NOT NULL default '0.00',
  `freight_price_snail` decimal(9,2) unsigned NOT NULL default '0.00',
  `freight_price_exp` decimal(9,2) unsigned NOT NULL default '0.00',
  `freight_price_ems` decimal(9,2) NOT NULL default '0.00',
  PRIMARY KEY  (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_product_field VALUES ('1','0','0','0.00','0.00','0.00','0.00');
INSERT INTO modoer_product_field VALUES ('2','0','0','0.00','0.00','0.00','0.00');
INSERT INTO modoer_product_field VALUES ('3','0','0','0.00','0.00','0.00','0.00');
INSERT INTO modoer_product_field VALUES ('4','0','0','0.00','0.00','0.00','0.00');

DROP TABLE IF EXISTS modoer_product_gcategory;
CREATE TABLE `modoer_product_gcategory` (
  `catid` mediumint(8) unsigned NOT NULL auto_increment,
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `level` tinyint(1) unsigned NOT NULL default '0',
  `name` varchar(20) NOT NULL default '',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  `enabled` tinyint(1) unsigned NOT NULL default '1',
  `subcats` varchar(255) NOT NULL default '',
  `nonuse_subcats` varchar(255) NOT NULL default '',
  `attcat` varchar(255) NOT NULL default '',
  `modelid` smallint(5) unsigned NOT NULL default '0',
  `title` varchar(500) NOT NULL default '',
  `keywords` varchar(500) NOT NULL default '',
  `description` varchar(500) NOT NULL default '',
  PRIMARY KEY  (`catid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AUTO_INCREMENT=4;

INSERT INTO modoer_product_gcategory VALUES ('1','0','1','婚礼套餐','0','1','2','','','0','婚礼套餐','婚礼套餐','婚礼套餐');
INSERT INTO modoer_product_gcategory VALUES ('2','1','2','婚礼套餐','0','1','3','','','1','','','');
INSERT INTO modoer_product_gcategory VALUES ('3','2','3','婚礼套餐','0','1','','','','1','','','');

DROP TABLE IF EXISTS modoer_product_model;
CREATE TABLE `modoer_product_model` (
  `modelid` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `tablename` varchar(20) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `usearea` tinyint(1) NOT NULL default '0',
  `item_name` varchar(200) NOT NULL default '',
  `item_unit` varchar(200) NOT NULL default '',
  `tplname_list` varchar(200) NOT NULL default '',
  `tplname_detail` varchar(200) NOT NULL default '',
  `disable` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`modelid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_product_model VALUES ('1','婚庆套餐','product_data','','0','','','','','0');

DROP TABLE IF EXISTS modoer_product_order;
CREATE TABLE `modoer_product_order` (
  `orderid` int(10) unsigned NOT NULL auto_increment,
  `ordersn` varchar(20) NOT NULL default '',
  `orderstyle` tinyint(1) unsigned NOT NULL default '1',
  `sellerid` int(10) unsigned NOT NULL default '0',
  `sellername` varchar(100) default '',
  `buyerid` int(10) unsigned NOT NULL default '0',
  `buyername` varchar(100) default '',
  `buyeremail` varchar(60) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `addtime` int(10) unsigned NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `payment_id` int(10) unsigned default '0',
  `paymentname` varchar(100) default '',
  `paymentcode` varchar(20) NOT NULL default '',
  `paytime` int(10) unsigned default '0',
  `paymessage` varchar(255) NOT NULL default '',
  `shiptime` int(10) unsigned default '0',
  `invoice_no` varchar(255) default '',
  `kdcom` varchar(255) NOT NULL default 'other',
  `finishedtime` int(10) unsigned NOT NULL default '0',
  `goods_amount` decimal(9,2) unsigned NOT NULL default '0.00',
  `order_amount` decimal(10,2) unsigned NOT NULL default '0.00',
  `integral_amount` decimal(9,2) unsigned NOT NULL default '0.00',
  `integral` int(10) unsigned NOT NULL default '0',
  `integral_pointtype` varchar(15) NOT NULL default '',
  `brokerage` decimal(9,2) unsigned NOT NULL default '0.00',
  `is_serial` tinyint(1) unsigned NOT NULL default '0',
  `is_cod` tinyint(1) unsigned NOT NULL default '0',
  `is_offline_pay` enum('null','owner','admin') NOT NULL default 'null',
  `offline_pay_role` varchar(20) NOT NULL,
  `remark` varchar(255) NOT NULL default '',
  `amount_changed` smallint(5) unsigned NOT NULL default '0',
  `delivery_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`orderid`),
  KEY `ordersn` (`ordersn`,`sellerid`),
  KEY `sellername` (`sellername`),
  KEY `buyername` (`buyername`),
  KEY `addtime` (`sid`,`addtime`),
  KEY `paytime` (`sid`,`paytime`),
  KEY `sid` (`sid`,`status`,`orderid`),
  KEY `buyerid` (`buyerid`,`status`,`orderid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

INSERT INTO modoer_product_order VALUES ('1','1433783486CN5880','1','0','','1','fex','wuxiaofengdeemail@126.com','1','1433783534','2','0','','','0','','0','','other','0','30000.00','30000.00','0.00','0','point1','0.00','0','0','null','','','0','0');
INSERT INTO modoer_product_order VALUES ('2','1433811840CN7760','1','2','fex2','1','fex','wuxiaofengdeemail@126.com','1','1433817360','2','0','','','0','','0','','other','0','180000.00','180000.00','0.00','0','point1','0.00','0','0','null','','','0','0');

DROP TABLE IF EXISTS modoer_product_orderextm;
CREATE TABLE `modoer_product_orderextm` (
  `orderid` int(10) unsigned NOT NULL default '0',
  `username` varchar(60) NOT NULL default '',
  `address` varchar(255) default '',
  `zipcode` int(6) unsigned default '0',
  `mobile` varchar(60) default '',
  `shipid` int(10) unsigned default '10',
  `shipname` varchar(100) default '',
  `shipfee` decimal(9,2) NOT NULL default '0.00',
  PRIMARY KEY  (`orderid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_product_orderextm VALUES ('1','123','123123123123123123123','0','13555555555','0','卖家承担运费','0.00');
INSERT INTO modoer_product_orderextm VALUES ('2','123','123123123123123123123','0','13555555555','0','卖家承担运费','0.00');

DROP TABLE IF EXISTS modoer_product_ordergoods;
CREATE TABLE `modoer_product_ordergoods` (
  `gid` int(10) unsigned NOT NULL auto_increment,
  `orderid` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `pname` varchar(255) NOT NULL default '',
  `price` decimal(9,2) unsigned NOT NULL default '0.00',
  `quantity` int(10) unsigned NOT NULL default '1',
  `buyattr` text NOT NULL,
  `goods_image` varchar(255) default '',
  `commented` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`gid`),
  KEY `orderid` (`orderid`,`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

INSERT INTO modoer_product_ordergoods VALUES ('1','1','4','XX婚纱摄影套餐','30000.00','1','','uploads/product/2015-06/thumb_81_1433753600.jpg','0');
INSERT INTO modoer_product_ordergoods VALUES ('2','2','4','XX婚纱摄影套餐','30000.00','1','','uploads/product/2015-06/thumb_81_1433753600.jpg','0');
INSERT INTO modoer_product_ordergoods VALUES ('3','2','1','XX婚礼酒店宴会厅','100000.00','1','','uploads/product/2015-06/thumb_33_1433753485.jpg','0');
INSERT INTO modoer_product_ordergoods VALUES ('4','2','3','XX婚庆策划公司','50000.00','1','','uploads/product/2015-06/thumb_22_1433753548.jpg','0');

DROP TABLE IF EXISTS modoer_product_orderlog;
CREATE TABLE `modoer_product_orderlog` (
  `logid` int(10) unsigned NOT NULL auto_increment,
  `orderid` int(10) unsigned NOT NULL default '0',
  `operator` varchar(60) NOT NULL default '',
  `order_status` varchar(60) NOT NULL default '',
  `changed_status` varchar(60) NOT NULL default '',
  `remark` varchar(255) default '',
  `log_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`logid`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_product_serial;
CREATE TABLE `modoer_product_serial` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `oid` mediumint(8) NOT NULL default '0',
  `uid` mediumint(8) NOT NULL default '0',
  `serial` varchar(255) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `dateline` int(10) unsigned NOT NULL default '0',
  `sendtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_product_shipping;
CREATE TABLE `modoer_product_shipping` (
  `shipid` mediumint(8) unsigned NOT NULL auto_increment,
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `shipname` varchar(100) NOT NULL default '',
  `shipdesc` varchar(255) NOT NULL default '',
  `price` decimal(9,2) unsigned NOT NULL default '0.00',
  `enabled` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`shipid`),
  KEY `uid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_productatt;
CREATE TABLE `modoer_productatt` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `pid` mediumint(8) unsigned NOT NULL default '0',
  `attid` mediumint(8) unsigned NOT NULL default '0',
  `att_catid` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `sid` (`pid`,`attid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_reports;
CREATE TABLE `modoer_reports` (
  `reportid` mediumint(8) unsigned NOT NULL auto_increment,
  `rid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(16) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `sort` tinyint(1) NOT NULL default '0',
  `reportcontent` mediumtext NOT NULL,
  `disposal` tinyint(1) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `disposaltime` int(10) NOT NULL default '0',
  `reportremark` mediumtext NOT NULL,
  `update_point` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`reportid`),
  KEY `disposal` (`disposal`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_responds;
CREATE TABLE `modoer_responds` (
  `respondid` mediumint(8) unsigned NOT NULL auto_increment,
  `rid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `content` text NOT NULL,
  `posttime` int(10) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '1',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`respondid`),
  KEY `uid` (`uid`,`status`),
  KEY `reviewid` (`rid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_review;
CREATE TABLE `modoer_review` (
  `rid` mediumint(8) unsigned NOT NULL auto_increment,
  `idtype` varchar(30) NOT NULL default '',
  `id` mediumint(8) unsigned NOT NULL default '0',
  `city_id` smallint(5) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '1',
  `pcatid` smallint(5) unsigned NOT NULL default '0',
  `sort1` tinyint(1) unsigned NOT NULL default '0',
  `sort2` tinyint(1) unsigned NOT NULL default '0',
  `sort3` tinyint(1) unsigned NOT NULL default '0',
  `sort4` tinyint(1) unsigned NOT NULL default '0',
  `sort5` tinyint(1) unsigned NOT NULL default '0',
  `sort6` tinyint(1) unsigned NOT NULL default '0',
  `sort7` tinyint(1) unsigned NOT NULL default '0',
  `sort8` tinyint(1) unsigned NOT NULL default '0',
  `price` int(10) unsigned NOT NULL default '0',
  `best` tinyint(1) unsigned NOT NULL default '0',
  `digest` tinyint(1) NOT NULL default '0',
  `havepic` tinyint(1) NOT NULL default '0',
  `havevoice` tinyint(1) NOT NULL default '0' COMMENT '是否有语音点评',
  `posttime` int(10) NOT NULL default '0',
  `isupdate` tinyint(1) NOT NULL default '0',
  `flowers` int(8) unsigned NOT NULL default '0',
  `responds` int(8) unsigned NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `title` varchar(60) NOT NULL default '',
  `content` text NOT NULL,
  `taggroup` text NOT NULL,
  `pictures` text NOT NULL,
  `voice_file` varchar(255) NOT NULL default '' COMMENT '语音文件',
  `hide_name` tinyint(1) unsigned NOT NULL default '0',
  `source` tinyint(1) unsigned NOT NULL default '0' COMMENT '点评来源，0：网站，1：手机模块',
  PRIMARY KEY  (`rid`),
  KEY `sid` (`id`,`status`),
  KEY `uid` (`uid`,`status`),
  KEY `city_id` (`city_id`,`best`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_review_opt;
CREATE TABLE `modoer_review_opt` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `gid` smallint(5) unsigned NOT NULL default '0',
  `flag` varchar(10) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `listorder` smallint(5) NOT NULL default '0',
  `enable` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 AUTO_INCREMENT=9;

INSERT INTO modoer_review_opt VALUES ('1','1','sort1','口味','1','1');
INSERT INTO modoer_review_opt VALUES ('2','1','sort2','服务','2','1');
INSERT INTO modoer_review_opt VALUES ('3','1','sort3','环境','3','1');
INSERT INTO modoer_review_opt VALUES ('4','1','sort4','性价比','4','1');
INSERT INTO modoer_review_opt VALUES ('5','1','sort5','R5','5','0');
INSERT INTO modoer_review_opt VALUES ('6','1','sort6','R6','6','0');
INSERT INTO modoer_review_opt VALUES ('7','1','sort7','R7','7','0');
INSERT INTO modoer_review_opt VALUES ('8','1','sort8','R8','8','0');

DROP TABLE IF EXISTS modoer_review_opt_group;
CREATE TABLE `modoer_review_opt_group` (
  `gid` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `des` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_review_opt_group VALUES ('1','默认点评项组','系统默认安装');

DROP TABLE IF EXISTS modoer_search_cache;
CREATE TABLE `modoer_search_cache` (
  `ssid` mediumint(8) NOT NULL auto_increment,
  `keyword` varchar(60) NOT NULL default '0',
  `count` mediumint(8) NOT NULL default '0',
  `total` mediumint(8) NOT NULL default '0',
  `catid` smallint(5) NOT NULL default '0',
  `city_id` varchar(10) NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ssid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_session;
CREATE TABLE `modoer_session` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'ID',
  `uniq` char(32) NOT NULL COMMENT '唯一会话ID',
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '关联登录会员ID',
  `last_time` int(10) unsigned NOT NULL default '0' COMMENT '最后会话时间',
  `last_url` char(255) NOT NULL default '' COMMENT '最后访问页面URL',
  `ip_address` char(15) NOT NULL default '' COMMENT '访客IP地址',
  `is_mobile` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否手机web访问',
  `user_agent` char(255) NOT NULL default '' COMMENT '浏览器',
  `content` text NOT NULL COMMENT '会话内容',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uniq` (`uniq`),
  KEY `last_time` (`last_time`),
  KEY `uid_last_time` (`uid`,`last_time`)
) ENGINE=MyISAM AUTO_INCREMENT=1873 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1873;

INSERT INTO modoer_session VALUES ('1867','33e68c2788014417be0931162b08b524','0','1433988352','/space.php?act=30&pr=mylist_favorites','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1866','1b9d475081154f8afad0162331f7dd25','0','1433988300','/space.php?act=30&pr=item','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1865','e975b05e5fd0a5f0198d7d1c4dc55474','0','1433988281','/space.php?act=30&pr=group_replies','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1864','6ed44d2ca9cbaf06a49433f7244e1d2d','0','1433988253','/space.php?act=29&pr=item','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1862','e35bbe493fc50db346b1fec3a68b3214','0','1433988425','/admin.php?module=product&act=field_type','60.177.221.250','0','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36','');
INSERT INTO modoer_session VALUES ('1863','2178f1adc8d2036fed2e35cb859eb805','0','1433988226','/space.php?act=29&pr=mylist_favorites','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1849','f6ef0c5e43432b8164559e6db122f5bc','0','1433987661','/space.php?act=26&pr=item','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1850','1895a98c68aa960ad90952e54fbe8996','0','1433987785','/space.php?act=26&pr=mylist_favorites','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1861','8645442eb4c8a2151c420396088e28f5','0','1433988198','/space.php?act=29&pr=item','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1860','a9db12cd84631c45a78bbd65f0479d17','0','1433988137','/space.php?act=29&pr=group_replies','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1859','091f22fecf83b88c26130893ed8865c8','0','1433988055','/space.php?act=29&pr=group_replies','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1858','4ee3318017072a8c6bb5f1ad832ce520','0','1433988049','/space.php?act=28&pr=mylist_favorites','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1857','36931825a67d64eb4792e40d511202b0','0','1433988038','/space.php?act=28&pr=item','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1856','e9019bbf95747eaf05bfedcb15565734','0','1433988018','/space.php?act=28&pr=group_replies','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1855','1e34de0d9ce0c1eb3fb5caea0fe7c546','0','1433987983','/space.php?act=28&pr=group_replies','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1854','360dd942f22e70df7f69f0cfe42ae9c8','0','1433988486','/admin.php?module=modoer&act=tools&op=cache','115.205.211.141','0','Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36','');
INSERT INTO modoer_session VALUES ('1853','4df5aab260d87f1d96bed6f84f3e21e7','0','1433987937','/space.php?act=27&pr=mylist_favorites','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1851','a000c3415c3c09d615a02c3730646bfc','0','1433987876','/space.php?act=27&pr=group_replies','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1852','ec0e6ebc189cee80d59c764bb72dec42','0','1433987925','/space.php?act=27&pr=item','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1872','ecaeac894359426bc1a1cab41b350654','0','1433988490','/admin.php?module=modoer&act=cpmenu&tab=setting&_page_param_rand=0.6609712957870215','180.153.206.22','1','Mozilla/5.0 (Linux; U; Android 4.4.2; zh-cn; GT-I9500 Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 MQQBrowser/5.0 QQ-Manager Mobile Safari/537.36','');
INSERT INTO modoer_session VALUES ('1871','2ac05b8df6339d824d0a2d40f59f492b','0','1433988480','/space.php?act=32&pr=group_replies','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1869','8018685f183f88e6c4419d58ba595755','0','1433988404','/space.php?act=31&pr=item','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1870','000371706cfaf0d16072f242e1fcb18f','0','1433988462','/space.php?act=31&pr=mylist_favorites','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1868','7d6412f6fe25617f3200c226c1eed617','0','1433988391','/space.php?act=31&pr=group_replies','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');
INSERT INTO modoer_session VALUES ('1848','491c0181ee5645b663c00c3a7d0a0779','0','1433987583','/review.php?act=list&type=item_subject&pid=5&day=7&filter=all','62.210.97.48','0','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)','');

DROP TABLE IF EXISTS modoer_space_attend;
CREATE TABLE `modoer_space_attend` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL default '0',
  `is_part` tinyint(1) unsigned NOT NULL default '1',
  `name` varchar(32) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `part_num` smallint(4) unsigned NOT NULL default '0',
  `content` varchar(255) NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 AUTO_INCREMENT=10;

INSERT INTO modoer_space_attend VALUES ('1','1','0','13123','','0','','1432961137');
INSERT INTO modoer_space_attend VALUES ('2','1','1','111','222','333','444','1432961161');
INSERT INTO modoer_space_attend VALUES ('3','1','1','11133123','222','333','444','1432961223');
INSERT INTO modoer_space_attend VALUES ('6','1','1','玩儿玩儿','','0','','1432961330');
INSERT INTO modoer_space_attend VALUES ('5','1','1','斯蒂芬斯蒂芬','','0','','1432961262');
INSERT INTO modoer_space_attend VALUES ('7','2','1','234234','','0','','1433561589');
INSERT INTO modoer_space_attend VALUES ('8','2','1','林琳','13534574565','12','新婚快乐','1433561619');
INSERT INTO modoer_space_attend VALUES ('9','4','1','小明','12345651245','1','','1433647298');

DROP TABLE IF EXISTS modoer_space_music;
CREATE TABLE `modoer_space_music` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `listorder` mediumint(5) unsigned NOT NULL default '0',
  `src` varchar(255) NOT NULL,
  `name` varchar(60) NOT NULL,
  `des` varchar(255) NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

INSERT INTO modoer_space_music VALUES ('2','1','uploads/space/2015-05/31_1432523656.mp3','歌曲1','12312311','0','1');
INSERT INTO modoer_space_music VALUES ('4','0','uploads/space/2015-06/9_1433510491.mp3','歌曲2','12312311','1433510491','0');

DROP TABLE IF EXISTS modoer_space_new;
CREATE TABLE `modoer_space_new` (
  `uid` int(10) unsigned NOT NULL,
  `theme` int(10) unsigned NOT NULL default '0',
  `music` int(10) unsigned NOT NULL default '0',
  `cover` varchar(255) NOT NULL,
  `photobg` varchar(255) NOT NULL,
  `my_name` varchar(30) NOT NULL,
  `my_des` varchar(255) NOT NULL,
  `other_id` tinyint(1) unsigned NOT NULL default '0',
  `other_name` varchar(30) NOT NULL,
  `other_des` varchar(255) NOT NULL,
  `other_avatar` varchar(255) NOT NULL,
  `wedding_timestamp` int(10) unsigned NOT NULL default '0',
  `wedding_address` varchar(255) NOT NULL,
  `wedding_map_point` varchar(255) NOT NULL,
  `hotel` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `is_rsvp` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_space_new VALUES ('1','4','4','uploads/space/2015-05/47_1432754440.jpg','','林SOI44','yutrytrytryt123123123','1','qwev','iuyiuyiuyiuyiuy23123123刚恢复发货','uploads/space/2015-05/89_1433037988.jpg','1434124080','潮州市饶平县青岚地质公园','116.845071,23.738598','艾尔123123','','0');
INSERT INTO modoer_space_new VALUES ('2','4','4','','','456','445566','1','123','112233','uploads/space/2015-06/95_1433567788.gif','1435334280','潮州市饶平县饶平大酒店','116.993774,23.67356','饶平大酒店','wer','1');
INSERT INTO modoer_space_new VALUES ('3','3','4','','','','','0','','','uploads/space/2015-06/32_1433596263.gif','0','','','','werwer','0');
INSERT INTO modoer_space_new VALUES ('4','4','4','uploads/space/2015-06/28_1433647073.jpg','','男方','男方描述','0','女方','女方描述','uploads/space/2015-06/80_1433647104.jpg','1433980800','潮州市饶平县黄冈镇','117.012021,23.67202','饶平大酒店','','1');
INSERT INTO modoer_space_new VALUES ('5','3','0','','','','','0','新娘','新郎','','0','','','','','1');

DROP TABLE IF EXISTS modoer_space_story;
CREATE TABLE `modoer_space_story` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL default '0',
  `path` varchar(255) NOT NULL,
  `title` varchar(32) NOT NULL,
  `content` text NOT NULL,
  `media_type` tinyint(1) unsigned NOT NULL default '1',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 AUTO_INCREMENT=40;

INSERT INTO modoer_space_story VALUES ('1','1','uploads/space/2015-05/87_1432791229.jpg','','1','1','1432804081');
INSERT INTO modoer_space_story VALUES ('33','2','uploads/space/2015-06/60_1433559344.jpg','','我想和你虚度时光，比如低头看鱼，比如把茶杯留在桌子上，离开，浪费它们好看的阴影。我还想连落日一起浪费，比如散步，一直消磨到星光满天。','1','1433559347');
INSERT INTO modoer_space_story VALUES ('2','1','uploads/space/2015-05/2_1432795727.jpg','','2','1','1432804081');
INSERT INTO modoer_space_story VALUES ('3','1','uploads/space/2015-05/95_1432795831.jpg','','3','1','1432804081');
INSERT INTO modoer_space_story VALUES ('32','2','uploads/space/2015-06/12_1433559336.jpg','','我想一直陪你走很久很久。久到那时的我都记不清你的名字，却还用宝贝一直称呼你。','1','1433559347');
INSERT INTO modoer_space_story VALUES ('39','4','uploads/space/2015-06/26_1433647187.jpg','','永恒的定义太多，对于我的永恒，只不过是见你的每个瞬间。','1','1433647189');
INSERT INTO modoer_space_story VALUES ('37','3','uploads/space/2015-06/92_1433598241.jpg','','其实全世界最美好的童话，就是一起度过柴米油盐的岁月。','1','1433598243');
INSERT INTO modoer_space_story VALUES ('38','4','uploads/space/2015-06/77_1433647180.jpg','','时间会告诉我们 ：简单的喜欢，最长远；平凡中的陪伴，最心安；懂你的人，最温暖；彼此相爱就是幸福。','1','1433647189');
INSERT INTO modoer_space_story VALUES ('15','1','uploads/space/2015-05/87_1432791229.jpg','','4','1','1432804081');
INSERT INTO modoer_space_story VALUES ('31','1','uploads/space/2015-05/88_1432804247.jpg','','9','1','1432804259');
INSERT INTO modoer_space_story VALUES ('16','1','uploads/space/2015-05/2_1432795727.jpg','','5','1','1432804081');
INSERT INTO modoer_space_story VALUES ('30','1','uploads/space/2015-05/62_1432804076.jpg','','8','1','1432804081');
INSERT INTO modoer_space_story VALUES ('17','1','uploads/space/2015-05/95_1432795831.jpg','','6','1','1432804081');
INSERT INTO modoer_space_story VALUES ('29','1','uploads/space/2015-05/46_1432796832.jpg','','7','1','1432804081');
INSERT INTO modoer_space_story VALUES ('35','3','uploads/space/2015-06/53_1433598192.jpg','','我本想把日子过成诗，时而简单，时而精致。不料却过成了我们的歌，时而不靠谱，时而不着调...但是，有什么关系呢，咱们来日方长，以后的日子慢慢过。','1','1433598208');

DROP TABLE IF EXISTS modoer_space_theme;
CREATE TABLE `modoer_space_theme` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `listorder` smallint(5) unsigned NOT NULL default '0',
  `thumb` varchar(255) NOT NULL,
  `pic1` varchar(255) character set ucs2 NOT NULL,
  `pic2` varchar(255) NOT NULL,
  `pic3` varchar(255) NOT NULL,
  `css` varchar(255) NOT NULL,
  `name` varchar(32) NOT NULL,
  `des` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

INSERT INTO modoer_space_theme VALUES ('4','1','uploads/space/2015-05/12_1432750447.jpg','','','','/templates/main/default/space/theme2.css','主题2','123','','1432977571','1');
INSERT INTO modoer_space_theme VALUES ('3','0','uploads/space/2015-05/10_1432754374.jpg','','','','/templates/main/default/space/theme1.css','主题1','123','','1432971414','1');

DROP TABLE IF EXISTS modoer_space_visit;
CREATE TABLE `modoer_space_visit` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '表主键ID',
  `space_uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '空间用户UID',
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '访客UID',
  `username` char(30) NOT NULL default '' COMMENT '访客昵称',
  `last_time` int(10) unsigned NOT NULL COMMENT '最近一次访问时间',
  `visit_count` int(10) unsigned NOT NULL COMMENT '总共访问次数',
  PRIMARY KEY  (`id`),
  KEY `id_last_time` (`id`,`last_time`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

INSERT INTO modoer_space_visit VALUES ('1','1','3','fex3','1433644304','1');
INSERT INTO modoer_space_visit VALUES ('2','1','2','fex2','1433748075','1');

DROP TABLE IF EXISTS modoer_spaces;
CREATE TABLE `modoer_spaces` (
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `space_styleid` smallint(3) NOT NULL default '0',
  `spacename` varchar(30) NOT NULL default '',
  `spacedescribe` varchar(50) NOT NULL default '',
  `pageview` int(10) NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `pageviews` (`pageview`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_spaces VALUES ('1','0','fex的个人空间','读万卷书模，行万里路！','22');
INSERT INTO modoer_spaces VALUES ('5','0','abcec的个人空间','读万卷书模，行万里路！','0');
INSERT INTO modoer_spaces VALUES ('2','0','fex2的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('26','0','Michaelnub的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('27','0','Enriquesn的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('28','0','Vincenttymn的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('29','0','Danieldecy的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('30','0','JosephEi的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('31','0','PhilipEa的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('32','0','ThomasBug的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('33','0','esolisinna的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('34','0','DanielOr的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('35','0','RobertNexy的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('3','0','fex3的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('47','0','Michealsl的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('48','0','WilliamLer的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('49','0','DennisMt的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('50','0','MartinLip的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('51','0','Kennethol的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('52','0','Javiergraw的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('53','0','PhilipBype的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('54','0','SidneyEn的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('55','0','DonaldOl的个人空间','读万卷书模，行万里路！','1');
INSERT INTO modoer_spaces VALUES ('56','0','MichaelSa的个人空间','读万卷书模，行万里路！','1');

DROP TABLE IF EXISTS modoer_subject;
CREATE TABLE `modoer_subject` (
  `sid` mediumint(8) unsigned NOT NULL auto_increment,
  `domain` char(50) NOT NULL default '',
  `city_id` smallint(5) unsigned NOT NULL default '0',
  `aid` smallint(5) unsigned NOT NULL default '0',
  `pid` smallint(5) unsigned NOT NULL default '0',
  `catid` smallint(5) unsigned NOT NULL default '0',
  `sub_catids` varchar(255) NOT NULL default '',
  `minor_catids` varchar(255) NOT NULL default '',
  `name` varchar(60) NOT NULL default '',
  `subname` varchar(60) NOT NULL default '',
  `avgsort` decimal(5,2) unsigned NOT NULL default '0.00',
  `sort1` decimal(5,2) unsigned NOT NULL default '0.00',
  `sort2` decimal(5,2) unsigned NOT NULL default '0.00',
  `sort3` decimal(5,2) unsigned NOT NULL default '0.00',
  `sort4` decimal(5,2) unsigned NOT NULL default '0.00',
  `sort5` decimal(5,2) unsigned NOT NULL default '0.00',
  `sort6` decimal(5,2) unsigned NOT NULL default '0.00',
  `sort7` decimal(5,2) unsigned NOT NULL default '0.00',
  `sort8` decimal(5,2) unsigned NOT NULL default '0.00',
  `avgprice` int(10) unsigned NOT NULL default '0',
  `best` int(10) unsigned NOT NULL default '0',
  `reviews` int(10) unsigned NOT NULL default '0',
  `voice_reviews` int(10) unsigned NOT NULL default '0',
  `guestbooks` int(10) unsigned NOT NULL default '0',
  `pictures` int(10) unsigned NOT NULL default '0',
  `pageviews` int(10) unsigned NOT NULL default '1',
  `products` mediumint(8) unsigned NOT NULL default '0',
  `coupons` mediumint(8) unsigned NOT NULL default '0',
  `favorites` mediumint(8) unsigned NOT NULL default '0',
  `finer` tinyint(3) unsigned NOT NULL default '0',
  `level` tinyint(3) unsigned NOT NULL default '0',
  `owner` varchar(20) NOT NULL default '',
  `cuid` mediumint(8) unsigned NOT NULL default '0',
  `creator` varchar(20) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `video` varchar(255) NOT NULL default '',
  `thumb` varchar(255) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '1',
  `map_lng` decimal(15,10) NOT NULL default '0.0000000000',
  `map_lat` decimal(15,10) NOT NULL default '0.0000000000',
  `map_geohash` char(20) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sid`),
  KEY `name` (`name`),
  KEY `doamin` (`domain`),
  KEY `catid` (`catid`,`city_id`,`addtime`),
  KEY `catid_2` (`catid`),
  KEY `aid` (`pid`,`aid`),
  KEY `pid` (`pid`,`city_id`,`addtime`),
  KEY `addtime` (`status`,`addtime`),
  KEY `list_aid` (`pid`,`aid`,`status`,`finer`),
  KEY `list_city` (`pid`,`city_id`,`status`,`finer`),
  KEY `tops` (`city_id`,`pid`,`status`,`avgsort`),
  KEY `finer` (`status`,`finer`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

INSERT INTO modoer_subject VALUES ('1','','1','5','1','4','','','李兰兰','','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0','0','0','0','0','1','19','0','0','1','0','0','','0','','1433743836','','uploads/pictures/2015-06/thumb_15_1433743836.jpg','1','0.0000000000','0.0000000000','7zzz','李兰兰');
INSERT INTO modoer_subject VALUES ('2','','1','5','5','6','','','蜜嫁','','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0.00','0','0','0','0','0','0','3','3','0','0','0','0','fex2','0','','1433752793','','','1','0.0000000000','0.0000000000','7zzz','');

DROP TABLE IF EXISTS modoer_subject_package;
CREATE TABLE `modoer_subject_package` (
  `sid` mediumint(8) unsigned NOT NULL,
  `forumid` smallint(5) unsigned NOT NULL default '0',
  `templateid` smallint(5) unsigned NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `tel` varchar(255) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_subject_package VALUES ('2','0','0','蜜嫁婚礼套餐','4008000571','杭州市湖滨路');

DROP TABLE IF EXISTS modoer_subject_shops;
CREATE TABLE `modoer_subject_shops` (
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `tel` varchar(255) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `templateid` smallint(5) unsigned NOT NULL default '0',
  `forumid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_subject_shops VALUES ('1','','','婚礼管家李兰兰的简介','0','0');

DROP TABLE IF EXISTS modoer_subjectapply;
CREATE TABLE `modoer_subjectapply` (
  `applyid` mediumint(8) unsigned NOT NULL auto_increment,
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default '0',
  `applyname` varchar(100) NOT NULL default '',
  `contact` varchar(255) NOT NULL default '',
  `pic` varchar(255) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `dateline` int(10) unsigned NOT NULL default '0',
  `checker` varchar(30) NOT NULL default '',
  `returned` text NOT NULL,
  PRIMARY KEY  (`applyid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_subjectatt;
CREATE TABLE `modoer_subjectatt` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `attid` mediumint(8) unsigned NOT NULL default '0',
  `type` varchar(20) NOT NULL default '',
  `att_catid` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `attid` (`attid`,`sid`),
  KEY `sid` (`sid`,`attid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;

INSERT INTO modoer_subjectatt VALUES ('1','1','1','category','0');
INSERT INTO modoer_subjectatt VALUES ('2','1','11','category','0');
INSERT INTO modoer_subjectatt VALUES ('3','1','4','area','0');
INSERT INTO modoer_subjectatt VALUES ('4','1','8','area','0');
INSERT INTO modoer_subjectatt VALUES ('5','1','5','area','0');
INSERT INTO modoer_subjectatt VALUES ('6','2','12','category','0');
INSERT INTO modoer_subjectatt VALUES ('7','2','13','category','0');
INSERT INTO modoer_subjectatt VALUES ('8','2','4','area','0');
INSERT INTO modoer_subjectatt VALUES ('9','2','8','area','0');
INSERT INTO modoer_subjectatt VALUES ('10','2','5','area','0');

DROP TABLE IF EXISTS modoer_subjectfield;
CREATE TABLE `modoer_subjectfield` (
  `fieldid` mediumint(8) unsigned NOT NULL auto_increment,
  `modelid` smallint(5) NOT NULL default '0',
  `tablename` varchar(25) NOT NULL default '',
  `fieldname` varchar(50) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `unit` varchar(100) NOT NULL default '',
  `style` varchar(255) NOT NULL default '',
  `template` text NOT NULL,
  `note` mediumtext NOT NULL,
  `type` varchar(20) NOT NULL default '',
  `listorder` smallint(5) NOT NULL default '0',
  `allownull` tinyint(1) unsigned NOT NULL default '1',
  `enablesearch` tinyint(1) unsigned NOT NULL default '0',
  `iscore` tinyint(1) NOT NULL default '0',
  `isadminfield` varchar(1) NOT NULL default '0',
  `show_list` tinyint(1) unsigned NOT NULL default '0',
  `show_detail` tinyint(1) unsigned NOT NULL default '1',
  `show_side` tinyint(1) unsigned NOT NULL default '0' COMMENT '侧边栏显示',
  `regular` varchar(255) NOT NULL default '',
  `errmsg` varchar(255) NOT NULL default '',
  `datatype` varchar(60) NOT NULL default '',
  `config` text NOT NULL,
  `disable` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fieldid`),
  KEY `tablename` (`tablename`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 AUTO_INCREMENT=25;

INSERT INTO modoer_subjectfield VALUES ('1','1','subject','aid','地区','','','','','area','6','1','1','2','0','0','0','0','/[0-9]+/','未选择地区','varchar(6)','a:1:{s:7:\"default\";s:1:\"0\";}','0');
INSERT INTO modoer_subjectfield VALUES ('2','1','subject','catid','分类','','','','','category','1','0','1','2','0','0','1','0','/[0-9]+/','未选择分类','smallint(5)','a:1:{s:7:\"default\";s:1:\"0\";}','0');
INSERT INTO modoer_subjectfield VALUES ('3','1','subject','name','名称','','','','','text','2','0','1','1','0','1','1','1','','','VARCHAR(255)','a:3:{s:3:\"len\";i:80;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');
INSERT INTO modoer_subjectfield VALUES ('4','1','subject','subname','子名称','','','','','text','3','1','1','1','0','0','0','0','','','VARCHAR(255)','a:3:{s:3:\"len\";i:80;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');
INSERT INTO modoer_subjectfield VALUES ('5','1','subject','mappoint','地图坐标','','','','','mappoint','4','1','0','1','0','0','0','0','','','varchar(60)','a:2:{s:7:\"default\";s:0:\"\";s:4:\"size\";s:2:\"30\";}','0');
INSERT INTO modoer_subjectfield VALUES ('6','1','subject','video','视频地址','','','','','video','5','1','0','1','0','1','1','0','','','varchar(255)','a:2:{s:7:\"default\";s:0:\"\";s:4:\"size\";s:2:\"30\";}','0');
INSERT INTO modoer_subjectfield VALUES ('7','1','subject','description','简介','','','','','text','7','1','0','1','0','1','1','1','','','VARCHAR(255)','a:3:{s:3:\"len\";i:255;s:7:\"default\";s:0:\"\";s:4:\"size\";i:60;}','0');
INSERT INTO modoer_subjectfield VALUES ('8','1','subject','level','等级','','','','','level','92','0','1','1','1','0','1','0','/[0-9]+/','未选择点评对象等级','tinyint(3)','a:1:{s:7:\"default\";i:0;}','0');
INSERT INTO modoer_subjectfield VALUES ('9','1','subject','finer','推荐度','','','','','numeric','91','1','0','1','1','0','0','0','','','SMALLINT(5)','a:4:{s:3:\"min\";i:0;s:3:\"max\";i:255;s:5:\"point\";s:1:\"0\";s:7:\"default\";s:1:\"0\";}','0');
INSERT INTO modoer_subjectfield VALUES ('10','1','subject_shops','tel','电话','','','','','text','8','1','1','1','0','1','1','1','','','VARCHAR(255)','a:3:{s:3:\"len\";i:80;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');
INSERT INTO modoer_subjectfield VALUES ('11','1','subject_shops','address','地址','','','','','text','9','1','1','1','0','1','1','1','','','VARCHAR(255)','a:3:{s:3:\"len\";i:80;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');
INSERT INTO modoer_subjectfield VALUES ('12','1','subject_shops','content','详细介绍','','','','','textarea','90','0','0','1','0','0','1','0','','','MEDIUMTEXT','a:6:{s:5:\"width\";s:3:\"99%\";s:6:\"height\";s:5:\"200px\";s:4:\"html\";s:1:\"2\";s:7:\"default\";s:0:\"\";s:11:\"charnum_sup\";i:0;s:11:\"charnum_sub\";i:1000;}','0');
INSERT INTO modoer_subjectfield VALUES ('13','2','subject','aid','地区','','','','','area','0','1','1','2','0','0','0','0','/[0-9]+/','未选择地区','MEDIUMINT(8) UNSIGNED','a:1:{s:7:\"default\";s:1:\"0\";}','0');
INSERT INTO modoer_subjectfield VALUES ('14','2','subject','catid','分类','','','','','category','1','1','1','2','0','0','0','0','/[0-9]+/','未选择分类','smallint(5)','a:1:{s:7:\"default\";s:1:\"0\";}','0');
INSERT INTO modoer_subjectfield VALUES ('15','2','subject','name','名称','','','','','text','2','0','1','1','0','0','1','0','','','VARCHAR(255)','a:3:{s:3:\"len\";i:80;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');
INSERT INTO modoer_subjectfield VALUES ('16','2','subject','subname','子名称','','','','','text','3','1','1','1','0','0','0','0','','','VARCHAR(255)','a:3:{s:3:\"len\";i:80;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');
INSERT INTO modoer_subjectfield VALUES ('17','2','subject','mappoint','地图坐标','','','','','mappoint','4','1','0','1','0','0','1','0','/[\\-\\.0-9a-z]+,[\\-\\.0-9a-z]+/i','地图坐标不正确','varchar(255)','a:2:{s:7:\"default\";s:0:\"\";s:4:\"size\";s:2:\"30\";}','0');
INSERT INTO modoer_subjectfield VALUES ('18','2','subject','video','视频地址','','','','','video','5','1','0','1','0','0','1','0','','','varchar(255)','a:2:{s:7:\"default\";s:0:\"\";s:4:\"size\";s:2:\"30\";}','0');
INSERT INTO modoer_subjectfield VALUES ('19','2','subject','description','简介','','','','','text','7','1','0','1','0','0','1','0','','','VARCHAR(255)','a:3:{s:3:\"len\";i:255;s:7:\"default\";s:0:\"\";s:4:\"size\";i:60;}','0');
INSERT INTO modoer_subjectfield VALUES ('20','2','subject','level','等级','','','','','level','92','0','1','1','1','0','1','0','/[0-9]+/','未选择点评对象等级','tinyint(3)','a:1:{s:7:\"default\";i:0;}','0');
INSERT INTO modoer_subjectfield VALUES ('21','2','subject','finer','推荐度','','','','','numeric','91','1','0','1','1','0','0','0','','','INT(10)','a:4:{s:3:\"min\";i:0;s:3:\"max\";i:255;s:5:\"point\";s:1:\"0\";s:7:\"default\";s:1:\"0\";}','0');
INSERT INTO modoer_subjectfield VALUES ('22','2','subject_package','content','详细介绍','','','','','textarea','90','0','0','1','0','0','1','0','','','MEDIUMTEXT','a:6:{s:5:\"width\";s:3:\"99%\";s:6:\"height\";s:5:\"200px\";s:4:\"html\";s:1:\"2\";s:7:\"default\";s:0:\"\";s:11:\"charnum_sup\";i:0;s:11:\"charnum_sub\";i:1000;}','0');
INSERT INTO modoer_subjectfield VALUES ('23','2','subject_package','tel','电话','','','','','text','2','0','1','0','0','0','1','0','','','VARCHAR(255)','a:3:{s:3:\"len\";i:255;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');
INSERT INTO modoer_subjectfield VALUES ('24','2','subject_package','address','地址','','','','','text','2','0','1','0','0','0','1','0','','','VARCHAR(255)','a:3:{s:3:\"len\";i:255;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');

DROP TABLE IF EXISTS modoer_subjectgourd;
CREATE TABLE `modoer_subjectgourd` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `status` enum('grow','harvest','finish') NOT NULL default 'grow',
  `progress` tinyint(3) unsigned NOT NULL default '0',
  `total` smallint(5) unsigned NOT NULL default '0',
  `num` smallint(5) unsigned NOT NULL default '0',
  `createtime` int(10) unsigned NOT NULL default '0',
  `harvesttime` int(10) unsigned NOT NULL default '0',
  `finishtime` int(10) unsigned NOT NULL default '0',
  `users` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `sid` (`sid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_subjectimpress;
CREATE TABLE `modoer_subjectimpress` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `sid` mediumint(8) unsigned NOT NULL,
  `title` varchar(20) NOT NULL,
  `total` int(8) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `sid` (`sid`,`total`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_subjectimpress VALUES ('1','1','细致','1','1433747556');

DROP TABLE IF EXISTS modoer_subjectlink;
CREATE TABLE `modoer_subjectlink` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `flagid` mediumint(8) unsigned NOT NULL default '0',
  `flag` varchar(30) NOT NULL default '',
  `sid` int(10) unsigned NOT NULL default '0',
  `modelid` smallint(5) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `flagid` (`flagid`,`flag`),
  KEY `sid` (`sid`,`flag`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_subjectlog;
CREATE TABLE `modoer_subjectlog` (
  `upid` mediumint(8) unsigned NOT NULL auto_increment,
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `username` varchar(16) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `ismappoint` tinyint(1) unsigned NOT NULL default '0',
  `upcontent` mediumtext NOT NULL,
  `disposal` tinyint(1) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `upremark` mediumtext NOT NULL,
  `disposaltime` int(10) NOT NULL default '0',
  `update_point` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`upid`),
  KEY `sid` (`sid`),
  KEY `disposal` (`disposal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_subjectrelated;
CREATE TABLE `modoer_subjectrelated` (
  `related_id` mediumint(8) unsigned NOT NULL auto_increment,
  `fieldid` smallint(5) unsigned NOT NULL default '0',
  `modelid` smallint(5) unsigned NOT NULL default '0',
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `r_sid` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`related_id`),
  KEY `fieldid` (`fieldid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_subjectsetting;
CREATE TABLE `modoer_subjectsetting` (
  `sid` mediumint(8) NOT NULL default '0',
  `variable` char(20) NOT NULL default '',
  `value` text NOT NULL,
  UNIQUE KEY `sid` (`sid`,`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS modoer_subjectstyle;
CREATE TABLE `modoer_subjectstyle` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `templateid` smallint(5) NOT NULL default '0',
  `buytime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_subjecttaoke;
CREATE TABLE `modoer_subjecttaoke` (
  `user_id` int(10) unsigned NOT NULL,
  `sid` mediumint(8) unsigned NOT NULL default '0',
  `nick` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`user_id`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS modoer_tag_data;
CREATE TABLE `modoer_tag_data` (
  `stid` mediumint(8) unsigned NOT NULL auto_increment,
  `tagid` int(10) unsigned NOT NULL default '0',
  `tgid` smallint(5) unsigned NOT NULL default '0',
  `id` mediumint(8) unsigned NOT NULL default '0',
  `tagname` varchar(25) NOT NULL default '',
  `total` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`stid`),
  KEY `tagid` (`tagid`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_taggroup;
CREATE TABLE `modoer_taggroup` (
  `tgid` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `des` varchar(200) NOT NULL default '',
  `sort` tinyint(1) unsigned NOT NULL default '0',
  `options` text NOT NULL,
  PRIMARY KEY  (`tgid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_taggroup VALUES ('1','点评标签','商铺标签说明','1','');

DROP TABLE IF EXISTS modoer_tags;
CREATE TABLE `modoer_tags` (
  `tagid` mediumint(8) unsigned NOT NULL auto_increment,
  `city_id` smallint(5) unsigned NOT NULL default '0',
  `tagname` varchar(20) NOT NULL default '',
  `closed` tinyint(1) NOT NULL default '0',
  `total` int(10) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tagid`),
  KEY `total` (`total`),
  KEY `closed` (`closed`),
  KEY `tagname` (`tagname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_task;
CREATE TABLE `modoer_task` (
  `taskid` mediumint(8) unsigned NOT NULL auto_increment,
  `enable` tinyint(1) unsigned NOT NULL default '1',
  `taskflag` varchar(30) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `des` text NOT NULL,
  `icon` varchar(30) NOT NULL default '',
  `starttime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `period` smallint(5) unsigned NOT NULL default '0',
  `period_unit` tinyint(1) unsigned NOT NULL default '0',
  `pointtype` varchar(30) NOT NULL default '',
  `point` int(10) unsigned NOT NULL default '0',
  `access` tinyint(1) unsigned NOT NULL default '0',
  `access_groupids` varchar(255) NOT NULL default '',
  `applys` int(10) unsigned NOT NULL default '0',
  `completes` int(10) unsigned NOT NULL default '0',
  `listorder` smallint(5) unsigned NOT NULL default '0',
  `reg_automatic` tinyint(1) unsigned NOT NULL default '0',
  `config` text NOT NULL,
  PRIMARY KEY  (`taskid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;

INSERT INTO modoer_task VALUES ('1','0','member:avatar','上传头像','注册用户上传一个自己的头像，即可获得积分奖励，赶快来参与吧！','','1315075860','0','0','0','point1','10','0','','0','0','0','0','');
INSERT INTO modoer_task VALUES ('2','0','item:favorite','关注主题','浏览主题，关注自己喜欢和关注的主题，从申请任务起，累计关注 3 个主题，即可获得积分奖励。','','1315075920','0','0','0','point1','6','0','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"3\";s:10:\"time_limit\";s:0:\"\";}');
INSERT INTO modoer_task VALUES ('3','0','review:flower','赠送鲜花','给你认为非常棒的点评信息赠送 3 朵鲜花，就可以获得积分奖励，本任务每周都可以重复申请一次。','','1315075980','0','1','2','point1','5','0','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"3\";s:10:\"time_limit\";s:0:\"\";}');
INSERT INTO modoer_task VALUES ('4','0','review:post','添加主题点评','申请本任务后，选择一个主题，发表自己对这些主题的点评信息，发表 1 篇，即可获得积分奖励，可重复申请。','','1315076040','0','1','1','point1','5','1','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"1\";s:10:\"time_limit\";s:0:\"\";}');

DROP TABLE IF EXISTS modoer_tasktype;
CREATE TABLE `modoer_tasktype` (
  `ttid` smallint(5) unsigned NOT NULL auto_increment,
  `flag` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `copyright` varchar(255) NOT NULL default '',
  `version` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`ttid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 AUTO_INCREMENT=8;

INSERT INTO modoer_tasktype VALUES ('1','review:post','点评类任务','MouferStudio','1.0');
INSERT INTO modoer_tasktype VALUES ('2','review:flower','鲜花类任务','MouferStudio','1.0');
INSERT INTO modoer_tasktype VALUES ('3','member:avatar','头像类任务','MouferStudio','1.0');
INSERT INTO modoer_tasktype VALUES ('4','item:subject','主题类任务','MouferStudio','1.0');
INSERT INTO modoer_tasktype VALUES ('5','item:picture','图片类任务','MouferStudio','1.0');
INSERT INTO modoer_tasktype VALUES ('6','item:favorite','关注类任务','MouferStudio','1.0');
INSERT INTO modoer_tasktype VALUES ('7','article:post','文章类任务','MouferStudio','1.0');

DROP TABLE IF EXISTS modoer_templates;
CREATE TABLE `modoer_templates` (
  `templateid` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `directory` varchar(100) NOT NULL default '',
  `copyright` varchar(45) NOT NULL default '',
  `tpltype` varchar(15) NOT NULL default '',
  `bind` tinyint(1) NOT NULL default '0',
  `price` int(10) NOT NULL default '0',
  `pointtype` enum('point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL default 'point1',
  PRIMARY KEY  (`templateid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

INSERT INTO modoer_templates VALUES ('1','默认模板','default','Moufer Studio','main','1','0','point1');
INSERT INTO modoer_templates VALUES ('2','商铺风格1','store_1','','item','0','10','point1');

DROP TABLE IF EXISTS modoer_travelers;
CREATE TABLE `modoer_travelers` (
  `tid` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `tuid` mediumint(8) unsigned NOT NULL default '0',
  `tusername` varchar(16) NOT NULL default '',
  `addtime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`tid`),
  KEY `uid` (`uid`,`addtime`),
  KEY `tuid` (`tuid`,`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_usergroups;
CREATE TABLE `modoer_usergroups` (
  `groupid` smallint(6) NOT NULL auto_increment,
  `grouptype` enum('member','special','system') default 'member',
  `groupname` char(16) default '',
  `point` int(10) NOT NULL default '0',
  `color` varchar(7) NOT NULL default '',
  `price` mediumint(8) unsigned NOT NULL default '0',
  `access` text NOT NULL,
  PRIMARY KEY  (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 AUTO_INCREMENT=16;

INSERT INTO modoer_usergroups VALUES ('1','system','游客','0','#808080','0','a:20:{s:16:\"member_forbidden\";s:1:\"0\";s:14:\"tuan_post_wish\";s:1:\"0\";s:23:\"item_allow_edit_subject\";s:1:\"0\";s:17:\"item_create_album\";s:1:\"0\";s:13:\"item_subjects\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:12:\"article_post\";s:1:\"0\";s:14:\"article_delete\";s:1:\"0\";s:12:\"coupon_print\";s:1:\"0\";s:16:\"exchange_disable\";s:1:\"0\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"1\";s:10:\"card_apply\";s:1:\"0\";s:8:\"ask_post\";s:1:\"0\";s:10:\"ask_delete\";s:1:\"0\";s:10:\"ask_editor\";s:1:\"0\";}');
INSERT INTO modoer_usergroups VALUES ('2','system','禁止访问','0','#808080','0','a:5:{s:16:\"member_forbidden\";s:1:\"1\";s:13:\"item_subjects\";s:2:\"-1\";s:12:\"item_reviews\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:15:\"comment_disable\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('3','system','禁止发言','0','#808080','0','a:5:{s:16:\"member_forbidden\";s:1:\"0\";s:13:\"item_subjects\";s:2:\"-1\";s:12:\"item_reviews\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:15:\"comment_disable\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('4','system','等待验证','0','#0bbfb9','0','a:5:{s:16:\"member_forbidden\";s:1:\"0\";s:13:\"item_subjects\";s:2:\"-1\";s:12:\"item_reviews\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:15:\"comment_disable\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('10','member','注册会员','0','','0','a:19:{s:16:\"member_forbidden\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:2:\"10\";s:17:\"item_create_album\";s:1:\"1\";s:14:\"tuan_post_wish\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('12','member','青铜会员','100','','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:1:\"0\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('13','member','白银会员','500','','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:2:\"30\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"0\";}');
INSERT INTO modoer_usergroups VALUES ('14','member','黄金会员','1000','','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:0:\"\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('15','special','VIP会员','0','#FF0000','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:3:\"150\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');

DROP TABLE IF EXISTS modoer_visitor;
CREATE TABLE `modoer_visitor` (
  `vid` mediumint(8) unsigned NOT NULL auto_increment,
  `sid` mediumint(8) NOT NULL default '0',
  `uid` mediumint(8) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `dateline` int(10) NOT NULL default '0',
  `total` int(10) NOT NULL default '0',
  PRIMARY KEY  (`vid`),
  KEY `sid` (`sid`,`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_visitor VALUES ('1','1','1','fex','1433747096','1');

DROP TABLE IF EXISTS modoer_weixin_converse;
CREATE TABLE `modoer_weixin_converse` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `hash` char(32) NOT NULL default '',
  `openid` char(100) NOT NULL default '',
  `uid` mediumint(8) unsigned NOT NULL default '0',
  `last_time` int(10) unsigned NOT NULL default '0',
  `last_cmd` char(60) NOT NULL default '',
  `city_id` mediumint(8) unsigned NOT NULL default '0',
  `data` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_words;
CREATE TABLE `modoer_words` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `keyword` varchar(255) NOT NULL default '',
  `expression` varchar(255) NOT NULL default '',
  `admin` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS modoer_admin;
CREATE TABLE `modoer_admin` (
  `adminid` mediumint(8) unsigned NOT NULL auto_increment,
  `adminname` varchar(24) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `admintype` tinyint(3) NOT NULL default '0',
  `is_founder` char(1) NOT NULL default 'N',
  `logintime` int(10) NOT NULL default '0',
  `loginip` varchar(20) NOT NULL default '',
  `logincount` int(10) unsigned NOT NULL default '0',
  `mycitys` text NOT NULL,
  `mymodules` text NOT NULL,
  `closed` tinyint(1) NOT NULL default '0',
  `validtime` int(10) NOT NULL default '0',
  `private_key` char(10) NOT NULL default '',
  PRIMARY KEY  (`adminid`),
  UNIQUE KEY `adminname` (`adminname`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT INTO modoer_admin VALUES ('1','admin','ed5cb196d56209d4f81a0702fe924eb0','admin@admin.com','1','Y','1433988226','60.177.221.250','27','','','0','0','po947h9x');

DROP TABLE IF EXISTS modoer_admin_session;
CREATE TABLE `modoer_admin_session` (
  `adminid` mediumint(8) NOT NULL,
  `ipaddress` varchar(16) NOT NULL default '',
  `adminhash` varchar(255) NOT NULL default '',
  `lasttime` int(10) NOT NULL default '0',
  `errno` tinyint(1) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO modoer_admin_session VALUES ('1','115.205.211.141','16940e1a46ff2ffd03a2892150d59c53','1433988494','-1');
INSERT INTO modoer_admin_session VALUES ('1','60.177.221.250','16940e1a46ff2ffd03a2892150d59c53','1433988440','-1');

