<?php
if($_G['modoer']['version'] < 'MC 3.4') {
    redirect('本模块无法安装在 Modoer 3.4 以下版本。');
}

if($_G['modoer']['build'] < '20141023') {
    redirect('本模块不许安装在 Modoer 3.4 Build 20141023 以下版本。');
}

$_G['db']->from('dbpre_adv_place');
$_G['db']->where('name','商城_首页通栏');
$exists = $_G['db']->count() > 0;

if(!$exists) {
	$set = array();
	$set['templateid'] = 0;
	$set['name'] = '商城_首页通栏';
	$set['des'] = '产品商城首页模版，分类下面一栏960px';
	$set['template'] = "{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>\$ad[code]</div>\r\n{getempty(ad)}\r\n<div>960*60通栏广告位一</div>\r\n{/get}";
	$set['enabled'] = 'Y';
	$_G['db']->from('dbpre_adv_place')->set($set)->insert();
}

?>