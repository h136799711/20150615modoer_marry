<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'coupon');

if(isset($_GET['catid'])) $catid = (int) $_GET['catid'];
if($catid==0) unset($catid);

$category = $_G['loader']->variable('category',MOD_FLAG);

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("coupon/index"));

$CO = $_G['loader']->model(':coupon');
$where = array();
$where['c.city_id'] = array(0,$_CITY['aid']);
if($catid>0&&isset($category[$catid])) {
	$where['c.catid'] = $catid;
	$urlpath[] = url_path($category[$catid][name], url("coupon/index/catid/$catid"));
}
if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
	$_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}
$keyword = _get('keyword','','_T');
if($keyword) {
	$where['subject'] = array('where_like',array("%$keyword%"));
	$urlpath[] = url_path($keyword, url("product/index/catid/$catid/keyword/$keyword"));
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
list($total, $list) = $CO->find($select, $where, array('c.'.$orderby => $orderbylist[$orderby]), $start, $offset, TRUE, 's.name,s.subname,s.reviews');
if($total) $multipage = multi($total, $offset, $_GET['page'], url("coupon/index/catid/$catid/keyword/$keyword/page/_PAGE_"));

$active = array();
$active['catid'][(int)$catid] = ' class="selected"';
$active['orderby'][$orderby] = ' class="selected"';

$SEO->tags->category_name = display('coupon:category', "catid/$catid");
if($catid>0) {
	//列表页
	$SEO->pares('list');
} else {
	//首页
	$SEO->pares('index');
}
//解析seo设置赋值
$_HEAD['title'] = $SEO->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

include template('coupon_index');
?>