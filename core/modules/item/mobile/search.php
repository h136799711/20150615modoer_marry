<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$city_id = _get('city_id', null, MF_INT_KEY);
if($city_id>0) {
	$citys = $_G['loader']->variable('area');
    if(!$city = $citys[$city_id]) redirect('global_area_city_id_invalid');
    if(!$city['enabled']) redirect('global_area_city_disabled');
    init_city($city_id);
}

$q = _input('keyword', '', MF_TEXT);
if(($_GET['Pathinfo'] || $_GET['Rewrite']) && $q && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
	$q = charset_convert($q,'utf-8',$_G['charset']);
}
$q = str_replace(array("\r\n","\r","\n") ,'', _T($q));

$_G['db']->from('dbpre_subject');
$_G['db']->select('*');
$_G['db']->where('city_id', array(0,$_CITY['aid']));
$_G['db']->where_concat_like('name,subname', "%{$q}%");
$_G['db']->where('status', 1);

$multipage = '';
if($total = $_G['db']->count()) {
	$_G['db']->sql_roll_back('from,select,where');
	$orderby = array($post['ordersort']=>$post['ordertype']);
	$offset = 10;
	$start = get_start($_GET['page'], $offset);
	$_G['db']->order_by($orderby);
	$_G['db']->limit($start, $offset);
	$list = $_G['db']->get();
	if($total) {
		$multipage = mobile_page($total, $offset, $_GET['page'], url("item/mobile/do/search/keyword/$q/page/_PAGE_"));
	}
}

//显示模版
if($_G['in_ajax']) {
	include mobile_template('item_list_li');
	output();
}

$title = '搜索商户：'.$q;
include mobile_template('item_list');