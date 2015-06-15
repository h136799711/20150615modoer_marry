<?php
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'coupon');

if(isset($_GET['catid'])) $catid = (int) $_GET['catid'];
if($catid==0) unset($catid);

$category = $_G['loader']->variable('category',MOD_FLAG);

$urlpath = array();
$urlpath[] = url_path($MOD['name'], '');
if($catid > 0) {
    $urlpath[] = url_path(display('article:category',"catid/$catid"), '');
}
$subtitle = $_GET['keyword'] ? _T($_GET['keyword']) : lang('global_all');
$urlpath[] = url_path($subtitle, '');

$A =& $_G['loader']->model(':article');
$category = $A->variable('category');

//进入筛选页面
if ($op == 'filter') {
	include mobile_template('article_list_filter');
	exit;
}

$select = 'articleid,subject,a.dateline,pageview,comments,digg,uid,author,copyfrom,thumb,picture,introduce';
$orderby = array('dateline'=>'DESC');
$offset = $MOD['list_num'] > 0 ? $MOD['list_num'] : 10;
$start = get_start($_GET['page'],$offset);
$_GET['status'] = 1;
list($total, $list) = $A->search($select, $orderby, $start, $offset);
if($total) {
	$multipage = mobile_page($total, $offset, $_GET['page'], 
    	url("article/mobile/do/list/catid/$catid/keyword/$keyword/page/_PAGE_"));
}

if($_G['in_ajax']) {
	include mobile_template('article_list_li');
	output();
}

if (strposex($_SERVER['HTTP_REFERER'],'filter') || strposex($_SERVER['HTTP_REFERER'],'detail')){
    $header_forward = url("article/mobile/do/list");
} elseif(strposex($_SERVER['HTTP_REFERER'],'list')){
    $header_forward = url("mobile/index");
} elseif($_SERVER['HTTP_REFERER']) {
    $header_forward = $_SERVER['HTTP_REFERER'];
}

include mobile_template('article_list');

/* end */