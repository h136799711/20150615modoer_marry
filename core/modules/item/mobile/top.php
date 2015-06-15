<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$catid = isset($_GET['catid']) ? (int)$_GET['catid'] : (int)$MOD['pid'];
!$catid and location(url('item/mobile/do/category/p/top'));

//实例化主题类
$I =& $_G['loader']->model('item:subject');
$I->get_category($catid);
if(!$pid = $I->category['catid']) {
    location(url('mobile/category'));
}

//载入配置信息
$catcfg =& $I->category['config'];
$modelid = $I->category['modelid'];
$rogid = $I->category['review_opt_gid'];

//载入模型
$model = $I->variable('model_' . $modelid);
//载入点评选项
$reviewpot = $_G['loader']->variable('opt_' . $rogid, 'review');
$reviewcfg = $_G['loader']->variable('config','review');


$list = datacall_get(
	'top',
	array(
		'city_id'	=> $modelid['usearea']?$_CITY['aid']:0,
		'pid'		=> $catid,
		'field'		=> "avgsort",
		'orderby'	=> "avgsort DESC",
		'rows'		=> 10,
	),
	'item'
);
if($_G['in_ajax']) {
	include mobile_template('item_top_loop');
	output();
}

include mobile_template('item_top');