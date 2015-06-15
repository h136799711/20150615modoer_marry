<?php
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'coupon');

if(isset($_GET['catid'])) $catid = (int) $_GET['catid'];
if($catid==0) unset($catid);

$category = $_G['loader']->variable('category',MOD_FLAG);

$urlpath = array();
$urlpath[] = url_path($MOD['name'], '');

//进入筛选页面
if ($op == 'filter') {
	include mobile_template('coupon_list_filter');
	exit;
}

//显示模版
$CO = $_G['loader']->model(':coupon');
$where = array();
$where['c.city_id'] = array(0, $_CITY['aid']);
if($catid>0 && isset($category[$catid])) {
	$where['c.catid'] = $catid;
}
if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
	$_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}
$keyword = _get('keyword','','_T');
if($keyword) {
	$where['subject'] = array('where_like',array("%$keyword%"));
	$urlpath[] = url_path($keyword, url("coupon/mobile/do/list/catid/$catid/keyword/$keyword"));
} else {
	unset($keyword);
}

$where['c.status'] = 1;
$offset = $MOD['listnum'] > 0 ? $MOD['listnum'] : 10;
$start = get_start($_GET['page'], $offset);
$select = 'c.couponid,c.city_id,c.catid,c.sid,c.thumb,c.subject,c.starttime,c.endtime,c.des,c.comments,c.pageview,c.effect1';
$orderbylist = array('effect1'=>'DESC', 'pageview'=>'DESC', 'dateline'=>'DESC', 'comments'=>'DESC');
$orderby = _cookie('list_display_coupon_orderby', 'dateline', '_T');
!in_array($orderby,array_keys($orderbylist)) && $orderby = 'dateline';

list($total, $list) = $CO->find($select, $where, array('c.'.$orderby => $orders[$orderby]), $start, $offset, TRUE, 's.name,s.subname,s.reviews');
if($total) $multipage = mobile_page($total, $offset, $_GET['page'], url("coupon/mobile/do/list/catid/$catid/keyword/$keyword/page/_PAGE_"));


if($_G['in_ajax']) {
	include mobile_template('coupon_list_li');
	output();
}

$active = array();
$active['catid'][(int)$catid] = ' class="selected"';
$active['orderby'][$orderby] = ' class="selected"';

$header_forward = url("mobile/index");
include mobile_template('coupon_list');

/* end */