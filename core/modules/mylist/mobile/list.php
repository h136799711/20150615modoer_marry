<?php
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'mylist');

$urlpath = array();
$urlpath[] = url_path($MOD['name']);

$lo = _get('lo','time');
$lo != 'pv' && $lo = 'time';
$lo_arr = array(
	'pv' => array('pageviews'=>'DESC'),
	'time' => array('modifytime'=>'DESC'),
);
$SEO->tags->lo_name = lang('mylist_lo_'.$lo);

$offset = 10; //每页数量
$start = get_start($_GET['page'], $offset);

$db = $_G['db'];
$total = 0;

if($catid = _get('catid','', MF_INT_KEY)) {
	$params = "/catid/$catid";
	$seo_page = 'list';
	if($cate = $_G['loader']->model('mylist:category')->read($catid)) {
		$SEO->tags->category = $cate['name'];
		$urlpath[] = url_path($cate['name']);
		$total = $db->from('dbpre_mylist','m')->use_index('city_time')
			->where(array('city_id'=>$_CITY['aid'],'catid'=>$catid,'num'=>array('where_more',array(1))))->count();
		if($total>0) $list = $db->sql_roll_back('from,index,where')->from('dbpre_mylist')
			->order_by($lo_arr[$lo])->limit($start, $offset)->get();
	}
} else {
	$total = $db->from('dbpre_mylist')->use_index('city_time')
		->where(array('city_id'=>(int)$_CITY['aid'],'num'=>array('where_not_equal',array(0))))->count();
	if($total>0) $list = $db->sql_roll_back('from,index,where')
			->order_by($lo_arr[$lo])->limit($start, $offset)->get();
}

if($total > $offset) {
	$multipage = mobile_page($total, $offset, $_GET['page'], url("mylist/mobile/do/list/lo/{$lo}{$params}/page/_PAGE_"));
}

if($_G['in_ajax']) {
	include mobile_template('mylist_list_li');
	output();
}

$urlpath[] = url_path(lang('mylist_lo_'.$lo));

//解析seo设置赋值
$_HEAD['title'] = $SEO->pares(isset($seo_page)?$seo_page:'index')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

//$header_forward = U('mylist/mobile/do/list');
include mobile_template('mylist_list');