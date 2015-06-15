DROP TABLE IF EXISTS modoer_product;
CREATE TABLE modoer_product (
  pid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  modelid smallint(5) unsigned NOT NULL DEFAULT '0',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  city_id mediumint(8) unsigned NOT NULL DEFAULT '0',
  pgcatid mediumint(8) unsigned NOT NULL DEFAULT '0',
  gcatid mediumint(8) unsigned NOT NULL DEFAULT '0',
  catid mediumint(8) unsigned NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  `subject` varchar(60) NOT NULL DEFAULT '',
  shape_code char(15) NOT NULL DEFAULT '',
  brief_code char(15) NOT NULL DEFAULT '',
  price decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  user_price varchar(255) NOT NULL DEFAULT '',
  usergroup varchar(255) NOT NULL DEFAULT '',
  grade smallint(5) NOT NULL DEFAULT '0',
  pageview mediumint(8) unsigned NOT NULL DEFAULT '0',
  comments mediumint(8) NOT NULL DEFAULT '0',
  picture varchar(255) NOT NULL DEFAULT '',
  pictures text NOT NULL,
  thumb varchar(255) NOT NULL DEFAULT '',
  description varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  listorder smallint(5) unsigned NOT NULL DEFAULT '0',
  stock int(11) unsigned NOT NULL DEFAULT '0',
  p_style tinyint(1) unsigned NOT NULL DEFAULT '0',
  giveintegral int(11) unsigned NOT NULL DEFAULT '0',
  integral int(10) unsigned NOT NULL DEFAULT '0',
  promote decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  promote_start int(10) unsigned NOT NULL DEFAULT '0',
  promote_end int(10) unsigned NOT NULL DEFAULT '0',
  is_on_sale tinyint(1) unsigned NOT NULL DEFAULT '0',
  is_cod tinyint(1) unsigned NOT NULL DEFAULT '0',
  is_freight tinyint(1) unsigned NOT NULL DEFAULT '1',
  freight tinyint(1) unsigned NOT NULL DEFAULT '1',
  free_shipping tinyint(1) unsigned NOT NULL DEFAULT '0',
  tag_keyword varchar(255) NOT NULL DEFAULT '',
  last_update int(10) unsigned NOT NULL DEFAULT '0',
  sales mediumint(8) unsigned NOT NULL DEFAULT '0',
  finer tinyint(1) unsigned NOT NULL DEFAULT '0',
  weight int(10) unsigned NOT NULL DEFAULT '0',
  shipping varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (pid),
  KEY last_update (last_update),
  KEY newproduct (`status`,is_on_sale,city_id,dateline),
  KEY gcatid (`status`,is_on_sale,gcatid,city_id,sales),
  KEY pgcatid (`status`,is_on_sale,pgcatid,city_id,sales),
  KEY finer (finer,pgcatid),
  KEY promote (promote,promote_start,promote_end,city_id),
  KEY catid (sid,catid,sales),
  KEY shape_code (shape_code),
  KEY brief_code (brief_code)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_productatt;
CREATE TABLE modoer_productatt (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  pid mediumint(8) unsigned NOT NULL DEFAULT '0',
  attid mediumint(8) unsigned NOT NULL DEFAULT '0',
  att_catid mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY sid (pid,attid),
  KEY pid (pid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_buyattr;
CREATE TABLE modoer_product_buyattr (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` char(60) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY pid (pid,listorder)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_cart;
CREATE TABLE modoer_product_cart (
  cid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  cartid varchar(80) NOT NULL DEFAULT '',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  pid mediumint(8) unsigned NOT NULL DEFAULT '0',
  pname varchar(255) NOT NULL DEFAULT '',
  uid tinyint(1) unsigned NOT NULL DEFAULT '0',
  p_image varchar(255) NOT NULL DEFAULT '',
  p_style tinyint(1) unsigned NOT NULL DEFAULT '1',
  quantity int(10) unsigned NOT NULL DEFAULT '0',
  price decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  buyattr text NOT NULL,
  overdue int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (cid),
  KEY pid (pid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_category;
CREATE TABLE modoer_product_category (
  catid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  listorder smallint(5) NOT NULL DEFAULT '0',
  num mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (catid),
  KEY sid (sid,catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_data;
CREATE TABLE modoer_product_data (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  pid mediumint(8) unsigned NOT NULL,
  fieldid mediumint(8) unsigned NOT NULL,
  fieldname varchar(60) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY pid (pid,fieldid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS modoer_product_field;
CREATE TABLE modoer_product_field (
  pid mediumint(8) unsigned NOT NULL,
  use_delivery_time TINYINT(1) unsigned NOT NULL DEFAULT '0',
  use_dispatch_time TINYINT(1) unsigned NOT NULL DEFAULT '0',
  cod_price decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  freight_price_snail decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  freight_price_exp decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  freight_price_ems decimal(9,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (pid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_gcategory;
CREATE TABLE modoer_product_gcategory (
  catid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  pid mediumint(8) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL DEFAULT '',
  listorder smallint(5) unsigned NOT NULL DEFAULT '0',
  enabled tinyint(1) unsigned NOT NULL DEFAULT '1',
  subcats varchar(255) NOT NULL DEFAULT '',
  nonuse_subcats varchar(255) NOT NULL DEFAULT '',
  attcat varchar(255) NOT NULL DEFAULT '',
  modelid smallint(5) unsigned NOT NULL DEFAULT '0',
  title varchar(500) NOT NULL DEFAULT '',
  keywords varchar(500) NOT NULL DEFAULT '',
  description varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (catid),
  KEY pid (pid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_model;
CREATE TABLE modoer_product_model (
  modelid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  tablename varchar(20) NOT NULL DEFAULT '',
  description varchar(255) NOT NULL DEFAULT '',
  usearea tinyint(1) NOT NULL DEFAULT '0',
  item_name varchar(200) NOT NULL DEFAULT '',
  item_unit varchar(200) NOT NULL DEFAULT '',
  tplname_list varchar(200) NOT NULL DEFAULT '',
  tplname_detail varchar(200) NOT NULL DEFAULT '',
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (modelid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_order;
CREATE TABLE modoer_product_order (
  orderid int(10) unsigned NOT NULL AUTO_INCREMENT,
  ordersn varchar(20) NOT NULL DEFAULT '',
  orderstyle tinyint(1) unsigned NOT NULL DEFAULT '1',
  sellerid int(10) unsigned NOT NULL DEFAULT '0',
  sellername varchar(100) DEFAULT '',
  buyerid int(10) unsigned NOT NULL DEFAULT '0',
  buyername varchar(100) DEFAULT '',
  buyeremail varchar(60) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  payment_id int(10) unsigned DEFAULT '0',
  paymentname varchar(100) DEFAULT '',
  paymentcode varchar(20) NOT NULL DEFAULT '',
  paytime int(10) unsigned DEFAULT '0',
  paymessage varchar(255) NOT NULL DEFAULT '',
  shiptime int(10) unsigned DEFAULT '0',
  invoice_no varchar(255) DEFAULT '',
  kdcom varchar(255) NOT NULL DEFAULT 'other',
  finishedtime int(10) unsigned NOT NULL DEFAULT '0',
  goods_amount decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  order_amount decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  integral_amount decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  integral int(10) unsigned NOT NULL DEFAULT '0',
  integral_pointtype varchar(15) NOT NULL DEFAULT '',
  brokerage decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  is_serial tinyint(1) unsigned NOT NULL DEFAULT '0',
  is_cod tinyint(1) unsigned NOT NULL DEFAULT '0',
  is_offline_pay enum('null','owner','admin') NOT NULL DEFAULT 'null',
  offline_pay_role varchar(20) NOT NULL,
  remark varchar(255) NOT NULL DEFAULT '',
  amount_changed smallint(5) unsigned NOT NULL DEFAULT '0',
  delivery_time int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (orderid),
  KEY ordersn (ordersn,sellerid),
  KEY sellername (sellername),
  KEY buyername (buyername),
  KEY addtime (sid,addtime),
  KEY paytime (sid,paytime),
  KEY sid (sid,`status`,orderid),
  KEY buyerid (buyerid,`status`,orderid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_orderextm;
CREATE TABLE modoer_product_orderextm (
  orderid int(10) unsigned NOT NULL DEFAULT '0',
  username varchar(60) NOT NULL DEFAULT '',
  address varchar(255) DEFAULT '',
  zipcode int(6) unsigned DEFAULT '0',
  mobile varchar(60) DEFAULT '',
  shipid int(10) unsigned DEFAULT '10',
  shipname varchar(100) DEFAULT '',
  shipfee decimal(9,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (orderid),
  KEY username (username)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_ordergoods;
CREATE TABLE modoer_product_ordergoods (
  gid int(10) unsigned NOT NULL AUTO_INCREMENT,
  orderid int(10) unsigned NOT NULL DEFAULT '0',
  pid int(10) unsigned NOT NULL DEFAULT '0',
  pname varchar(255) NOT NULL DEFAULT '',
  price decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  quantity int(10) unsigned NOT NULL DEFAULT '1',
  buyattr text NOT NULL,
  goods_image varchar(255) DEFAULT '',
  commented tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (gid),
  KEY orderid (orderid,pid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_orderlog;
CREATE TABLE modoer_product_orderlog (
  logid int(10) unsigned NOT NULL AUTO_INCREMENT,
  orderid int(10) unsigned NOT NULL DEFAULT '0',
  operator varchar(60) NOT NULL DEFAULT '',
  order_status varchar(60) NOT NULL DEFAULT '',
  changed_status varchar(60) NOT NULL DEFAULT '',
  remark varchar(255) DEFAULT '',
  log_time int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (logid),
  KEY orderid (orderid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_serial;
CREATE TABLE modoer_product_serial (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  pid mediumint(8) unsigned NOT NULL DEFAULT '0',
  oid mediumint(8) NOT NULL DEFAULT '0',
  uid mediumint(8) NOT NULL DEFAULT '0',
  `serial` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  sendtime int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY pid (pid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_product_shipping;
CREATE TABLE modoer_product_shipping (
  shipid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  shipname varchar(100) NOT NULL DEFAULT '',
  shipdesc varchar(255) NOT NULL DEFAULT '',
  price decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  enabled tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (shipid),
  KEY uid (sid)
) TYPE=MyISAM;
