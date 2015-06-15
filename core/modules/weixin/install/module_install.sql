DROP TABLE IF EXISTS modoer_weixin_converse;
CREATE TABLE modoer_weixin_converse (
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
) TYPE=MyISAM;