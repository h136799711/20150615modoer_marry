DROP TABLE IF EXISTS modoer_group;
CREATE TABLE modoer_group (
  gid mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '小组ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态:-1未通过,0待审，1正常',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '关联主题ID',
  catid mediumint(8) NOT NULL DEFAULT '0' COMMENT '小组分类ID',
  groupname char(60) NOT NULL COMMENT '小组名称',
  topics mediumint(8) NOT NULL DEFAULT '0' COMMENT '话题数量',
  replies mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '回复量',
  members mediumint(8) NOT NULL DEFAULT '0' COMMENT '会员数量',
  createtime int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  uid mediumint(8) NOT NULL DEFAULT '0' COMMENT '组长uid',
  username char(30) NOT NULL COMMENT '组长昵称',
  lastpost int(10) NOT NULL DEFAULT '0' COMMENT '最后活动时间',
  icon char(255) NOT NULL COMMENT '图标',
  tags char(100) NOT NULL COMMENT '使用的标签',
  auth tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0:常规1:官方认证2:主题认证',
  finer tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '推荐',
  des text NOT NULL COMMENT '简介',
  PRIMARY KEY (gid),
  KEY groupname (groupname),
  KEY `status` (`status`,members),
  KEY sid (sid,`status`),
  KEY uid (uid,`status`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_category;
CREATE TABLE modoer_group_category (
  catid mediumint(8) NOT NULL AUTO_INCREMENT,
  pid mediumint(8) NOT NULL DEFAULT '0',
  listorder smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL,
  tags text NOT NULL COMMENT '内置tag',
  title varchar(255) NOT NULL,
  keywords varchar(255) NOT NULL,
  description varchar(255) NOT NULL,
  PRIMARY KEY (catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_member;
CREATE TABLE modoer_group_member (
  id mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  gid mediumint(8) NOT NULL DEFAULT '0' COMMENT '小组id',
  uid mediumint(8) NOT NULL DEFAULT '0' COMMENT '会员id',
  username varchar(30) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：-1禁言，0待审核，1正常',
  jointime int(10) NOT NULL DEFAULT '0' COMMENT '加入时间',
  lastpost int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最近发布时间',
  bantime int(10) unsigned NOT NULL DEFAULT '0' COMMENT '禁言时间(小于当前时间表示不禁言)',
  usertype tinyint(1) NOT NULL DEFAULT '10' COMMENT '会员类型(1:组长,2:管理员,10:成员）',
  posts mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '发帖量',
  applydes text NOT NULL COMMENT '申请理由',
  PRIMARY KEY (id),
  KEY gid (gid,uid),
  KEY `list` (gid,`status`,jointime)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_reply;
CREATE TABLE modoer_group_reply (
  rpid mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  tpid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '回应的话题id',
  gid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '小组ID',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '话题所属主题id',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '作者id',
  username varchar(20) NOT NULL COMMENT '作者昵称',
  dateline int(10) unsigned NOT NULL DEFAULT '10' COMMENT '发布时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，0未审核，1已审核',
  pictures text NOT NULL,
  content text NOT NULL COMMENT '回应内容',
  `source` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (rpid),
  KEY tpid (tpid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_setting;
CREATE TABLE modoer_group_setting (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  gid mediumint(8) NOT NULL DEFAULT '0',
  variable char(30) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (id),
  KEY gid (gid,variable)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_tag;
CREATE TABLE modoer_group_tag (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  tagname char(8) NOT NULL COMMENT '标签名字',
  gid mediumint(8) unsigned NOT NULL COMMENT '关联分类id',
  PRIMARY KEY (id),
  KEY tagname (tagname),
  KEY gid (gid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_topic;
CREATE TABLE modoer_group_topic (
  tpid mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  typeid mediumint(8) unsigned NOT NULL DEFAULT '0',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主题id',
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '话题标题',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '作者id',
  username varchar(20) NOT NULL DEFAULT '' COMMENT '作者昵称',
  pageview int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击次数',
  replies mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '回复数量',
  replytime int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后回应时间',
  top tinyint(1) NOT NULL DEFAULT '0',
  digest int(1) unsigned NOT NULL DEFAULT '0' COMMENT '精华',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，0未审核，1已审核',
  dateline int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  pictures text NOT NULL,
  content text NOT NULL COMMENT '话题内容',
  closed tinyint(1) NOT NULL DEFAULT '0',
  gid mediumint(8) NOT NULL DEFAULT '0' COMMENT '所属小组ID',
  `source` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (tpid),
  KEY `list` (gid,`status`,top,replytime),
  KEY uid (uid,`status`,dateline)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_topic_type;
CREATE TABLE modoer_group_topic_type (
  typeid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL DEFAULT '',
  gid mediumint(8) unsigned NOT NULL DEFAULT '0',
  listorder smallint(5) unsigned NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (typeid),
  KEY gid (gid,listorder)
) TYPE=MyISAM;