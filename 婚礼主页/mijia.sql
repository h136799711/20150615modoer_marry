-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 06 月 07 日 03:28
-- 服务器版本: 5.1.50
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `mijia`
--

-- --------------------------------------------------------

--
-- 表的结构 `modoer_activity`
--

CREATE TABLE IF NOT EXISTS `modoer_activity` (
  `aid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(16) NOT NULL DEFAULT '',
  `reviews` smallint(5) unsigned NOT NULL DEFAULT '0',
  `subjects` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `reviews` (`reviews`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_activity`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_admin`
--

CREATE TABLE IF NOT EXISTS `modoer_admin` (
  `adminid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `adminname` varchar(24) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `admintype` tinyint(3) NOT NULL DEFAULT '0',
  `is_founder` char(1) NOT NULL DEFAULT 'N',
  `logintime` int(10) NOT NULL DEFAULT '0',
  `loginip` varchar(20) NOT NULL DEFAULT '',
  `logincount` int(10) unsigned NOT NULL DEFAULT '0',
  `mycitys` text NOT NULL,
  `mymodules` text NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `validtime` int(10) NOT NULL DEFAULT '0',
  `private_key` char(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`adminid`),
  UNIQUE KEY `adminname` (`adminname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `modoer_admin`
--

INSERT INTO `modoer_admin` (`adminid`, `adminname`, `password`, `email`, `admintype`, `is_founder`, `logintime`, `loginip`, `logincount`, `mycitys`, `mymodules`, `closed`, `validtime`, `private_key`) VALUES
(1, 'admin', 'ed5cb196d56209d4f81a0702fe924eb0', 'admin@admin.com', 1, 'Y', 1433646186, '127.0.0.1', 11, '', '', 0, 0, 'po947h9x');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_admin_session`
--

CREATE TABLE IF NOT EXISTS `modoer_admin_session` (
  `adminid` mediumint(8) NOT NULL,
  `ipaddress` varchar(16) NOT NULL DEFAULT '',
  `adminhash` varchar(255) NOT NULL DEFAULT '',
  `lasttime` int(10) NOT NULL DEFAULT '0',
  `errno` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_admin_session`
--

INSERT INTO `modoer_admin_session` (`adminid`, `ipaddress`, `adminhash`, `lasttime`, `errno`) VALUES
(1, '127.0.0.1', '16940e1a46ff2ffd03a2892150d59c53', 1433647394, -1);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_adv_list`
--

CREATE TABLE IF NOT EXISTS `modoer_adv_list` (
  `adid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `apid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `city_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `adname` varchar(60) NOT NULL DEFAULT '',
  `sort` enum('word','flash','code','img') NOT NULL,
  `begintime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  `code` text NOT NULL,
  `attr` char(10) NOT NULL DEFAULT '',
  `ader` varchar(255) NOT NULL DEFAULT '',
  `adtel` varchar(255) NOT NULL DEFAULT '',
  `ademail` varchar(255) NOT NULL DEFAULT '',
  `enabled` char(1) NOT NULL DEFAULT 'Y',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`adid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `modoer_adv_list`
--

INSERT INTO `modoer_adv_list` (`adid`, `apid`, `city_id`, `adname`, `sort`, `begintime`, `endtime`, `config`, `code`, `attr`, `ader`, `adtel`, `ademail`, `enabled`, `listorder`) VALUES
(1, 1, 0, 'Modoer2.0发布', 'img', 1289260800, 0, 'a:5:{s:9:"img_title";s:7:"Modoer2";s:7:"img_src";s:38:"/uploads/adv/2010-12/13_1292772219.jpg";s:9:"img_width";s:3:"708";s:10:"img_height";s:2:"75";s:8:"img_href";s:22:"http://www.modoer.com/";}', '<a href="http://www.modoer.com/" alt="Modoer2" target="_blank"><img src="/uploads/adv/2010-12/13_1292772219.jpg" width="708" height="75" /></a>', '', '', '', '', 'Y', 0),
(2, 2, 0, 'Modoer2.0发布', 'img', 1289260800, 0, 'a:5:{s:9:"img_title";s:7:"Modoer2";s:7:"img_src";s:38:"/uploads/adv/2010-12/29_1292772237.jpg";s:9:"img_width";s:3:"958";s:10:"img_height";s:2:"90";s:8:"img_href";s:22:"http://www.modoer.com/";}', '<a href="http://www.modoer.com/" alt="Modoer2" target="_blank"><img src="/uploads/adv/2010-12/29_1292772237.jpg" width="958" height="90" /></a>', '', '', '', '', 'Y', 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_adv_place`
--

CREATE TABLE IF NOT EXISTS `modoer_adv_place` (
  `apid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `templateid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  `des` varchar(255) NOT NULL DEFAULT '',
  `template` text NOT NULL,
  `enabled` char(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`apid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `modoer_adv_place`
--

INSERT INTO `modoer_adv_place` (`apid`, `templateid`, `name`, `des`, `template`, `enabled`) VALUES
(1, 0, '首页_中部广告', '首页推荐主题下方广告位', '<div class="ix_foo">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>$ad[code]</div>\r\n{/get}\r\n</div>', 'Y'),
(2, 0, '新闻首页_广告', '新闻模块的首页中午长条图片广告', '<div class="art_ix">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>$ad[code]</div>\r\n{/get}\r\n</div>', 'Y'),
(3, 0, '主题内容页_点评间广告', '在主题内容页坐下点评列表第二行加入的广告', '<div style="padding-bottom:10px;border-bottom:1px dashed #ddd;margin-bottom:10px;">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div style="text-align:center;">$ad[code]</div>\r\n{getempty(ad)}\r\n<center>广告位招租</center>\r\n{/get}\r\n</div>', 'Y'),
(4, 0, '主题列表页_列表间广告', '在主题模块的列表页面，列表第二层加入一个广告', '<div style="padding-bottom:5px;border-bottom:1px dashed #ddd;margin:5px 0;">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div style="text-align:center;">$ad[code]</div>\r\n{getempty(ad)}\r\n<center>广告位招租</center>\r\n{/get}\r\n</div>', 'Y');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_album`
--

CREATE TABLE IF NOT EXISTS `modoer_album` (
  `albumid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `des` text NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `num` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `pageview` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`albumid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_album`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_announcements`
--

CREATE TABLE IF NOT EXISTS `modoer_announcements` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `orders` smallint(5) NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `author` varchar(50) NOT NULL DEFAULT '',
  `pageview` int(10) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `available` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_announcements`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_area`
--

CREATE TABLE IF NOT EXISTS `modoer_area` (
  `aid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `attid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `domain` varchar(20) NOT NULL DEFAULT '',
  `initial` char(1) NOT NULL DEFAULT '',
  `name` varchar(16) NOT NULL DEFAULT '',
  `mappoint` varchar(50) NOT NULL DEFAULT '',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `templateid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `config` text NOT NULL,
  PRIMARY KEY (`aid`),
  KEY `pid` (`pid`),
  KEY `level` (`level`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `modoer_area`
--

INSERT INTO `modoer_area` (`aid`, `pid`, `attid`, `domain`, `initial`, `name`, `mappoint`, `level`, `listorder`, `templateid`, `enabled`, `config`) VALUES
(1, 0, 4, 'nb', 'N', '宁波', '121.565151,29.877309', 1, 0, 0, 1, ''),
(2, 1, 5, '', '', '江东区', '', 2, 0, 0, 1, ''),
(5, 2, 8, '', '', '天伦广场', '', 3, 0, 0, 1, ''),
(3, 1, 6, '', '', '海曙区', '', 2, 0, 0, 1, ''),
(6, 3, 9, '', '', '东门口', '', 3, 0, 0, 1, ''),
(4, 1, 7, '', '', '江北区', '', 2, 0, 0, 1, ''),
(7, 4, 10, '', '', '老外滩', '', 3, 0, 0, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_articles`
--

CREATE TABLE IF NOT EXISTS `modoer_articles` (
  `articleid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` varchar(255) NOT NULL DEFAULT '',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `att` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `havepic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `author` varchar(20) NOT NULL DEFAULT '',
  `subject` varchar(60) NOT NULL DEFAULT '',
  `keywords` varchar(100) NOT NULL DEFAULT '',
  `pageview` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `grade` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `digg` mediumint(8) NOT NULL DEFAULT '0',
  `closed_comment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `copyfrom` varchar(200) NOT NULL DEFAULT '',
  `introduce` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `checker` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`articleid`),
  KEY `sid` (`sid`),
  KEY `uid` (`uid`),
  KEY `city_id` (`city_id`,`catid`),
  KEY `att` (`att`,`status`,`listorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `modoer_articles`
--

INSERT INTO `modoer_articles` (`articleid`, `city_id`, `catid`, `sid`, `dateline`, `att`, `listorder`, `havepic`, `uid`, `author`, `subject`, `keywords`, `pageview`, `grade`, `digg`, `closed_comment`, `comments`, `copyfrom`, `introduce`, `thumb`, `picture`, `status`, `checker`) VALUES
(1, 0, 2, '0', 1275267913, 1, 0, 0, 0, 'admin', 'Modoer 点评系统', '', 0, 0, 0, 0, 0, '', 'Modoer 是一款以本地分享，多功能的点评网站管理系统。采用 PHP+MYSQL 开发设计，开放全部源代码。因具有非凡的访问速度和卓越的负载能力而深受国内外朋友的喜爱。', '', '', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_article_category`
--

CREATE TABLE IF NOT EXISTS `modoer_article_category` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pid` smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `total` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `modoer_article_category`
--

INSERT INTO `modoer_article_category` (`catid`, `pid`, `name`, `listorder`, `total`) VALUES
(1, 0, '默认分类', 0, 0),
(2, 1, '默认子分类', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_article_data`
--

CREATE TABLE IF NOT EXISTS `modoer_article_data` (
  `articleid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`articleid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_article_data`
--

INSERT INTO `modoer_article_data` (`articleid`, `content`) VALUES
(1, '\r\n            \r\n            \r\n        \r\n        <div class="content">\r\n            <h3>商铺功能</h3>\r\n            <ul><li>可建立多板块的点评，例如（餐饮，旅游，购物，娱乐，服务等）</li><li>每个板块可以分类，并按类别输出信息（如餐饮板块可以建立火锅，海鲜等，出行/旅游板块可以建立汽车，旅行社\r\n等）</li><li>商铺可以设置，商铺名称，分店名称，主营菜系，地址，电话，手机，店铺标签(Tag)，并可增加分店</li><li>商家可通过认领功能，来管理自己的点评</li><li>商铺自定义风格功能</li><li>会员可补充商铺信息</li><li>已有商铺可增加分店</li><li>商铺可以根据环境，产品或者其他补充图片集展示，图片支持缩略图，水印功能</li><li>可自定义设置商铺封面</li><li>所有会员的提交信息可自动提交和后台管理审核</li><li>自定义城市区域，可以精确到街道</li><li>地图标注和地图报错功能</li><li>商铺视频功能</li><li>会员去过，想去的互动</li></ul>\r\n            <h3>点评功能</h3>\r\n            <ul><li>商铺可以针对各个板块可以自定义点评项名称和评分项数量），喜欢程度，人均消费，消费感受，适合类型进行点评，\r\n会员并可推荐产品以及设置店铺Tag，其他会员可以对点评进行献花和回应，反馈，举报点评</li><li>会员并可推荐产品以及设置店铺 Tag</li><li>其他会员可以对点评进行赠送鲜花和回应，反馈</li><li>可举报点评</li></ul>\r\n            <h3>会员卡模块</h3>\r\n            <ul><li>可自定义设置会员卡名称</li><li>可设置会员卡在商铺的折扣或者优惠活动和备注说明</li><li>可设置推荐加盟商家</li></ul>\r\n            <h3>兑奖中心模块</h3>\r\n            <ul><li>会员可通过点评，登记，回应等一系列互动操作得到金币积分，利用这些积分可对话相应积分的奖品</li><li>后台可添加和设置奖品以及奖品说明</li></ul>\r\n            <h3>优惠券模块</h3>\r\n            <ul><li>会员可上传优惠券，可供其他会员下载打印优惠券</li><li>后台可设置优惠券审核</li><li>其他会员可投票是否优惠券是否有用</li></ul>\r\n            <h3>新闻咨询模块</h3>\r\n            <ul><li>发布新闻资讯</li><li>商家可发布店铺的咨询信息</li><li>其他会员可投票是否优惠券是否有用</li></ul>\r\n            <h3>排行榜功能</h3>\r\n            <ul><li>餐厅排行（最佳餐厅、口味最佳、环境最佳、服务最佳）</li><li>最新餐厅（近一周加入、近一月加入、近三月加入）</li></ul>\r\n            <h3>会员功能</h3>\r\n            <ul><li>会员短信功能</li><li>个人主页功能（可以设置、更改个人主页名称和风格）</li><li>好友设置功能</li><li>个人留言版功能</li><li>会员积分功能</li><li>会员鲜花功能</li><li>收藏夹功能</li><li>积分等级</li></ul>\r\n            <h3>模块功能</h3>\r\n            <ul><li>Modoer系统以模块为基础组成</li><li>可以Modoer为平台开发安装模块</li></ul>\r\n            <h3>高级数据调用</h3>\r\n            <ul><li>利用内置的函数和SQL调用方式调用数据</li><li>可设置每个调用的模板和空数据的模板</li><li>调用数据可设置缓存，较少系统数据库资源消耗</li><li>支持本地和JS方式调用数据</li><li>\r\n			<br /></li></ul>\r\n			<h3>插件功能</h3>\r\n            <ul><li>利用插件接口可丰富系统功能</li><li>集成提供多个插件（图片轮换，网站公告）</li></ul>\r\n			<h3>系统整合</h3>\r\n			<ul><li>万能整合API，可与任何PHP程序进行整合</li><li>整合UCenter（账户，短信，好友，积分兑换，Feed推送，个人空间跳转UCH）</li></ul>\r\n			<h3>其他功能</h3>\r\n			<ul><li>词语过滤可设置不同的过滤方式：阻止，替换，审核</li><li>菜单管理可自定义模板显示的菜单，不需要再修改模板</li><li>伪静态功能优化SEO</li></ul>\r\n        </div>');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_att_cat`
--

CREATE TABLE IF NOT EXISTS `modoer_att_cat` (
  `catid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `des` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_att_cat`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_att_list`
--

CREATE TABLE IF NOT EXISTS `modoer_att_list` (
  `attid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT '',
  `catid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`attid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `modoer_att_list`
--

INSERT INTO `modoer_att_list` (`attid`, `type`, `catid`, `name`, `listorder`, `icon`) VALUES
(1, 'category', 1, '美食', 0, ''),
(2, 'category', 2, '自助餐', 0, ''),
(3, 'category', 3, '海鲜', 0, ''),
(4, 'area', 1, '宁波', 0, ''),
(5, 'area', 2, '江东区', 0, ''),
(6, 'area', 3, '海曙区', 0, ''),
(7, 'area', 4, '江北区', 0, ''),
(8, 'area', 5, '天伦广场', 0, ''),
(9, 'area', 6, '东门口', 0, ''),
(10, 'area', 7, '老外滩', 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_bcastr`
--

CREATE TABLE IF NOT EXISTS `modoer_bcastr` (
  `bcastr_id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupname` varchar(15) NOT NULL DEFAULT 'index',
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `itemtitle` varchar(100) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `item_url` varchar(255) NOT NULL DEFAULT '',
  `orders` smallint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bcastr_id`),
  KEY `groupname` (`groupname`,`available`,`orders`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `modoer_bcastr`
--

INSERT INTO `modoer_bcastr` (`bcastr_id`, `city_id`, `groupname`, `available`, `itemtitle`, `link`, `item_url`, `orders`) VALUES
(1, 0, 'index', 1, 'Modoer点评系统', 'uploads/bcastr/25_1275267815.jpg', 'http://www.modoer.com', 1);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_card_apply`
--

CREATE TABLE IF NOT EXISTS `modoer_card_apply` (
  `applyid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `linkman` varchar(20) NOT NULL DEFAULT '',
  `tel` varchar(20) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `postcode` varchar(10) NOT NULL DEFAULT '',
  `num` smallint(5) unsigned NOT NULL DEFAULT '1',
  `coin` int(10) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `comment` text NOT NULL,
  `checker` varchar(30) NOT NULL DEFAULT '',
  `checktime` int(10) NOT NULL DEFAULT '0',
  `checkmsg` text NOT NULL,
  PRIMARY KEY (`applyid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_card_apply`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_card_discounts`
--

CREATE TABLE IF NOT EXISTS `modoer_card_discounts` (
  `sid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cardsort` enum('both','largess','discount') NOT NULL DEFAULT 'discount',
  `discount` decimal(4,1) NOT NULL DEFAULT '0.0',
  `largess` varchar(100) NOT NULL DEFAULT '',
  `exception` varchar(255) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `finer` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`),
  KEY `available` (`available`),
  KEY `finer` (`finer`,`available`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_card_discounts`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_category`
--

CREATE TABLE IF NOT EXISTS `modoer_category` (
  `catid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) NOT NULL DEFAULT '0',
  `review_opt_gid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `attid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `subcats` varchar(255) NOT NULL,
  `nonuse_subcats` varchar(255) NOT NULL,
  PRIMARY KEY (`catid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `modoer_category`
--

INSERT INTO `modoer_category` (`catid`, `pid`, `level`, `modelid`, `review_opt_gid`, `attid`, `name`, `total`, `config`, `listorder`, `enabled`, `subcats`, `nonuse_subcats`) VALUES
(1, 0, 1, 1, 1, 1, '美食', 0, 'a:35:{s:10:"enable_add";s:1:"1";s:11:"relate_root";s:1:"0";s:9:"gusetbook";s:1:"1";s:5:"forum";s:1:"0";s:13:"subject_apply";s:1:"1";s:19:"subject_apply_uppic";s:1:"0";s:24:"subject_apply_uppic_name";s:0:"";s:9:"useeffect";s:1:"1";s:7:"effect1";s:6:"去过";s:7:"effect2";s:6:"想去";s:13:"use_subbranch";s:1:"0";s:18:"allow_edit_subject";s:1:"0";s:21:"use_review_upload_pic";s:1:"1";s:8:"taggroup";a:1:{i:0;s:1:"1";}s:8:"useprice";s:1:"1";s:17:"useprice_required";s:1:"0";s:14:"useprice_title";s:12:"人均消费";s:13:"useprice_unit";s:7:"元/人";s:13:"repeat_review";s:1:"0";s:17:"repeat_review_num";s:1:"0";s:18:"repeat_review_time";s:1:"0";s:12:"guest_review";s:1:"0";s:9:"itemcheck";s:1:"0";s:11:"reviewcheck";s:1:"0";s:12:"picturecheck";s:1:"0";s:14:"guestbookcheck";s:1:"0";s:10:"templateid";s:1:"0";s:19:"detail_picture_hide";s:1:"0";s:19:"detail_content_hide";s:1:"0";s:11:"displaytype";s:6:"normal";s:9:"listorder";s:5:"finer";s:4:"icon";s:0:"";s:15:"product_modelid";s:1:"0";s:13:"meta_keywords";s:6:"美食";s:16:"meta_description";s:30:"modoer点评系统美食点评";}', 0, 1, '2,3', ''),
(2, 1, 2, 1, 0, 2, '自助餐', 0, '', 0, 1, '', ''),
(3, 1, 2, 1, 0, 3, '海鲜', 0, '', 0, 1, '', '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_comment`
--

CREATE TABLE IF NOT EXISTS `modoer_comment` (
  `cid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `idtype` varchar(30) NOT NULL DEFAULT '',
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `reply_cid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '回复评论的ID',
  `reply_user` varchar(255) NOT NULL DEFAULT '',
  `root_cid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '回复评论的根ID',
  `root_subtotal` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根总数（即root_cid=0）',
  `grade` tinyint(2) NOT NULL DEFAULT '0',
  `effect1` int(10) unsigned NOT NULL DEFAULT '0',
  `effect2` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `extra_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `idtype` (`idtype`,`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_config`
--

CREATE TABLE IF NOT EXISTS `modoer_config` (
  `variable` varchar(32) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `module` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`variable`,`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_config`
--

INSERT INTO `modoer_config` (`variable`, `value`, `module`) VALUES
('point', 'a:12:{s:11:"add_subject";a:7:{s:5:"point";i:15;s:6:"point1";i:15;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:10:"add_review";a:7:{s:5:"point";i:10;s:6:"point1";i:10;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:11:"add_picture";a:7:{s:5:"point";i:5;s:6:"point1";i:5;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:13:"add_guestbook";a:7:{s:5:"point";i:5;s:6:"point1";i:5;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:11:"add_respond";a:7:{s:5:"point";i:5;s:6:"point1";i:5;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:14:"update_subject";a:7:{s:5:"point";i:5;s:6:"point1";i:5;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:13:"report_review";a:7:{s:5:"point";i:5;s:6:"point1";i:5;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:11:"add_article";a:7:{s:5:"point";i:5;s:6:"point1";i:5;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:10:"add_coupon";a:7:{s:5:"point";i:5;s:6:"point1";i:5;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:12:"print_coupon";a:7:{s:5:"point";i:5;s:6:"point1";i:5;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:11:"add_comment";a:7:{s:5:"point";i:2;s:6:"point1";i:2;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}s:3:"reg";a:7:{s:5:"point";i:20;s:6:"point1";i:20;s:6:"point2";i:0;s:6:"point3";i:0;s:6:"point4";i:0;s:6:"point5";i:0;s:6:"point6";i:0;}}', 'member'),
('point_group', 'a:6:{s:6:"point1";a:6:{s:7:"enabled";s:1:"1";s:4:"name";s:6:"金币";s:4:"unit";s:3:"个";s:2:"in";s:1:"1";s:3:"out";s:1:"1";s:4:"rate";s:0:"";}s:6:"point2";a:3:{s:4:"name";s:0:"";s:4:"unit";s:0:"";s:4:"rate";s:0:"";}s:6:"point3";a:3:{s:4:"name";s:0:"";s:4:"unit";s:0:"";s:4:"rate";s:0:"";}s:6:"point4";a:3:{s:4:"name";s:0:"";s:4:"unit";s:0:"";s:4:"rate";s:0:"";}s:6:"point5";a:3:{s:4:"name";s:0:"";s:4:"unit";s:0:"";s:4:"rate";s:0:"";}s:6:"point6";a:3:{s:4:"name";s:0:"";s:4:"unit";s:0:"";s:4:"rate";s:0:"";}}', 'member'),
('siteclose', '0', 'modoer'),
('icpno', '', 'modoer'),
('sitename', 'Modoer点评系统', 'modoer'),
('seccode', '0', 'modoer'),
('useripaccess', '', 'modoer'),
('adminipaccess', '', 'modoer'),
('ban_ip', '', 'modoer'),
('gzipcompress', '0', 'modoer'),
('scriptinfo', '1', 'modoer'),
('picture_upload_size', '2000', 'modoer'),
('watermark', '1', 'modoer'),
('jstransfer', '1', 'modoer'),
('jsaccess', '', 'modoer'),
('googlesearch', '0', 'modoer'),
('googlesearch_website', 'modoer.com', 'modoer'),
('tplext', '.htm', 'modoer'),
('mapapi', 'http://api.map.baidu.com/api?v=2.0&ak=yourkey', 'modoer'),
('datacall_dir', './data/datacall', 'modoer'),
('datacall_clearinterval', '1', 'modoer'),
('datacall_cleartime', '1', 'modoer'),
('search_limit', '60', 'modoer'),
('search_maxspm', '20', 'modoer'),
('search_maxresults', '500', 'modoer'),
('search_cachelife', '3600', 'modoer'),
('rewrite', '0', 'modoer'),
('rewritecompatible', '1', 'modoer'),
('subname', '多功能点评系统', 'modoer'),
('titlesplit', ',', 'modoer'),
('meta_keywords', 'Meta Keywords', 'modoer'),
('meta_description', 'Meta Description', 'modoer'),
('headhtml', '', 'modoer'),
('templateid', '0', 'member'),
('editor_relativeurl', '1', 'modoer'),
('page_cachetime', '0', 'modoer'),
('console_menuid', '3', 'modoer'),
('closereg', '0', 'member'),
('censoruser', '*admin*\r\n*管理员*', 'member'),
('existsemailreg', '0', 'member'),
('salutatory', '1', 'member'),
('salutatory_msg', '尊敬的$username：\r\n\r\n欢迎您加入$sitename大家庭！\r\n祝你在$sitename过得愉快！\r\n\r\n$sitename运营团队\r\n$time', 'member'),
('showregrule', '1', 'member'),
('regrule', '这里填写新用户的注册协议！', 'member'),
('pic_width', '200', 'item'),
('pic_height', '150', 'item'),
('video_width', '250', 'item'),
('video_height', '200', 'item'),
('review_min', '10', 'review'),
('review_max', '1500', 'review'),
('respond_min', '10', 'review'),
('respond_max', '500', 'review'),
('avatar_review', '0', 'review'),
('pcatid', '9', 'item'),
('list_num', '20', 'item'),
('review_num', '5', 'review'),
('respond_num', '5', 'review'),
('classorder', 'order', 'item'),
('thumb', '2', 'item'),
('show_thumb', '1', 'item'),
('show_thumb_sort', 'small', 'item'),
('mapapi_charset', '', 'modoer'),
('main_menuid', '1', 'modoer'),
('respondcheck', '0', 'item'),
('pid', '1', 'item'),
('closenote', '正在升级，请稍后访问...', 'modoer'),
('gbook', '1', 'space'),
('gbook_guest', '1', 'space'),
('gbook_seccode', '1', 'space'),
('templateid', '0', 'space'),
('recordguest', '1', 'space'),
('spacename', '{username}的个人空间', 'space'),
('spacedescribe', '读万卷书模，行万里路！', 'space'),
('index_reviews', '5', 'space'),
('index_gbooks', '5', 'space'),
('reviews', '10', 'space'),
('gbooks', '10', 'space'),
('seccode_review', '0', 'review'),
('seccode_picupload', '1', 'item'),
('seccode_guestbook', '0', 'item'),
('seccode_respond', '1', 'review'),
('templateid', '1', 'modoer'),
('foot_menuid', '66', 'modoer'),
('scoretype', '10', 'review'),
('decimalpoint', '2', 'review'),
('seccode_review_guest', '1', 'review'),
('seccode_subject', '0', 'item'),
('tag_split_sp', '1', 'item'),
('menuid', '80', 'space'),
('space_menuid', '80', 'space'),
('multi_upload_pic', '1', 'item'),
('multi_upload_pic_num', '10', 'item'),
('console_seccode', '0', 'modoer'),
('console_total', '1', 'modoer'),
('guest_post', '0', 'comment'),
('member_seccode', '0', 'comment'),
('guest_seccode', '0', 'comment'),
('disable_comment', '0', 'comment'),
('guest_comment', '0', 'comment'),
('check_comment', '0', 'comment'),
('filter_word', '1', 'comment'),
('list_num', '5', 'comment'),
('hidden_comment', '0', 'comment'),
('comment_interval', '5', 'comment'),
('mapflag', 'baidu', 'modoer'),
('seccode_reg', '0', 'member'),
('seccode_login', '0', 'member'),
('mail_debug', '0', 'modoer'),
('ownernews', '1', 'exchange'),
('ownernews_classid', '1', 'exchange'),
('ownernews_check', '0', 'exchange'),
('thumb_w', '160', 'exchange'),
('thumb_h', '100', 'exchange'),
('exchange_seccode', '1', 'exchange'),
('keywords', '礼品兑换,兑奖中心', 'exchange'),
('description', '礼品兑换模块用户会员使用金币兑换网站提供的礼品', 'exchange'),
('picture_createthumb_level', '80', 'modoer'),
('keywords', '新闻模块', 'article'),
('description', '文章信息，发布网站信息和主题资讯', 'article'),
('editor_image', '1', 'article'),
('rss', '1', 'article'),
('owner_post', '1', 'article'),
('member_post', '0', 'article'),
('post_check', '1', 'article'),
('post_filter', '1', 'article'),
('list_num', '20', 'article'),
('owner_category', '0', 'article'),
('member_category', '0', 'article'),
('post_seccode', '0', 'article'),
('member_bysubject', '0', 'article'),
('meta_keywords', '新闻模块', 'article'),
('meta_description', '文章信息，发布网站信息和主题资讯', 'article'),
('post_comment', '1', 'article'),
('att_custom', '1|头条(默认显示2条)\r\n2|文字推荐(默认显示7条)\r\n3|图片推荐(默认显示3条)\r\n4|模块首页图片轮换(不宜过多)', 'article'),
('meta_keywords', '兑奖中心', 'exchange'),
('meta_description', '兑奖中心模块，用于消费金币', 'exchange'),
('map_view_level', '15', 'modoer'),
('guestbook_min', '10', 'item'),
('guestbook_max', '50', 'item'),
('content_min', '10', 'comment'),
('content_max', '200', 'comment'),
('meta_keywords', '友情链接', 'link'),
('meta_description', 'Modoer点评系统的友情链接模块！', 'link'),
('num_logo', '5', 'link'),
('num_char', '20', 'link'),
('open_apply', '1', 'link'),
('apply', '1', 'card'),
('applyseccode', '1', 'card'),
('coin', '10', 'card'),
('applynum', '2', 'card'),
('applydes', '这里填写申请提交时，显示给会员看的申请说明和条例。', 'card'),
('subtitle', '最优惠的消费折扣卡', 'card'),
('meta_keywords', '会员卡', 'card'),
('meta_description', 'modoer点评系统会员卡模块', 'card'),
('check', '1', 'coupon'),
('post_item_owner', '1', 'coupon'),
('watermark', '1', 'coupon'),
('thumb_width', '160', 'coupon'),
('thumb_height', '100', 'coupon'),
('seccode', '1', 'coupon'),
('listnum', '10', 'coupon'),
('des', '这是是优惠券发布的保证说明！', 'coupon'),
('subtitle', '最优优惠', 'coupon'),
('meta_keywords', '优惠券模块', 'coupon'),
('meta_description', 'Modoer点评系统之优惠券模块', 'coupon'),
('post_comment', '1', 'coupon'),
('picture_createthumb_mod', '0', 'modoer'),
('watermark_postion', '0', 'modoer'),
('picture_ext', 'jpg jpeg png gif', 'modoer'),
('select_city', '1', 'article'),
('copyright', '&#169; 2007 - 2011 <a href="http://www.modoer.com" target="_blank">陌风软件</a> 版权所有', 'modoer'),
('buildinfo', '1', 'modoer'),
('statement', '免责声明：站内会员言论仅代表个人观点，并不代表本站同意其观点，本站不承担由此引起的法律责任。', 'modoer'),
('feed_enable', '1', 'member'),
('watermark_text', 'Modoer点评系统', 'modoer'),
('city_id', '1', 'modoer'),
('picture_max_width', '800', 'modoer'),
('picture_max_height', '600', 'modoer'),
('city_ip_location', '0', 'modoer'),
('index_digst_rand_num', '2', 'review'),
('index_pk_rand_num', '1', 'review'),
('index_show_bad_review', '1', 'review'),
('index_review_num', '5', 'review'),
('index_review_gettype', 'rand', 'review'),
('content_min', '10', 'article'),
('content_max', '50000', 'article'),
('citypath_without', 'index/announcement\r\nfenlei/detail\r\nparty/detail\r\nask/detail\r\ntuan/detail\r\nproduct/detail\r\narticle/detail\r\nitem/detail\r\ncoupon/detail\r\nreview/detail\r\nexchange/gift\r\nspace/*\r\ngroup/*', 'modoer'),
('sellgroup_pointtype', 'point1', 'member'),
('sellgroup_useday', '30', 'member'),
('passport_login', '0', 'member'),
('passport_pw', '0', 'member'),
('registered_again', '0', 'member'),
('email_verify', '0', 'member'),
('mobile_verify', '0', 'member'),
('mobile_verify_message', '$sitename 用户手机认证验证码：$serial', 'member'),
('sldomain', '0', 'item'),
('base_sldomain', '', 'item'),
('reserve_sldomain', '', 'item'),
('selltpl_pointtype', 'point1', 'item'),
('selltpl_useday', '180', 'item'),
('seccode_review', '0', 'item'),
('seccode_review_guest', '0', 'item'),
('review_min', '10', 'item'),
('review_max', '2000', 'item'),
('respond_min', '10', 'item'),
('respond_max', '500', 'item'),
('avatar_review', '0', 'item'),
('search_location', '0', 'item'),
('album_comment', '1', 'item'),
('ajax_taoke', '0', 'item'),
('review_num', '', 'item'),
('show_detail_vs_review', '0', 'item'),
('close_detail_total', '0', 'item'),
('list_filter_li_collapse_num', '', 'item'),
('pointgroup', 'point1', 'exchange'),
('pointgroup', 'point1', 'card'),
('city_sldomain', '0', 'modoer'),
('utf8url', '0', 'modoer'),
('picture_dir_mod', 'MONTH', 'modoer'),
('meta_keywords', '', 'review'),
('meta_description', '', 'review'),
('respondcheck', '1', 'review'),
('tag_split_sp', '0', 'review'),
('default_grade', '0', 'review'),
('digest_price', '10', 'review'),
('digest_pointtype', 'point1', 'review'),
('digest_gain', '', 'review'),
('cmds', 'help,my,nearby,search,welcome', 'weixin'),
('version', 'MC 3.4', 'modoer'),
('build', '20141229', 'modoer'),
('authkey', 'QVFC9212JL9X', 'modoer'),
('siteurl', 'http://localhost/mijia', 'modoer'),
('jscache_flag', '394', 'article');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_coupons`
--

CREATE TABLE IF NOT EXISTS `modoer_coupons` (
  `couponid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) NOT NULL DEFAULT '0',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0',
  `subject` varchar(100) NOT NULL DEFAULT '',
  `des` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `effect1` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `prints` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sms_text` varchar(255) NOT NULL DEFAULT '',
  `send_num` int(10) NOT NULL DEFAULT '0',
  `grade` smallint(5) unsigned NOT NULL DEFAULT '0',
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `pageview` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `closed_comment` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`couponid`),
  KEY `sid` (`sid`),
  KEY `uid` (`uid`),
  KEY `city_id` (`city_id`,`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_coupons`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_coupon_category`
--

CREATE TABLE IF NOT EXISTS `modoer_coupon_category` (
  `catid` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `num` mediumint(9) NOT NULL DEFAULT '0',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `modoer_coupon_category`
--

INSERT INTO `modoer_coupon_category` (`catid`, `name`, `num`, `listorder`) VALUES
(1, '美食', 0, 0),
(2, '购物', 0, 0),
(3, '休闲', 0, 0),
(4, '女性', 0, 0),
(5, '摄影', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_coupon_print`
--

CREATE TABLE IF NOT EXISTS `modoer_coupon_print` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `couponid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `point` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `couponid` (`couponid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_coupon_print`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_datacall`
--

CREATE TABLE IF NOT EXISTS `modoer_datacall` (
  `callid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(60) NOT NULL DEFAULT '',
  `calltype` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `fun` varchar(60) NOT NULL DEFAULT '',
  `var` varchar(60) NOT NULL DEFAULT '',
  `cachetime` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `expression` text NOT NULL,
  `tplname` varchar(200) NOT NULL DEFAULT '',
  `empty_tplname` varchar(200) NOT NULL DEFAULT '',
  `closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`callid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `modoer_datacall`
--

INSERT INTO `modoer_datacall` (`callid`, `module`, `calltype`, `name`, `fun`, `var`, `cachetime`, `expression`, `tplname`, `empty_tplname`, `closed`, `hash`) VALUES
(5, 'item', 'sql', '主题_会员参与', 'sql', 'mydata', 1000, 'a:6:{s:4:"from";s:72:"{dbpre}membereffect_total mt LEFT JOIN {dbpre}subject s ON (mt.id=s.sid)";s:6:"select";s:58:"mt.{field:effect} as effect,s.sid,s.catid,s.name,s.subname";s:5:"where";s:77:"mt.idtype={idtype} AND mt.{field:effect}>0 AND s.city_id IN ({array:city_id})";s:5:"other";s:0:"";s:7:"orderby";s:22:"mt.{field:effect} DESC";s:5:"limit";s:4:"0,10";}', 'item_subject_effect_li', 'empty_li', 0, ''),
(8, 'item', 'sql', '主题_相关主题', 'sql', 'mydata', 1000, 'a:6:{s:4:"from";s:14:"{dbpre}subject";s:6:"select";s:52:"sid,pid,catid,name,subname,avgsort,pageviews,reviews";s:5:"where";s:72:"city_id IN ({array:city_id}) and name={name} and status=1 and sid!={sid}";s:5:"other";s:0:"";s:7:"orderby";s:12:"addtime DESC";s:5:"limit";s:4:"0,10";}', 'item_subject_li', 'empty_li', 0, ''),
(6, 'item', 'sql', '主题_同类主题', 'sql', 'mydata', 1000, 'a:6:{s:4:"from";s:14:"{dbpre}subject";s:6:"select";s:52:"sid,pid,catid,name,subname,avgsort,pageviews,reviews";s:5:"where";s:74:"city_id IN ({array:city_id}) and catid={catid} and status=1 and sid!={sid}";s:5:"other";s:0:"";s:7:"orderby";s:12:"addtime DESC";s:5:"limit";s:4:"0,10";}', 'item_subject_li', 'empty_li', 0, ''),
(7, 'item', 'sql', '主题_附近主题', 'sql', 'mydata', 1000, 'a:6:{s:4:"from";s:14:"{dbpre}subject";s:6:"select";s:52:"sid,pid,catid,name,subname,avgsort,pageviews,reviews";s:5:"where";s:37:"aid={aid} and status=1 and sid!={sid}";s:5:"other";s:0:"";s:7:"orderby";s:12:"addtime DESC";s:5:"limit";s:4:"0,10";}', 'item_subject_li', 'empty_li', 0, ''),
(11, 'item', 'sql', '首页_推荐主题', 'sql', 'mydata', 1000, 'a:6:{s:4:"from";s:14:"{dbpre}subject";s:6:"select";s:46:"sid,aid,name,subname,avgsort,thumb,description";s:5:"where";s:67:"city_id IN ({array:city_id}) AND pid={pid} AND status=1 AND finer>0";s:5:"other";s:0:"";s:7:"orderby";s:10:"finer DESC";s:5:"limit";s:3:"0,8";}', 'index_subject_finer', 'empty_div', 0, ''),
(16, 'product', 'sql', '产品_主题产品', 'sql', 'mydata', 1000, 'a:6:{s:4:"from";s:14:"{dbpre}product";s:6:"select";s:59:"pid,catid,subject,grade,description,thumb,comments,pageview";s:5:"where";s:22:"sid={sid} AND status=1";s:5:"other";s:0:"";s:7:"orderby";s:24:"grade DESC,comments DESC";s:5:"limit";s:4:"0,10";}', 'product_pic_li', 'empty_li', 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_dbcache`
--

CREATE TABLE IF NOT EXISTS `modoer_dbcache` (
  `cache_key` char(200) NOT NULL COMMENT '缓存键名',
  `cache_value` mediumtext NOT NULL COMMENT '缓存内容',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次更新时间',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缓存过期时间：0无限期',
  PRIMARY KEY (`cache_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_dbcache`
--

INSERT INTO `modoer_dbcache` (`cache_key`, `cache_value`, `update_time`, `expire_time`) VALUES
('comm_session_online', '6	1433647131', 1433647131, 0),
('comm_dbcache_expire', '1433644112', 1433644112, 0),
('comm_task_nexttime', '1433694600', 1433644112, 0),
('comm_session_expire', '1433647388', 1433647388, 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_digest_pay`
--

CREATE TABLE IF NOT EXISTS `modoer_digest_pay` (
  `payid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `idtype` char(15) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` char(20) NOT NULL DEFAULT '',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `pointtype` enum('point1','point2','point3','point4','point5','point6') NOT NULL DEFAULT 'point1',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `gain_uid` mediumint(8) NOT NULL DEFAULT '0',
  `gain_price` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`payid`),
  KEY `id` (`id`,`idtype`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_digest_pay`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_exchange_category`
--

CREATE TABLE IF NOT EXISTS `modoer_exchange_category` (
  `catid` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `num` mediumint(9) NOT NULL DEFAULT '0',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `modoer_exchange_category`
--

INSERT INTO `modoer_exchange_category` (`catid`, `name`, `num`, `listorder`) VALUES
(1, '默认分类', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_exchange_gifts`
--

CREATE TABLE IF NOT EXISTS `modoer_exchange_gifts` (
  `giftid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `sort` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `pattern` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `reviewed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0',
  `randomcodelen` tinyint(1) NOT NULL DEFAULT '0',
  `randomcode` varchar(50) NOT NULL DEFAULT '',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `point` int(10) unsigned NOT NULL DEFAULT '0',
  `point3` int(10) unsigned NOT NULL DEFAULT '0',
  `point4` int(10) unsigned NOT NULL DEFAULT '0',
  `pointtype` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `pointtype2` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `pointtype3` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `pointtype4` enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `timenum` int(10) unsigned NOT NULL DEFAULT '0',
  `pageview` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `salevolume` int(11) unsigned NOT NULL DEFAULT '0',
  `allowtime` varchar(255) NOT NULL DEFAULT '',
  `usergroup` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`giftid`),
  KEY `available` (`available`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_exchange_gifts`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_exchange_log`
--

CREATE TABLE IF NOT EXISTS `modoer_exchange_log` (
  `exchangeid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(25) NOT NULL DEFAULT '',
  `giftid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `giftname` varchar(200) NOT NULL DEFAULT '',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `pointtype` enum('point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL DEFAULT 'point1',
  `number` int(10) unsigned NOT NULL DEFAULT '1',
  `pay_style` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `status_extra` varchar(255) NOT NULL DEFAULT '',
  `exchangetime` int(10) NOT NULL DEFAULT '0',
  `contact` mediumtext NOT NULL,
  `checker` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`exchangeid`),
  KEY `uid` (`uid`),
  KEY `giftid` (`giftid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_exchange_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_exchange_lottery`
--

CREATE TABLE IF NOT EXISTS `modoer_exchange_lottery` (
  `lid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `giftid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `lotterycode` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`),
  KEY `giftid` (`giftid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_exchange_lottery`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_exchange_serial`
--

CREATE TABLE IF NOT EXISTS `modoer_exchange_serial` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `giftid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `exchangeid` mediumint(8) NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `serial` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `sendtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `giftid` (`giftid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_exchange_serial`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_favorites`
--

CREATE TABLE IF NOT EXISTS `modoer_favorites` (
  `fid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `idtype` char(20) NOT NULL DEFAULT '',
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fid`),
  KEY `addtime` (`addtime`),
  KEY `uid` (`uid`,`addtime`),
  KEY `idtype` (`idtype`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_favorites`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_field`
--

CREATE TABLE IF NOT EXISTS `modoer_field` (
  `fieldid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `idtype` varchar(30) NOT NULL DEFAULT '',
  `id` smallint(5) NOT NULL DEFAULT '0',
  `tablename` varchar(25) NOT NULL DEFAULT '',
  `fieldname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `unit` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(255) NOT NULL DEFAULT '',
  `template` text NOT NULL,
  `note` mediumtext NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `allownull` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enablesearch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `iscore` tinyint(1) NOT NULL DEFAULT '0',
  `isadminfield` varchar(1) NOT NULL DEFAULT '0',
  `show_list` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `show_detail` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `regular` varchar(255) NOT NULL DEFAULT '',
  `errmsg` varchar(255) NOT NULL DEFAULT '',
  `datatype` varchar(60) NOT NULL DEFAULT '',
  `config` text NOT NULL,
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `tablename` (`tablename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_field`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_flowers`
--

CREATE TABLE IF NOT EXISTS `modoer_flowers` (
  `fid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `idtype` char(20) NOT NULL DEFAULT 'review',
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(16) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fid`),
  KEY `reviewid` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_flowers`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_friends`
--

CREATE TABLE IF NOT EXISTS `modoer_friends` (
  `fid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `friend_uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fid`),
  KEY `addtime` (`addtime`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_friends`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_gbooks`
--

CREATE TABLE IF NOT EXISTS `modoer_gbooks` (
  `gid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gbuid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gbusername` varchar(16) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `posttime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `uid` (`uid`,`posttime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_gbooks`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_getpassword`
--

CREATE TABLE IF NOT EXISTS `modoer_getpassword` (
  `getpwid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `secode` varchar(8) NOT NULL DEFAULT '',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `sort` enum('get_password','email_verify') NOT NULL DEFAULT 'get_password',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`getpwid`),
  KEY `secode` (`secode`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_getpassword`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_group`
--

CREATE TABLE IF NOT EXISTS `modoer_group` (
  `gid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `city_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属城市（地方小组），0为全国',
  `catid` mediumint(8) NOT NULL DEFAULT '0',
  `groupname` char(60) NOT NULL DEFAULT '',
  `topics` mediumint(8) NOT NULL DEFAULT '0',
  `replies` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `members` mediumint(8) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `username` char(30) NOT NULL DEFAULT '',
  `lastpost` int(10) NOT NULL DEFAULT '0',
  `icon` char(255) NOT NULL DEFAULT '',
  `tags` char(100) NOT NULL DEFAULT '',
  `auth` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `finer` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `des` text NOT NULL,
  PRIMARY KEY (`gid`),
  KEY `groupname` (`groupname`),
  KEY `status` (`status`,`members`),
  KEY `sid` (`sid`,`status`),
  KEY `uid` (`uid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_group`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_group_category`
--

CREATE TABLE IF NOT EXISTS `modoer_group_category` (
  `catid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) NOT NULL DEFAULT '0',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  `tags` text NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_group_category`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_group_member`
--

CREATE TABLE IF NOT EXISTS `modoer_group_member` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `gid` mediumint(8) NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `jointime` int(10) NOT NULL DEFAULT '0',
  `lastpost` int(10) unsigned NOT NULL DEFAULT '0',
  `bantime` int(10) unsigned NOT NULL DEFAULT '0',
  `usertype` tinyint(1) NOT NULL DEFAULT '10',
  `posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `applydes` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`,`uid`),
  KEY `list` (`gid`,`status`,`jointime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_group_member`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_group_reply`
--

CREATE TABLE IF NOT EXISTS `modoer_group_reply` (
  `rpid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tpid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '10',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `pictures` text NOT NULL,
  `content` text NOT NULL,
  `source` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rpid`),
  KEY `tpid` (`tpid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_group_reply`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_group_setting`
--

CREATE TABLE IF NOT EXISTS `modoer_group_setting` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gid` mediumint(8) NOT NULL DEFAULT '0',
  `variable` char(30) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`,`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_group_setting`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_group_tag`
--

CREATE TABLE IF NOT EXISTS `modoer_group_tag` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tagname` char(8) NOT NULL DEFAULT '',
  `gid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tagname` (`tagname`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_group_tag`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_group_topic`
--

CREATE TABLE IF NOT EXISTS `modoer_group_topic` (
  `tpid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gid` mediumint(8) NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `pageview` int(10) unsigned NOT NULL DEFAULT '0',
  `replies` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `replytime` int(10) unsigned NOT NULL DEFAULT '0',
  `source` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `top` tinyint(1) NOT NULL DEFAULT '0',
  `digest` int(1) unsigned NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `pictures` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`tpid`),
  KEY `list` (`gid`,`status`,`top`,`replytime`),
  KEY `uid` (`uid`,`status`,`dateline`),
  KEY `dateline` (`status`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_group_topic`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_group_topic_type`
--

CREATE TABLE IF NOT EXISTS `modoer_group_topic_type` (
  `typeid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL DEFAULT '',
  `gid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeid`),
  KEY `gid` (`gid`,`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_group_topic_type`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_guestbook`
--

CREATE TABLE IF NOT EXISTS `modoer_guestbook` (
  `guestbookid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `uid` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `reply` text NOT NULL,
  `replytime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`guestbookid`),
  KEY `id` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_guestbook`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_hook`
--

CREATE TABLE IF NOT EXISTS `modoer_hook` (
  `hookid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `hook_module` varchar(30) NOT NULL DEFAULT '',
  `hook_position` varchar(60) NOT NULL DEFAULT '',
  `module` varchar(30) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `des` varchar(255) NOT NULL DEFAULT '',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`hookid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_hook`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_membereffect`
--

CREATE TABLE IF NOT EXISTS `modoer_membereffect` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `idtype` varchar(30) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `effect1` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `effect2` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `effect3` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`idtype`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_membereffect`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_membereffect_total`
--

CREATE TABLE IF NOT EXISTS `modoer_membereffect_total` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `idtype` varchar(30) NOT NULL DEFAULT '',
  `effect1` int(10) unsigned NOT NULL DEFAULT '0',
  `effect2` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`idtype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_membereffect_total`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_members`
--

CREATE TABLE IF NOT EXISTS `modoer_members` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `paypw` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(16) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `rmb` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `point` int(10) NOT NULL DEFAULT '0',
  `point1` int(10) NOT NULL DEFAULT '0',
  `point2` int(10) NOT NULL DEFAULT '0',
  `point3` int(10) NOT NULL DEFAULT '0',
  `point4` int(10) NOT NULL DEFAULT '0',
  `point5` int(10) NOT NULL DEFAULT '0',
  `point6` int(10) NOT NULL DEFAULT '0',
  `newmsg` smallint(5) unsigned NOT NULL DEFAULT '0',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `regip` char(15) NOT NULL DEFAULT '',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `loginip` varchar(16) NOT NULL DEFAULT '',
  `logincount` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `groupid` smallint(2) NOT NULL DEFAULT '1',
  `nextgroupid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `nexttime` int(10) unsigned NOT NULL DEFAULT '0',
  `subjects` int(10) unsigned NOT NULL DEFAULT '0',
  `reviews` int(10) unsigned NOT NULL DEFAULT '0',
  `responds` int(10) unsigned NOT NULL DEFAULT '0',
  `flowers` int(10) unsigned NOT NULL DEFAULT '0',
  `pictures` int(10) unsigned NOT NULL DEFAULT '0',
  `follow` int(10) unsigned NOT NULL DEFAULT '0',
  `fans` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `groupid` (`groupid`),
  KEY `point` (`point`),
  KEY `point1` (`point1`),
  KEY `regip` (`regip`,`regdate`),
  KEY `mobile` (`mobile`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `modoer_members`
--

INSERT INTO `modoer_members` (`uid`, `email`, `password`, `paypw`, `username`, `mobile`, `rmb`, `point`, `point1`, `point2`, `point3`, `point4`, `point5`, `point6`, `newmsg`, `regdate`, `regip`, `logintime`, `loginip`, `logincount`, `groupid`, `nextgroupid`, `nexttime`, `subjects`, `reviews`, `responds`, `flowers`, `pictures`, `follow`, `fans`) VALUES
(1, 'wuxiaofengdeemail@126.com', 'e10adc3949ba59abbe56e057f20f883e', '', 'fex', '', 0.00, 20, 20, 0, 0, 0, 0, 0, 1, 1432371549, '127.0.0.1', 1433645775, '127.0.0.1', 12, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'wuxiaofmail@126.com', 'e10adc3949ba59abbe56e057f20f883e', '', 'fex2', '', 0.00, 20, 20, 0, 0, 0, 0, 0, 1, 1433513427, '127.0.0.1', 1433553795, '127.0.0.1', 2, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'engdeemail@126.com', 'e10adc3949ba59abbe56e057f20f883e', '', 'fex3', '', 0.00, 20, 20, 0, 0, 0, 0, 0, 1, 1433595507, '127.0.0.1', 1433644112, '127.0.0.1', 2, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'email@126.com', 'e10adc3949ba59abbe56e057f20f883e', '', 'fex4', '', 0.00, 20, 20, 0, 0, 0, 0, 0, 1, 1433646966, '127.0.0.1', 1433646966, '127.0.0.1', 1, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_member_address`
--

CREATE TABLE IF NOT EXISTS `modoer_member_address` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  `addr` tinytext NOT NULL,
  `postcode` varchar(60) NOT NULL DEFAULT '',
  `mobile` varchar(60) NOT NULL DEFAULT '',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_member_address`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_member_feed`
--

CREATE TABLE IF NOT EXISTS `modoer_member_feed` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `flag` varchar(30) NOT NULL DEFAULT '',
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(25) NOT NULL DEFAULT '',
  `module` varchar(15) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(30) NOT NULL,
  `title` text NOT NULL,
  `body` text NOT NULL,
  `images` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_member_feed`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_member_invite`
--

CREATE TABLE IF NOT EXISTS `modoer_member_invite` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `inviter_uid` mediumint(8) NOT NULL DEFAULT '0',
  `inviter` char(30) NOT NULL DEFAULT '',
  `invitee_uid` mediumint(8) NOT NULL DEFAULT '0',
  `invitee` char(30) NOT NULL DEFAULT '',
  `add_point` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `inviter_uid` (`inviter_uid`,`dateline`,`add_point`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_member_invite`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_member_passport`
--

CREATE TABLE IF NOT EXISTS `modoer_member_passport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `psname` enum('weibo','qq','taobao','google','renren','txweibo','twitter','facebook','wechat','yahoo','jd') NOT NULL,
  `psuid` char(60) NOT NULL DEFAULT '',
  `uid` mediumint(10) unsigned NOT NULL DEFAULT '0',
  `access_token` varchar(512) NOT NULL DEFAULT '',
  `expired` int(10) NOT NULL DEFAULT '0',
  `token_data` text,
  `isbind` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `psname_psuid` (`psname`,`psuid`),
  UNIQUE KEY `uid_psname` (`uid`,`psname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_member_passport`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_member_point`
--

CREATE TABLE IF NOT EXISTS `modoer_member_point` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rmb` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `point` int(10) NOT NULL DEFAULT '0',
  `point1` int(10) NOT NULL DEFAULT '0',
  `point2` int(10) NOT NULL DEFAULT '0',
  `point3` int(10) NOT NULL DEFAULT '0',
  `point4` int(10) NOT NULL DEFAULT '0',
  `point5` int(10) NOT NULL DEFAULT '0',
  `point6` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `modoer_member_point`
--

INSERT INTO `modoer_member_point` (`uid`, `rmb`, `point`, `point1`, `point2`, `point3`, `point4`, `point5`, `point6`) VALUES
(1, 0.00, 20, 20, 0, 0, 0, 0, 0),
(2, 0.00, 20, 20, 0, 0, 0, 0, 0),
(3, 0.00, 20, 20, 0, 0, 0, 0, 0),
(4, 0.00, 20, 20, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_member_point_log`
--

CREATE TABLE IF NOT EXISTS `modoer_member_point_log` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `out_uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `out_username` varchar(25) NOT NULL DEFAULT '',
  `out_point` varchar(20) NOT NULL DEFAULT '',
  `out_value` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `in_uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `in_username` varchar(25) NOT NULL DEFAULT '',
  `in_point` varchar(20) NOT NULL DEFAULT '',
  `in_value` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `des` text NOT NULL,
  `extra` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `out_uid` (`out_uid`,`in_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_member_point_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_member_profile`
--

CREATE TABLE IF NOT EXISTS `modoer_member_profile` (
  `uid` mediumint(8) NOT NULL,
  `realname` varchar(100) NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `alipay` varchar(255) NOT NULL DEFAULT '',
  `qq` varchar(255) NOT NULL DEFAULT '',
  `wechat` varchar(255) NOT NULL DEFAULT '',
  `msn` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_member_profile`
--

INSERT INTO `modoer_member_profile` (`uid`, `realname`, `gender`, `birthday`, `alipay`, `qq`, `wechat`, `msn`, `address`, `zipcode`) VALUES
(1, 'uyuys', 0, '0000-00-00', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_member_verify`
--

CREATE TABLE IF NOT EXISTS `modoer_member_verify` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `hash` char(32) NOT NULL DEFAULT '',
  `verify_code` char(8) NOT NULL DEFAULT '',
  `action_flag` char(20) NOT NULL DEFAULT '',
  `expriy_date` int(10) unsigned NOT NULL DEFAULT '0',
  `uniq` char(16) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sender` enum('mobile','email') NOT NULL,
  `sender_id` char(100) NOT NULL DEFAULT '',
  `send_time` int(10) NOT NULL DEFAULT '0',
  `extra` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `verify_code_action_flag` (`verify_code`,`action_flag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_member_verify`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_menus`
--

CREATE TABLE IF NOT EXISTS `modoer_menus` (
  `menuid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '菜单当前层级',
  `isclosed` tinyint(1) NOT NULL DEFAULT '0',
  `isfolder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `scriptnav` varchar(60) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(60) NOT NULL DEFAULT '',
  `target` varchar(15) NOT NULL DEFAULT '',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `top_level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '菜单子类数量，0表示无限级',
  `flag` char(20) NOT NULL DEFAULT '' COMMENT '自定义标示，用于系统定位专用菜单',
  PRIMARY KEY (`menuid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=107 ;

--
-- 转存表中的数据 `modoer_menus`
--

INSERT INTO `modoer_menus` (`menuid`, `parentid`, `level`, `isclosed`, `isfolder`, `title`, `scriptnav`, `url`, `icon`, `target`, `listorder`, `top_level`, `flag`) VALUES
(1, 0, 1, 0, 1, '头部菜单', '', '', '', '', 1, 0, ''),
(49, 1, 1, 0, 0, '首页', 'index', 'modoer/index', '', '_self', 1, 0, ''),
(3, 0, 1, 0, 1, '后台快捷菜单', '', '', '', '', 5, 0, ''),
(53, 3, 1, 0, 0, '调用管理', '', '?module=modoer&act=datacall&op=list', '', 'main', 3, 0, ''),
(54, 3, 1, 0, 0, '更新网站缓存', '', '?module=modoer&act=tools&op=cache', '', 'main', 4, 0, ''),
(62, 1, 1, 0, 0, '主题', 'item_category', 'item/category', '', '', 4, 0, ''),
(75, 3, 1, 0, 0, '菜单管理', '', '?module=modoer&act=menu', '', '', 5, 0, ''),
(66, 0, 1, 0, 1, '底部菜单', '', '', '', '', 2, 0, ''),
(68, 66, 1, 0, 0, '联系我们', '', '#', '', '', 0, 0, ''),
(69, 66, 1, 0, 0, '广告服务', '', '#', '', '', 0, 0, ''),
(70, 66, 1, 0, 0, '服务条款', '', '#', '', '', 0, 0, ''),
(71, 66, 1, 0, 0, '网站地图', '', '#', '', '', 0, 0, ''),
(72, 66, 1, 0, 0, '使用帮助', '', '#', '', '', 0, 0, ''),
(73, 66, 1, 0, 0, '诚聘英才', '', '#', '', '', 0, 0, ''),
(76, 3, 1, 0, 0, '主题审核', '', '?module=item&act=subject_check', '', '', 1, 0, ''),
(77, 3, 1, 0, 0, '点评审核', '', '?module=review&act=review&op=checklist', '', '', 2, 0, ''),
(88, 1, 1, 0, 0, '礼品', 'exchange', 'exchange/index', '', '', 9, 0, ''),
(90, 1, 1, 0, 0, '资讯', 'article', 'article/index', '', '', 3, 0, ''),
(93, 1, 1, 0, 0, '会员卡', 'card', 'card/index', '', '', 11, 0, ''),
(94, 1, 1, 0, 0, '优惠券', 'coupon', 'coupon/index', '', '', 10, 0, ''),
(95, 1, 1, 0, 0, '相册', 'item_album', 'item/album', '', '', 12, 0, ''),
(97, 1, 1, 0, 0, '点评', 'review', 'review/index', '', '', 14, 0, ''),
(98, 1, 1, 0, 0, '排行榜', 'item_subject_tops', 'item/tops', '', '', 15, 0, ''),
(99, 1, 1, 0, 0, '小组', 'group', 'group/index', '', '', 16, 0, ''),
(100, 1, 1, 0, 0, '榜单', 'mylist', 'mylist/index', '', '', 17, 0, ''),
(101, 0, 1, 0, 1, '后台头部菜单', '', '', '', '', 5, 1, 'console_header'),
(102, 101, 1, 0, 0, '会员', '', 'member', '', '', 0, 0, ''),
(103, 101, 1, 0, 0, '主题', '', 'item', '', '', 0, 0, ''),
(104, 101, 1, 0, 0, '点评', '', 'review', '', '', 0, 0, ''),
(105, 101, 1, 0, 0, '资讯', '', 'article', '', '', 0, 0, ''),
(106, 101, 1, 0, 0, '小组', '', 'group', '', '', 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_mobile_verify`
--

CREATE TABLE IF NOT EXISTS `modoer_mobile_verify` (
  `id` mediumint(10) NOT NULL AUTO_INCREMENT,
  `uniq` char(32) NOT NULL DEFAULT '',
  `mobile` char(20) NOT NULL DEFAULT '',
  `serial` char(6) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniq` (`uniq`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_mobile_verify`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_model`
--

CREATE TABLE IF NOT EXISTS `modoer_model` (
  `modelid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `tablename` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `usearea` tinyint(1) NOT NULL DEFAULT '0',
  `item_name` varchar(200) NOT NULL DEFAULT '',
  `item_unit` varchar(200) NOT NULL DEFAULT '',
  `tplname_list` varchar(200) NOT NULL DEFAULT '',
  `tplname_detail` varchar(200) NOT NULL DEFAULT '',
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`modelid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `modoer_model`
--

INSERT INTO `modoer_model` (`modelid`, `name`, `tablename`, `description`, `usearea`, `item_name`, `item_unit`, `tplname_list`, `tplname_detail`, `disable`) VALUES
(1, '商铺模型', 'subject_shops', '', 1, '商铺', '户', 'item_subject_list', 'item_subject_detail', 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_modules`
--

CREATE TABLE IF NOT EXISTS `modoer_modules` (
  `moduleid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `flag` varchar(30) NOT NULL DEFAULT '',
  `extra` varchar(20) NOT NULL DEFAULT '',
  `iscore` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  `directory` varchar(100) NOT NULL DEFAULT '',
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  `version` varchar(60) NOT NULL DEFAULT '',
  `releasetime` date NOT NULL DEFAULT '0000-00-00',
  `reliant` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(255) NOT NULL DEFAULT '',
  `introduce` text NOT NULL,
  `siteurl` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `copyright` varchar(255) NOT NULL DEFAULT '',
  `checkurl` varchar(255) NOT NULL DEFAULT '',
  `liccode` text NOT NULL,
  PRIMARY KEY (`moduleid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `modoer_modules`
--

INSERT INTO `modoer_modules` (`moduleid`, `flag`, `extra`, `iscore`, `listorder`, `name`, `directory`, `disable`, `config`, `version`, `releasetime`, `reliant`, `author`, `introduce`, `siteurl`, `email`, `copyright`, `checkurl`, `liccode`) VALUES
(1, 'member', '', 1, 8, '会员', 'member', 0, '', '1.1', '2008-09-30', '', 'Moufer Studio', ' ', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', '', ''),
(2, 'item', '', 1, 1, '主题', 'item', 0, '', '3.3', '2014-03-25', '', 'Moufer Studio', '主题模块', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/item.php', ''),
(3, 'space', '', 1, 9, '个人空间', 'space', 0, '', '1.1', '2008-09-30', '', 'Moufer Studio', '', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', '', ''),
(4, 'link', '', 0, 10, '友情链接', 'link', 0, '', '2.0', '2010-05-04', '', 'moufer', '友情链接模块', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/comment.php', ''),
(6, 'comment', '', 0, 6, '会员评论', 'comment', 0, '', '1.0', '2010-04-01', '', 'moufer', '评论模块可用于其他需要进行评论的模块', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/comment.php', ''),
(7, 'exchange', '', 0, 5, '礼品兑换', 'exchange', 0, '', '3.0', '2012-05-01', '', 'moufer,轩', '使用网站金币兑换礼品，抽奖，刺激玩家的积极性，消费金币', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/exchange.php', ''),
(8, 'article', '', 0, 3, '新闻资讯', 'article', 0, '', '2.0', '2010-04-14', '', 'moufer', '文章信息，发布网站信息和主题资讯', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/article.php', ''),
(9, 'card', '', 0, 7, '会员卡', 'card', 0, '', '2.1', '2010-09-29', 'item', 'moufer', '会员卡模块用户管理消费类主题提供优惠折扣信息', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/card.php', ''),
(10, 'coupon', '', 0, 4, '优惠券', 'coupon', 0, '', '2.0', '2010-05-10', '', 'moufer', '优惠券模块，提供分享和打印折扣和优惠信息', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/coupon.php', ''),
(11, 'adv', '', 0, 10, '广告', 'adv', 0, '', '2.0', '2010-12-30', '', 'moufer', '自定义广告模块', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/adv.php', ''),
(12, 'review', '', 1, 2, '点评', 'review', 0, '', '2.6', '2014-03-25', '', 'Moufer Studio', '点评模块', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/review.php', ''),
(13, 'sms', '', 0, 10, '短信发送', 'sms', 0, '', '1.0', '2011-12-06', '', 'moufer', '短信发送模块', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/sms.php', ''),
(14, 'pay', '', 0, 10, '在线充值', 'pay', 0, '', '2.2', '2012-03-30', '', 'moufer', '在线积分充值模块', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/pay.php', ''),
(15, 'group', '', 0, 10, '小组', 'group', 0, '', '1.2', '2014-03-25', '', 'moufer', '网站会员小组讨论模块', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/group.php', ''),
(16, 'mobile', '', 0, 11, '手机浏览', 'mobile', 0, '', '1.0', '2012-10-19', 'item', 'moufer', '基于HTML5的手机浏览模块', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/mobile.php', ''),
(17, 'mylist', '', 0, 12, '榜单', 'mylist', 0, '', '1.0', '2014-03-25', '', 'moufer', '会员聚合主题信息成为一个榜单，给共同喜好人群参考', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/mylist.php', ''),
(18, 'weixin', '', 0, 13, '微信公众平台', 'weixin', 0, '', '1.0', '2014-07-19', '', 'moufer', '通过微信公众号与网站进行操作互动', 'http://www.modoer.com', 'moufer@163.com', 'Moufer Studio', 'http://www.modoer.com/info/module/weixin.php', '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_mylinks`
--

CREATE TABLE IF NOT EXISTS `modoer_mylinks` (
  `linkid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(40) NOT NULL DEFAULT '',
  `link` varchar(100) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL DEFAULT '',
  `des` varchar(255) NOT NULL DEFAULT '',
  `displayorder` int(8) NOT NULL DEFAULT '0',
  `ischeck` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`linkid`),
  KEY `list` (`ischeck`,`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `modoer_mylinks`
--

INSERT INTO `modoer_mylinks` (`linkid`, `city_id`, `title`, `link`, `logo`, `des`, `displayorder`, `ischeck`) VALUES
(1, 0, 'Modoer点评系统', 'http://www.modoer.com', '', 'Modoer 是一款点评网站管理系统，采用 PHP+MYSQL 设计，开放全部源码', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_mylist`
--

CREATE TABLE IF NOT EXISTS `modoer_mylist` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `city_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `catid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `favorites` int(10) unsigned NOT NULL DEFAULT '0',
  `flowers` int(10) unsigned NOT NULL DEFAULT '0',
  `responds` int(10) unsigned NOT NULL DEFAULT '0',
  `pageviews` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `modifytime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `intro` text NOT NULL,
  `digest` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cat_time` (`city_id`,`catid`,`modifytime`),
  KEY `city_time` (`city_id`,`modifytime`),
  KEY `uid_modifytime` (`uid`,`modifytime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_mylist`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_mylist_category`
--

CREATE TABLE IF NOT EXISTS `modoer_mylist_category` (
  `catid` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `num` mediumint(9) NOT NULL DEFAULT '0',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_mylist_category`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_mylist_item`
--

CREATE TABLE IF NOT EXISTS `modoer_mylist_item` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `mylist_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '榜单ID',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '添加会员UID',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `excuse` text NOT NULL COMMENT '推荐理由',
  `rid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点评的ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_mylist_item`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_mylist_tag_data`
--

CREATE TABLE IF NOT EXISTS `modoer_mylist_tag_data` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '标签索引ID',
  `tag_name` char(30) NOT NULL COMMENT '标签名称',
  `mylist_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '关联榜单ID',
  `city_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '榜单城市ID',
  PRIMARY KEY (`id`),
  KEY `city_id` (`city_id`,`tag_id`),
  KEY `mylist_id` (`mylist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_mylist_tag_data`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_mylist_tag_index`
--

CREATE TABLE IF NOT EXISTS `modoer_mylist_tag_index` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_mylist_tag_index`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_mysubject`
--

CREATE TABLE IF NOT EXISTS `modoer_mysubject` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) NOT NULL DEFAULT '0',
  `expirydate` int(10) unsigned NOT NULL DEFAULT '0',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_mysubject`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_mytask`
--

CREATE TABLE IF NOT EXISTS `modoer_mytask` (
  `uid` mediumint(8) unsigned NOT NULL,
  `taskid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `progress` smallint(3) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `applytime` int(10) unsigned NOT NULL DEFAULT '0',
  `total` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`taskid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_mytask`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_notice`
--

CREATE TABLE IF NOT EXISTS `modoer_notice` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` varchar(15) NOT NULL DEFAULT '',
  `type` varchar(15) NOT NULL DEFAULT '',
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `note` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`isread`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_notice`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_pay`
--

CREATE TABLE IF NOT EXISTS `modoer_pay` (
  `payid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `order_flag` varchar(30) NOT NULL DEFAULT '',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `order_name` varchar(255) NOT NULL DEFAULT '',
  `payment_orderid` varchar(60) NOT NULL DEFAULT '',
  `payment_name` varchar(60) NOT NULL DEFAULT '',
  `creation_time` int(10) NOT NULL DEFAULT '0',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `pay_status` tinyint(1) NOT NULL DEFAULT '0',
  `my_status` tinyint(1) NOT NULL DEFAULT '0',
  `notify_url` varchar(255) NOT NULL DEFAULT '',
  `callback_url` varchar(255) NOT NULL DEFAULT '',
  `callback_url_mobile` varchar(255) NOT NULL DEFAULT '',
  `royalty` tinytext NOT NULL,
  `goods` tinytext NOT NULL,
  PRIMARY KEY (`payid`),
  KEY `order_flag` (`order_flag`,`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_pay`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_pay_card`
--

CREATE TABLE IF NOT EXISTS `modoer_pay_card` (
  `cardid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(30) NOT NULL DEFAULT '',
  `cztype` varchar(15) DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `usetime` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`cardid`),
  UNIQUE KEY `number` (`number`),
  KEY `status` (`status`,`endtime`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_pay_card`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_pay_log`
--

CREATE TABLE IF NOT EXISTS `modoer_pay_log` (
  `orderid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `port_orderid` varchar(60) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `point` int(10) unsigned NOT NULL,
  `cztype` varchar(15) DEFAULT '',
  `dateline` int(10) NOT NULL,
  `exchangetime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `retcode` varchar(10) NOT NULL DEFAULT '',
  `ip` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_pay_log`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_pay_withdraw`
--

CREATE TABLE IF NOT EXISTS `modoer_pay_withdraw` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `charges` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `realname` varchar(255) NOT NULL DEFAULT '',
  `accounts` varchar(255) NOT NULL DEFAULT '',
  `pointtype` char(6) NOT NULL DEFAULT '',
  `applytime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(16) NOT NULL DEFAULT '',
  `status` enum('wait','succeed','fail','cancel') NOT NULL DEFAULT 'wait',
  `status_des` varchar(255) NOT NULL DEFAULT '',
  `optime` int(10) unsigned NOT NULL DEFAULT '0',
  `opowner` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_pay_withdraw`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_pictures`
--

CREATE TABLE IF NOT EXISTS `modoer_pictures` (
  `picid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `albumid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(16) NOT NULL DEFAULT '',
  `title` varchar(60) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `width` smallint(5) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` varchar(60) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` tinyint(1) NOT NULL DEFAULT '0',
  `browse` int(10) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`picid`),
  KEY `uid` (`uid`,`sid`),
  KEY `sid` (`sid`,`status`),
  KEY `city_id` (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_pictures`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_plan_task`
--

CREATE TABLE IF NOT EXISTS `modoer_plan_task` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '执行脚本文件名',
  `time_exp` varchar(255) NOT NULL DEFAULT '' COMMENT '执行时间表达式',
  `setting` text NOT NULL COMMENT '脚本配置信息',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后执行时间',
  `nexttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下次执行时间',
  `run_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已执行次数',
  PRIMARY KEY (`id`),
  KEY `nexttime` (`nexttime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `modoer_plan_task`
--

INSERT INTO `modoer_plan_task` (`id`, `filename`, `time_exp`, `setting`, `lasttime`, `nexttime`, `run_count`) VALUES
(1, 'delete_space_visitor', 'day=1|hour=0|minute=0', '', 1433426490, 1435680000, 2),
(2, 'delete_member_notice', 'day=1|hour=5|minute=0', '', 1433426490, 1435698000, 2),
(3, 'delete_search_cache', 'hour=0|minute=30', '', 1433644112, 1433694600, 14),
(4, 'delete_upload_temp', 'hour=3|minute=30', '', 1433644112, 1433705400, 14);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_pmsgs`
--

CREATE TABLE IF NOT EXISTS `modoer_pmsgs` (
  `pmid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `senduid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `recvuid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `subject` varchar(60) NOT NULL DEFAULT '',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `new` tinyint(1) NOT NULL DEFAULT '1',
  `delflag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `senduid` (`senduid`,`posttime`),
  KEY `recvuid` (`recvuid`,`posttime`),
  KEY `new` (`new`,`recvuid`,`posttime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `modoer_pmsgs`
--

INSERT INTO `modoer_pmsgs` (`pmid`, `senduid`, `recvuid`, `content`, `subject`, `posttime`, `new`, `delflag`) VALUES
(1, 0, 1, '尊敬的fex：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-05-23 16:59:09', '欢迎注册成为我们的会员！', 1432371549, 1, 0),
(2, 0, 2, '尊敬的fex2：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-05 22:10:27', '欢迎注册成为我们的会员！', 1433513427, 1, 0),
(3, 0, 3, '尊敬的fex3：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-06 20:58:27', '欢迎注册成为我们的会员！', 1433595507, 1, 0),
(4, 0, 4, '尊敬的fex4：\r\n\r\n欢迎您加入Modoer点评系统大家庭！\r\n祝你在Modoer点评系统过得愉快！\r\n\r\nModoer点评系统运营团队\r\n2015-06-07 11:16:06', '欢迎注册成为我们的会员！', 1433646966, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_point_log`
--

CREATE TABLE IF NOT EXISTS `modoer_point_log` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `flag` varchar(20) NOT NULL DEFAULT '',
  `point_flow` enum('in','out') NOT NULL,
  `point_type` enum('point','rmb','point1','point2','point3','point4','point5','point6') NOT NULL DEFAULT 'point',
  `point_value` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  `amount` decimal(9,2) NOT NULL DEFAULT '0.00',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `extra` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `modoer_point_log`
--

INSERT INTO `modoer_point_log` (`id`, `uid`, `username`, `flag`, `point_flow`, `point_type`, `point_value`, `amount`, `dateline`, `remark`, `extra`) VALUES
(1, 1, 'fex', 'reg', 'in', 'point', 20.00, 20.00, 1432371549, '用户行为积分变更(reg)', ''),
(2, 1, 'fex', 'reg', 'in', 'point1', 20.00, 20.00, 1432371549, '用户行为积分变更(reg)', ''),
(3, 2, 'fex2', 'reg', 'in', 'point', 20.00, 20.00, 1433513427, '用户行为积分变更(reg)', ''),
(4, 2, 'fex2', 'reg', 'in', 'point1', 20.00, 20.00, 1433513427, '用户行为积分变更(reg)', ''),
(5, 3, 'fex3', 'reg', 'in', 'point', 20.00, 20.00, 1433595507, '用户行为积分变更(reg)', ''),
(6, 3, 'fex3', 'reg', 'in', 'point1', 20.00, 20.00, 1433595507, '用户行为积分变更(reg)', ''),
(7, 4, 'fex4', 'reg', 'in', 'point', 20.00, 20.00, 1433646966, '用户行为积分变更(reg)', ''),
(8, 4, 'fex4', 'reg', 'in', 'point1', 20.00, 20.00, 1433646966, '用户行为积分变更(reg)', '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_reports`
--

CREATE TABLE IF NOT EXISTS `modoer_reports` (
  `reportid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(16) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `sort` tinyint(1) NOT NULL DEFAULT '0',
  `reportcontent` mediumtext NOT NULL,
  `disposal` tinyint(1) NOT NULL DEFAULT '0',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `disposaltime` int(10) NOT NULL DEFAULT '0',
  `reportremark` mediumtext NOT NULL,
  `update_point` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`reportid`),
  KEY `disposal` (`disposal`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_reports`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_responds`
--

CREATE TABLE IF NOT EXISTS `modoer_responds` (
  `respondid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `posttime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`respondid`),
  KEY `uid` (`uid`,`status`),
  KEY `reviewid` (`rid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_responds`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_review`
--

CREATE TABLE IF NOT EXISTS `modoer_review` (
  `rid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `idtype` varchar(30) NOT NULL DEFAULT '',
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `pcatid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sort1` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort2` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort3` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort4` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort5` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort6` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort7` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort8` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `digest` tinyint(1) NOT NULL DEFAULT '0',
  `havepic` tinyint(1) NOT NULL DEFAULT '0',
  `havevoice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有语音点评',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `isupdate` tinyint(1) NOT NULL DEFAULT '0',
  `flowers` int(8) unsigned NOT NULL DEFAULT '0',
  `responds` int(8) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(60) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `taggroup` text NOT NULL,
  `pictures` text NOT NULL,
  `voice_file` varchar(255) NOT NULL DEFAULT '' COMMENT '语音文件',
  `hide_name` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `source` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '点评来源，0：网站，1：手机模块',
  PRIMARY KEY (`rid`),
  KEY `sid` (`id`,`status`),
  KEY `uid` (`uid`,`status`),
  KEY `city_id` (`city_id`,`best`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_review`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_review_opt`
--

CREATE TABLE IF NOT EXISTS `modoer_review_opt` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `gid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `flag` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `modoer_review_opt`
--

INSERT INTO `modoer_review_opt` (`id`, `gid`, `flag`, `name`, `listorder`, `enable`) VALUES
(1, 1, 'sort1', '口味', 1, 1),
(2, 1, 'sort2', '服务', 2, 1),
(3, 1, 'sort3', '环境', 3, 1),
(4, 1, 'sort4', '性价比', 4, 1),
(5, 1, 'sort5', 'R5', 5, 0),
(6, 1, 'sort6', 'R6', 6, 0),
(7, 1, 'sort7', 'R7', 7, 0),
(8, 1, 'sort8', 'R8', 8, 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_review_opt_group`
--

CREATE TABLE IF NOT EXISTS `modoer_review_opt_group` (
  `gid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `des` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `modoer_review_opt_group`
--

INSERT INTO `modoer_review_opt_group` (`gid`, `name`, `des`) VALUES
(1, '默认点评项组', '系统默认安装');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_search_cache`
--

CREATE TABLE IF NOT EXISTS `modoer_search_cache` (
  `ssid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(60) NOT NULL DEFAULT '0',
  `count` mediumint(8) NOT NULL DEFAULT '0',
  `total` mediumint(8) NOT NULL DEFAULT '0',
  `catid` smallint(5) NOT NULL DEFAULT '0',
  `city_id` varchar(10) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ssid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_search_cache`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_session`
--

CREATE TABLE IF NOT EXISTS `modoer_session` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uniq` char(32) NOT NULL COMMENT '唯一会话ID',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '关联登录会员ID',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后会话时间',
  `last_url` char(255) NOT NULL DEFAULT '' COMMENT '最后访问页面URL',
  `ip_address` char(15) NOT NULL DEFAULT '' COMMENT '访客IP地址',
  `is_mobile` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否手机web访问',
  `user_agent` char(255) NOT NULL DEFAULT '' COMMENT '浏览器',
  `content` text NOT NULL COMMENT '会话内容',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq` (`uniq`),
  KEY `last_time` (`last_time`),
  KEY `uid_last_time` (`uid`,`last_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=625 ;

--
-- 转存表中的数据 `modoer_session`
--

INSERT INTO `modoer_session` (`id`, `uniq`, `uid`, `last_time`, `last_url`, `ip_address`, `is_mobile`, `user_agent`, `content`) VALUES
(615, 'ee4e5d9e2b22717cd12e884d12b1bc33', 0, 1433647152, '/mijia/member.php?act=index&ac=face', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(616, '8321abcd90e5c238709bb07fbd8fc6eb', 0, 1433647158, '/mijia/space.php?act=new', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(617, '95eb03808a3e2a6e0faf9d88f56f305a', 0, 1433647257, '/mijia/member.php?act=login&op=logout', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(618, '9e81be6b4aa06e8ce94af28ebb4958f5', 0, 1433647302, '/mijia/member.php?act=login', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(619, '58898fbb60a3be528c3759c285ec805b', 0, 1433647359, '/mijia/admin.php?module=space&act=music', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(620, '6938c0bf9832c239171c8f6c8f533a62', 0, 1433647373, '/mijia/admin.php?module=space&act=music', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(621, 'e978671f6ceeb8bb01d8a9a51f43f55d', 0, 1433647388, '/mijia/admin.php?module=space&act=theme', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(622, '8aedcbcb47988843ec1f98398cd99eb3', 0, 1433647391, '/mijia/admin.php?module=space&act=theme&op=edit&id=3', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(599, 'ee59afbd6687e4482be9f0199c24af54', 4, 1433647511, '/mijia/space.php?act=new', '127.0.0.1', 0, 'Mozilla/5.0 (Windows NT 5.1; rv:36.0) Gecko/20100101 Firefox/36.0', ''),
(623, 'eaf77894b61a2223297dd95c20e10329', 0, 1433647452, '/mijia/index.php?act=tool', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(614, 'f91d25244fbbb85d91edd245541d69f1', 0, 1433647138, '/mijia/member.php?act=index&ac=face', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(613, '6d1beb95598bdd15ca18daca54ba677f', 0, 1433647135, '/mijia/member.php?act=index&ac=myset', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(612, '4a3dacd57de422e76519cb6bb74d613f', 0, 1433647130, '/mijia/space.php?act=member&ac=new', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(610, 'bc37e055d58dc270bbc150c31b3893eb', 0, 1433646972, '/mijia/member.php?act=index', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(611, 'c1fcc57a1046074dfc7c2fb0afc0085d', 0, 1433646983, '/mijia/space.php?act=new', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(609, 'db25919f72dd3f69ee5dbd5853bc2740', 0, 1433646969, '/mijia/member.php?act=index', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', ''),
(624, 'c5a05c9232f153a215e52e64c019254b', 0, 1433647510, '/mijia/space.php?act=new', '127.0.0.1', 0, 'Mozilla/4.0 (compatible; Win32; WinHttp.WinHttpRequest.5)', '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_spaces`
--

CREATE TABLE IF NOT EXISTS `modoer_spaces` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `space_styleid` smallint(3) NOT NULL DEFAULT '0',
  `spacename` varchar(30) NOT NULL DEFAULT '',
  `spacedescribe` varchar(50) NOT NULL DEFAULT '',
  `pageview` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `pageviews` (`pageview`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_spaces`
--

INSERT INTO `modoer_spaces` (`uid`, `space_styleid`, `spacename`, `spacedescribe`, `pageview`) VALUES
(1, 0, 'fex的个人空间', '读万卷书模，行万里路！', 15);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_space_attend`
--

CREATE TABLE IF NOT EXISTS `modoer_space_attend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `is_part` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `name` varchar(32) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `part_num` smallint(4) unsigned NOT NULL DEFAULT '0',
  `content` varchar(255) NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `modoer_space_attend`
--

INSERT INTO `modoer_space_attend` (`id`, `uid`, `is_part`, `name`, `phone`, `part_num`, `content`, `dateline`) VALUES
(1, 1, 0, '13123', '', 0, '', 1432961137),
(2, 1, 1, '111', '222', 333, '444', 1432961161),
(3, 1, 1, '11133123', '222', 333, '444', 1432961223),
(6, 1, 1, '玩儿玩儿', '', 0, '', 1432961330),
(5, 1, 1, '斯蒂芬斯蒂芬', '', 0, '', 1432961262),
(7, 2, 1, '234234', '', 0, '', 1433561589),
(8, 2, 1, '林琳', '13534574565', 12, '新婚快乐', 1433561619),
(9, 4, 1, '小明', '12345651245', 1, '', 1433647298);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_space_music`
--

CREATE TABLE IF NOT EXISTS `modoer_space_music` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `listorder` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `src` varchar(255) NOT NULL,
  `name` varchar(60) NOT NULL,
  `des` varchar(255) NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `modoer_space_music`
--

INSERT INTO `modoer_space_music` (`id`, `listorder`, `src`, `name`, `des`, `dateline`, `status`) VALUES
(2, 1, 'uploads/space/2015-05/31_1432523656.mp3', '歌曲1', '12312311', 0, 1),
(4, 0, 'uploads/space/2015-06/9_1433510491.mp3', '歌曲2', '12312311', 1433510491, 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_space_new`
--

CREATE TABLE IF NOT EXISTS `modoer_space_new` (
  `uid` int(10) unsigned NOT NULL,
  `theme` int(10) unsigned NOT NULL DEFAULT '0',
  `music` int(10) unsigned NOT NULL DEFAULT '0',
  `cover` varchar(255) NOT NULL,
  `photobg` varchar(255) NOT NULL,
  `my_name` varchar(30) NOT NULL,
  `my_des` varchar(255) NOT NULL,
  `other_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `other_name` varchar(30) NOT NULL,
  `other_des` varchar(255) NOT NULL,
  `other_avatar` varchar(255) NOT NULL,
  `wedding_timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `wedding_address` varchar(255) NOT NULL,
  `wedding_map_point` varchar(255) NOT NULL,
  `hotel` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `is_rsvp` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_space_new`
--

INSERT INTO `modoer_space_new` (`uid`, `theme`, `music`, `cover`, `photobg`, `my_name`, `my_des`, `other_id`, `other_name`, `other_des`, `other_avatar`, `wedding_timestamp`, `wedding_address`, `wedding_map_point`, `hotel`, `domain`, `is_rsvp`) VALUES
(1, 4, 4, 'uploads/space/2015-05/47_1432754440.jpg', '', '林SOI44', 'yutrytrytryt123123123', 1, 'qwev', 'iuyiuyiuyiuyiuy23123123刚恢复发货', 'uploads/space/2015-05/89_1433037988.jpg', 1434124080, '潮州市饶平县青岚地质公园', '116.845071,23.738598', '艾尔123123', '', 0),
(2, 4, 4, '', '', '456', '445566', 1, '123', '112233', 'uploads/space/2015-06/95_1433567788.gif', 1435334280, '潮州市饶平县饶平大酒店', '116.993774,23.67356', '饶平大酒店', 'wer', 1),
(3, 3, 4, '', '', '', '', 0, '', '', 'uploads/space/2015-06/32_1433596263.gif', 0, '', '', '', 'werwer', 0),
(4, 4, 4, 'uploads/space/2015-06/28_1433647073.jpg', '', '男方', '男方描述', 0, '女方', '女方描述', 'uploads/space/2015-06/80_1433647104.jpg', 1433980800, '潮州市饶平县黄冈镇', '117.012021,23.67202', '饶平大酒店', '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_space_story`
--

CREATE TABLE IF NOT EXISTS `modoer_space_story` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  `title` varchar(32) NOT NULL,
  `content` text NOT NULL,
  `media_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- 转存表中的数据 `modoer_space_story`
--

INSERT INTO `modoer_space_story` (`id`, `uid`, `path`, `title`, `content`, `media_type`, `dateline`) VALUES
(1, 1, 'uploads/space/2015-05/87_1432791229.jpg', '', '1', 1, 1432804081),
(33, 2, 'uploads/space/2015-06/60_1433559344.jpg', '', '我想和你虚度时光，比如低头看鱼，比如把茶杯留在桌子上，离开，浪费它们好看的阴影。我还想连落日一起浪费，比如散步，一直消磨到星光满天。', 1, 1433559347),
(2, 1, 'uploads/space/2015-05/2_1432795727.jpg', '', '2', 1, 1432804081),
(3, 1, 'uploads/space/2015-05/95_1432795831.jpg', '', '3', 1, 1432804081),
(32, 2, 'uploads/space/2015-06/12_1433559336.jpg', '', '我想一直陪你走很久很久。久到那时的我都记不清你的名字，却还用宝贝一直称呼你。', 1, 1433559347),
(39, 4, 'uploads/space/2015-06/26_1433647187.jpg', '', '永恒的定义太多，对于我的永恒，只不过是见你的每个瞬间。', 1, 1433647189),
(37, 3, 'uploads/space/2015-06/92_1433598241.jpg', '', '其实全世界最美好的童话，就是一起度过柴米油盐的岁月。', 1, 1433598243),
(38, 4, 'uploads/space/2015-06/77_1433647180.jpg', '', '时间会告诉我们 ：简单的喜欢，最长远；平凡中的陪伴，最心安；懂你的人，最温暖；彼此相爱就是幸福。', 1, 1433647189),
(15, 1, 'uploads/space/2015-05/87_1432791229.jpg', '', '4', 1, 1432804081),
(31, 1, 'uploads/space/2015-05/88_1432804247.jpg', '', '9', 1, 1432804259),
(16, 1, 'uploads/space/2015-05/2_1432795727.jpg', '', '5', 1, 1432804081),
(30, 1, 'uploads/space/2015-05/62_1432804076.jpg', '', '8', 1, 1432804081),
(17, 1, 'uploads/space/2015-05/95_1432795831.jpg', '', '6', 1, 1432804081),
(29, 1, 'uploads/space/2015-05/46_1432796832.jpg', '', '7', 1, 1432804081),
(35, 3, 'uploads/space/2015-06/53_1433598192.jpg', '', '我本想把日子过成诗，时而简单，时而精致。不料却过成了我们的歌，时而不靠谱，时而不着调...但是，有什么关系呢，咱们来日方长，以后的日子慢慢过。', 1, 1433598208);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_space_theme`
--

CREATE TABLE IF NOT EXISTS `modoer_space_theme` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL,
  `pic1` varchar(255) CHARACTER SET ucs2 NOT NULL,
  `pic2` varchar(255) NOT NULL,
  `pic3` varchar(255) NOT NULL,
  `css` varchar(255) NOT NULL,
  `name` varchar(32) NOT NULL,
  `des` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `modoer_space_theme`
--

INSERT INTO `modoer_space_theme` (`id`, `listorder`, `thumb`, `pic1`, `pic2`, `pic3`, `css`, `name`, `des`, `content`, `dateline`, `status`) VALUES
(4, 1, 'uploads/space/2015-05/12_1432750447.jpg', '', '', '', '/templates/main/default/space/theme2.css', '主题2', '123', '', 1432977571, 1),
(3, 0, 'uploads/space/2015-05/10_1432754374.jpg', '', '', '', '/templates/main/default/space/theme1.css', '主题1', '123', '', 1432971414, 1);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_space_visit`
--

CREATE TABLE IF NOT EXISTS `modoer_space_visit` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '表主键ID',
  `space_uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '空间用户UID',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '访客UID',
  `username` char(30) NOT NULL DEFAULT '' COMMENT '访客昵称',
  `last_time` int(10) unsigned NOT NULL COMMENT '最近一次访问时间',
  `visit_count` int(10) unsigned NOT NULL COMMENT '总共访问次数',
  PRIMARY KEY (`id`),
  KEY `id_last_time` (`id`,`last_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `modoer_space_visit`
--

INSERT INTO `modoer_space_visit` (`id`, `space_uid`, `uid`, `username`, `last_time`, `visit_count`) VALUES
(1, 1, 3, 'fex3', 1433644304, 1);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_subject`
--

CREATE TABLE IF NOT EXISTS `modoer_subject` (
  `sid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `domain` char(50) NOT NULL DEFAULT '',
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `aid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sub_catids` varchar(255) NOT NULL DEFAULT '',
  `minor_catids` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `subname` varchar(60) NOT NULL DEFAULT '',
  `avgsort` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `sort1` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `sort2` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `sort3` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `sort4` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `sort5` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `sort6` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `sort7` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `sort8` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `avgprice` int(10) unsigned NOT NULL DEFAULT '0',
  `best` int(10) unsigned NOT NULL DEFAULT '0',
  `reviews` int(10) unsigned NOT NULL DEFAULT '0',
  `voice_reviews` int(10) unsigned NOT NULL DEFAULT '0',
  `guestbooks` int(10) unsigned NOT NULL DEFAULT '0',
  `pictures` int(10) unsigned NOT NULL DEFAULT '0',
  `pageviews` int(10) unsigned NOT NULL DEFAULT '1',
  `products` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `coupons` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `favorites` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `finer` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `owner` varchar(20) NOT NULL DEFAULT '',
  `cuid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `creator` varchar(20) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `video` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `map_lng` decimal(15,10) NOT NULL DEFAULT '0.0000000000',
  `map_lat` decimal(15,10) NOT NULL DEFAULT '0.0000000000',
  `map_geohash` char(20) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sid`),
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_subject`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjectapply`
--

CREATE TABLE IF NOT EXISTS `modoer_subjectapply` (
  `applyid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `applyname` varchar(100) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `pic` varchar(255) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `checker` varchar(30) NOT NULL DEFAULT '',
  `returned` text NOT NULL,
  PRIMARY KEY (`applyid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_subjectapply`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjectatt`
--

CREATE TABLE IF NOT EXISTS `modoer_subjectatt` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `attid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '',
  `att_catid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `attid` (`attid`,`sid`),
  KEY `sid` (`sid`,`attid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_subjectatt`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjectfield`
--

CREATE TABLE IF NOT EXISTS `modoer_subjectfield` (
  `fieldid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) NOT NULL DEFAULT '0',
  `tablename` varchar(25) NOT NULL DEFAULT '',
  `fieldname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `unit` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(255) NOT NULL DEFAULT '',
  `template` text NOT NULL,
  `note` mediumtext NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `listorder` smallint(5) NOT NULL DEFAULT '0',
  `allownull` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enablesearch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `iscore` tinyint(1) NOT NULL DEFAULT '0',
  `isadminfield` varchar(1) NOT NULL DEFAULT '0',
  `show_list` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `show_detail` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `show_side` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '侧边栏显示',
  `regular` varchar(255) NOT NULL DEFAULT '',
  `errmsg` varchar(255) NOT NULL DEFAULT '',
  `datatype` varchar(60) NOT NULL DEFAULT '',
  `config` text NOT NULL,
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `tablename` (`tablename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `modoer_subjectfield`
--

INSERT INTO `modoer_subjectfield` (`fieldid`, `modelid`, `tablename`, `fieldname`, `title`, `unit`, `style`, `template`, `note`, `type`, `listorder`, `allownull`, `enablesearch`, `iscore`, `isadminfield`, `show_list`, `show_detail`, `show_side`, `regular`, `errmsg`, `datatype`, `config`, `disable`) VALUES
(1, 1, 'subject', 'aid', '地区', '', '', '', '', 'area', 6, 0, 1, 2, '0', 0, 1, 0, '/[0-9]+/', '未选择地区', 'varchar(6)', 'a:1:{s:7:"default";s:1:"0";}', 0),
(2, 1, 'subject', 'catid', '分类', '', '', '', '', 'category', 1, 0, 1, 2, '0', 0, 1, 0, '/[0-9]+/', '未选择分类', 'smallint(5)', 'a:1:{s:7:"default";s:1:"0";}', 0),
(3, 1, 'subject', 'name', '名称', '', '', '', '', 'text', 2, 0, 1, 1, '0', 1, 1, 1, '', '', 'VARCHAR(255)', 'a:3:{s:3:"len";i:80;s:7:"default";s:0:"";s:4:"size";i:20;}', 0),
(4, 1, 'subject', 'subname', '子名称', '', '', '', '', 'text', 3, 1, 1, 1, '0', 1, 1, 1, '', '', 'VARCHAR(255)', 'a:3:{s:3:"len";i:80;s:7:"default";s:0:"";s:4:"size";i:20;}', 0),
(5, 1, 'subject', 'mappoint', '地图坐标', '', '', '', '', 'mappoint', 4, 0, 0, 1, '0', 0, 1, 0, '', '', 'varchar(60)', 'a:2:{s:7:"default";s:0:"";s:4:"size";s:2:"30";}', 0),
(6, 1, 'subject', 'video', '视频地址', '', '', '', '', 'video', 5, 1, 0, 1, '0', 1, 1, 0, '', '', 'varchar(255)', 'a:2:{s:7:"default";s:0:"";s:4:"size";s:2:"30";}', 0),
(7, 1, 'subject', 'description', '简介', '', '', '', '', 'text', 7, 1, 0, 1, '0', 1, 1, 1, '', '', 'VARCHAR(255)', 'a:3:{s:3:"len";i:255;s:7:"default";s:0:"";s:4:"size";i:60;}', 0),
(8, 1, 'subject', 'level', '等级', '', '', '', '', 'level', 92, 0, 1, 1, '1', 0, 1, 0, '/[0-9]+/', '未选择点评对象等级', 'tinyint(3)', 'a:1:{s:7:"default";i:0;}', 0),
(9, 1, 'subject', 'finer', '推荐度', '', '', '', '', 'numeric', 91, 1, 0, 1, '1', 0, 0, 0, '', '', 'SMALLINT(5)', 'a:4:{s:3:"min";i:0;s:3:"max";i:255;s:5:"point";s:1:"0";s:7:"default";s:1:"0";}', 0),
(10, 1, 'subject_shops', 'tel', '电话', '', '', '', '', 'text', 8, 1, 1, 1, '0', 1, 1, 1, '', '', 'VARCHAR(255)', 'a:3:{s:3:"len";i:80;s:7:"default";s:0:"";s:4:"size";i:20;}', 0),
(11, 1, 'subject_shops', 'address', '地址', '', '', '', '', 'text', 9, 1, 1, 1, '0', 1, 1, 1, '', '', 'VARCHAR(255)', 'a:3:{s:3:"len";i:80;s:7:"default";s:0:"";s:4:"size";i:20;}', 0),
(12, 1, 'subject_shops', 'content', '详细介绍', '', '', '', '', 'textarea', 90, 0, 0, 1, '0', 0, 1, 0, '', '', 'MEDIUMTEXT', 'a:6:{s:5:"width";s:3:"99%";s:6:"height";s:5:"200px";s:4:"html";s:1:"2";s:7:"default";s:0:"";s:11:"charnum_sup";i:0;s:11:"charnum_sub";i:1000;}', 0);

-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjectgourd`
--

CREATE TABLE IF NOT EXISTS `modoer_subjectgourd` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `status` enum('grow','harvest','finish') NOT NULL DEFAULT 'grow',
  `progress` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `total` smallint(5) unsigned NOT NULL DEFAULT '0',
  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `harvesttime` int(10) unsigned NOT NULL DEFAULT '0',
  `finishtime` int(10) unsigned NOT NULL DEFAULT '0',
  `users` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_subjectgourd`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjectimpress`
--

CREATE TABLE IF NOT EXISTS `modoer_subjectimpress` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL,
  `title` varchar(20) NOT NULL,
  `total` int(8) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`,`total`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_subjectimpress`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjectlink`
--

CREATE TABLE IF NOT EXISTS `modoer_subjectlink` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `flagid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `flag` varchar(30) NOT NULL DEFAULT '',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `flagid` (`flagid`,`flag`),
  KEY `sid` (`sid`,`flag`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_subjectlink`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjectlog`
--

CREATE TABLE IF NOT EXISTS `modoer_subjectlog` (
  `upid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(16) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `ismappoint` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `upcontent` mediumtext NOT NULL,
  `disposal` tinyint(1) NOT NULL DEFAULT '0',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `upremark` mediumtext NOT NULL,
  `disposaltime` int(10) NOT NULL DEFAULT '0',
  `update_point` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`upid`),
  KEY `sid` (`sid`),
  KEY `disposal` (`disposal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_subjectlog`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjectrelated`
--

CREATE TABLE IF NOT EXISTS `modoer_subjectrelated` (
  `related_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `fieldid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `r_sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`related_id`),
  KEY `fieldid` (`fieldid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_subjectrelated`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjectsetting`
--

CREATE TABLE IF NOT EXISTS `modoer_subjectsetting` (
  `sid` mediumint(8) NOT NULL DEFAULT '0',
  `variable` char(20) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  UNIQUE KEY `sid` (`sid`,`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_subjectsetting`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjectstyle`
--

CREATE TABLE IF NOT EXISTS `modoer_subjectstyle` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `templateid` smallint(5) NOT NULL DEFAULT '0',
  `buytime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_subjectstyle`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subjecttaoke`
--

CREATE TABLE IF NOT EXISTS `modoer_subjecttaoke` (
  `user_id` int(10) unsigned NOT NULL,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `nick` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_subjecttaoke`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_subject_shops`
--

CREATE TABLE IF NOT EXISTS `modoer_subject_shops` (
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tel` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `templateid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `forumid` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `modoer_subject_shops`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_taggroup`
--

CREATE TABLE IF NOT EXISTS `modoer_taggroup` (
  `tgid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `des` varchar(200) NOT NULL DEFAULT '',
  `sort` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `options` text NOT NULL,
  PRIMARY KEY (`tgid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `modoer_taggroup`
--

INSERT INTO `modoer_taggroup` (`tgid`, `name`, `des`, `sort`, `options`) VALUES
(1, '点评标签', '商铺标签说明', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_tags`
--

CREATE TABLE IF NOT EXISTS `modoer_tags` (
  `tagid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tagname` varchar(20) NOT NULL DEFAULT '',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagid`),
  KEY `total` (`total`),
  KEY `closed` (`closed`),
  KEY `tagname` (`tagname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_tags`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_tag_data`
--

CREATE TABLE IF NOT EXISTS `modoer_tag_data` (
  `stid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` int(10) unsigned NOT NULL DEFAULT '0',
  `tgid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tagname` varchar(25) NOT NULL DEFAULT '',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`stid`),
  KEY `tagid` (`tagid`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_tag_data`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_task`
--

CREATE TABLE IF NOT EXISTS `modoer_task` (
  `taskid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `enable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `taskflag` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `des` text NOT NULL,
  `icon` varchar(30) NOT NULL DEFAULT '',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `period` smallint(5) unsigned NOT NULL DEFAULT '0',
  `period_unit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pointtype` varchar(30) NOT NULL DEFAULT '',
  `point` int(10) unsigned NOT NULL DEFAULT '0',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `access_groupids` varchar(255) NOT NULL DEFAULT '',
  `applys` int(10) unsigned NOT NULL DEFAULT '0',
  `completes` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `reg_automatic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  PRIMARY KEY (`taskid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `modoer_task`
--

INSERT INTO `modoer_task` (`taskid`, `enable`, `taskflag`, `title`, `des`, `icon`, `starttime`, `endtime`, `period`, `period_unit`, `pointtype`, `point`, `access`, `access_groupids`, `applys`, `completes`, `listorder`, `reg_automatic`, `config`) VALUES
(1, 1, 'member:avatar', '上传头像', '注册用户上传一个自己的头像，即可获得积分奖励，赶快来参与吧！', '', 1315075860, 0, 0, 0, 'point1', 10, 0, '', 0, 0, 0, 0, ''),
(2, 1, 'item:favorite', '关注主题', '浏览主题，关注自己喜欢和关注的主题，从申请任务起，累计关注 3 个主题，即可获得积分奖励。', '', 1315075920, 0, 0, 0, 'point1', 6, 0, '', 0, 0, 0, 0, 'a:2:{s:3:"num";s:1:"3";s:10:"time_limit";s:0:"";}'),
(3, 1, 'review:flower', '赠送鲜花', '给你认为非常棒的点评信息赠送 3 朵鲜花，就可以获得积分奖励，本任务每周都可以重复申请一次。', '', 1315075980, 0, 1, 2, 'point1', 5, 0, '', 0, 0, 0, 0, 'a:2:{s:3:"num";s:1:"3";s:10:"time_limit";s:0:"";}'),
(4, 1, 'review:post', '添加主题点评', '申请本任务后，选择一个主题，发表自己对这些主题的点评信息，发表 1 篇，即可获得积分奖励，可重复申请。', '', 1315076040, 0, 1, 1, 'point1', 5, 1, '', 0, 0, 0, 0, 'a:2:{s:3:"num";s:1:"1";s:10:"time_limit";s:0:"";}');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_tasktype`
--

CREATE TABLE IF NOT EXISTS `modoer_tasktype` (
  `ttid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `flag` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `copyright` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`ttid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `modoer_tasktype`
--

INSERT INTO `modoer_tasktype` (`ttid`, `flag`, `title`, `copyright`, `version`) VALUES
(1, 'review:post', '点评类任务', 'MouferStudio', '1.0'),
(2, 'review:flower', '鲜花类任务', 'MouferStudio', '1.0'),
(3, 'member:avatar', '头像类任务', 'MouferStudio', '1.0'),
(4, 'item:subject', '主题类任务', 'MouferStudio', '1.0'),
(5, 'item:picture', '图片类任务', 'MouferStudio', '1.0'),
(6, 'item:favorite', '关注类任务', 'MouferStudio', '1.0'),
(7, 'article:post', '文章类任务', 'MouferStudio', '1.0');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_templates`
--

CREATE TABLE IF NOT EXISTS `modoer_templates` (
  `templateid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `directory` varchar(100) NOT NULL DEFAULT '',
  `copyright` varchar(45) NOT NULL DEFAULT '',
  `tpltype` varchar(15) NOT NULL DEFAULT '',
  `bind` tinyint(1) NOT NULL DEFAULT '0',
  `price` int(10) NOT NULL DEFAULT '0',
  `pointtype` enum('point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL DEFAULT 'point1',
  PRIMARY KEY (`templateid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `modoer_templates`
--

INSERT INTO `modoer_templates` (`templateid`, `name`, `directory`, `copyright`, `tpltype`, `bind`, `price`, `pointtype`) VALUES
(1, '默认模板', 'default', 'Moufer Studio', 'main', 1, 0, 'point1'),
(2, '商铺风格1', 'store_1', '', 'item', 0, 10, 'point1');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_travelers`
--

CREATE TABLE IF NOT EXISTS `modoer_travelers` (
  `tid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tuid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tusername` varchar(16) NOT NULL DEFAULT '',
  `addtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `uid` (`uid`,`addtime`),
  KEY `tuid` (`tuid`,`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_travelers`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_usergroups`
--

CREATE TABLE IF NOT EXISTS `modoer_usergroups` (
  `groupid` smallint(6) NOT NULL AUTO_INCREMENT,
  `grouptype` enum('member','special','system') DEFAULT 'member',
  `groupname` char(16) DEFAULT '',
  `point` int(10) NOT NULL DEFAULT '0',
  `color` varchar(7) NOT NULL DEFAULT '',
  `price` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `access` text NOT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `modoer_usergroups`
--

INSERT INTO `modoer_usergroups` (`groupid`, `grouptype`, `groupname`, `point`, `color`, `price`, `access`) VALUES
(1, 'system', '游客', 0, '#808080', 0, 'a:20:{s:16:"member_forbidden";s:1:"0";s:14:"tuan_post_wish";s:1:"0";s:23:"item_allow_edit_subject";s:1:"0";s:17:"item_create_album";s:1:"0";s:13:"item_subjects";s:2:"-1";s:13:"item_pictures";s:2:"-1";s:12:"article_post";s:1:"0";s:14:"article_delete";s:1:"0";s:12:"coupon_print";s:1:"0";s:16:"exchange_disable";s:1:"0";s:10:"review_num";s:0:"";s:13:"review_repeat";s:1:"0";s:11:"fenlei_post";s:1:"0";s:13:"fenlei_delete";s:1:"0";s:10:"party_post";s:1:"0";s:15:"comment_disable";s:1:"1";s:10:"card_apply";s:1:"0";s:8:"ask_post";s:1:"0";s:10:"ask_delete";s:1:"0";s:10:"ask_editor";s:1:"0";}'),
(2, 'system', '禁止访问', 0, '#808080', 0, 'a:5:{s:16:"member_forbidden";s:1:"1";s:13:"item_subjects";s:2:"-1";s:12:"item_reviews";s:2:"-1";s:13:"item_pictures";s:2:"-1";s:15:"comment_disable";s:1:"1";}'),
(3, 'system', '禁止发言', 0, '#808080', 0, 'a:5:{s:16:"member_forbidden";s:1:"0";s:13:"item_subjects";s:2:"-1";s:12:"item_reviews";s:2:"-1";s:13:"item_pictures";s:2:"-1";s:15:"comment_disable";s:1:"1";}'),
(4, 'system', '等待验证', 0, '#0bbfb9', 0, 'a:5:{s:16:"member_forbidden";s:1:"0";s:13:"item_subjects";s:2:"-1";s:12:"item_reviews";s:2:"-1";s:13:"item_pictures";s:2:"-1";s:15:"comment_disable";s:1:"1";}'),
(10, 'member', '注册会员', 0, '', 0, 'a:19:{s:16:"member_forbidden";s:1:"0";s:13:"item_subjects";s:0:"";s:13:"item_pictures";s:2:"10";s:17:"item_create_album";s:1:"1";s:14:"tuan_post_wish";s:1:"1";s:12:"article_post";s:1:"1";s:14:"article_delete";s:1:"1";s:12:"coupon_print";s:1:"1";s:16:"exchange_disable";s:1:"0";s:8:"ask_post";s:1:"1";s:10:"ask_delete";s:1:"1";s:10:"ask_editor";s:1:"1";s:10:"review_num";s:0:"";s:13:"review_repeat";s:1:"0";s:11:"fenlei_post";s:1:"0";s:13:"fenlei_delete";s:1:"0";s:10:"party_post";s:1:"1";s:15:"comment_disable";s:1:"0";s:10:"card_apply";s:1:"1";}'),
(12, 'member', '青铜会员', 100, '', 0, 'a:18:{s:16:"member_forbidden";s:1:"0";s:8:"ask_post";s:1:"1";s:10:"ask_delete";s:1:"1";s:10:"ask_editor";s:1:"1";s:11:"fenlei_post";s:1:"0";s:13:"fenlei_delete";s:1:"0";s:10:"party_post";s:1:"1";s:10:"review_num";s:0:"";s:13:"review_repeat";s:1:"0";s:13:"item_subjects";s:0:"";s:13:"item_pictures";s:1:"0";s:17:"item_create_album";s:1:"1";s:12:"article_post";s:1:"1";s:14:"article_delete";s:1:"1";s:12:"coupon_print";s:1:"1";s:16:"exchange_disable";s:1:"0";s:15:"comment_disable";s:1:"0";s:10:"card_apply";s:1:"1";}'),
(13, 'member', '白银会员', 500, '', 0, 'a:18:{s:16:"member_forbidden";s:1:"0";s:8:"ask_post";s:1:"1";s:10:"ask_delete";s:1:"1";s:10:"ask_editor";s:1:"1";s:11:"fenlei_post";s:1:"0";s:13:"fenlei_delete";s:1:"0";s:10:"party_post";s:1:"1";s:10:"review_num";s:0:"";s:13:"review_repeat";s:1:"0";s:13:"item_subjects";s:0:"";s:13:"item_pictures";s:2:"30";s:17:"item_create_album";s:1:"1";s:12:"article_post";s:1:"1";s:14:"article_delete";s:1:"1";s:12:"coupon_print";s:1:"1";s:16:"exchange_disable";s:1:"0";s:15:"comment_disable";s:1:"0";s:10:"card_apply";s:1:"0";}'),
(14, 'member', '黄金会员', 1000, '', 0, 'a:18:{s:16:"member_forbidden";s:1:"0";s:8:"ask_post";s:1:"1";s:10:"ask_delete";s:1:"1";s:10:"ask_editor";s:1:"1";s:11:"fenlei_post";s:1:"0";s:13:"fenlei_delete";s:1:"0";s:10:"party_post";s:1:"1";s:10:"review_num";s:0:"";s:13:"review_repeat";s:1:"0";s:13:"item_subjects";s:0:"";s:13:"item_pictures";s:0:"";s:17:"item_create_album";s:1:"1";s:12:"article_post";s:1:"1";s:14:"article_delete";s:1:"1";s:12:"coupon_print";s:1:"1";s:16:"exchange_disable";s:1:"0";s:15:"comment_disable";s:1:"0";s:10:"card_apply";s:1:"1";}'),
(15, 'special', 'VIP会员', 0, '#FF0000', 0, 'a:18:{s:16:"member_forbidden";s:1:"0";s:11:"fenlei_post";s:1:"0";s:13:"fenlei_delete";s:1:"0";s:10:"party_post";s:1:"1";s:10:"review_num";s:0:"";s:13:"review_repeat";s:1:"0";s:8:"ask_post";s:1:"1";s:10:"ask_delete";s:1:"1";s:10:"ask_editor";s:1:"1";s:13:"item_subjects";s:0:"";s:13:"item_pictures";s:3:"150";s:17:"item_create_album";s:1:"1";s:12:"article_post";s:1:"1";s:14:"article_delete";s:1:"1";s:12:"coupon_print";s:1:"1";s:16:"exchange_disable";s:1:"0";s:15:"comment_disable";s:1:"0";s:10:"card_apply";s:1:"1";}');

-- --------------------------------------------------------

--
-- 表的结构 `modoer_visitor`
--

CREATE TABLE IF NOT EXISTS `modoer_visitor` (
  `vid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) NOT NULL DEFAULT '0',
  `uid` mediumint(8) NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `total` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vid`),
  KEY `sid` (`sid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_visitor`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_weixin_converse`
--

CREATE TABLE IF NOT EXISTS `modoer_weixin_converse` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `hash` char(32) NOT NULL DEFAULT '',
  `openid` char(100) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_cmd` char(60) NOT NULL DEFAULT '',
  `city_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_weixin_converse`
--


-- --------------------------------------------------------

--
-- 表的结构 `modoer_words`
--

CREATE TABLE IF NOT EXISTS `modoer_words` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL DEFAULT '',
  `expression` varchar(255) NOT NULL DEFAULT '',
  `admin` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `modoer_words`
--

