<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'mylist');

$lo = _get('lo','time');
$lo != 'pv' && $lo = 'time';
$lo_arr = array(
	'pv' => array('pageviews'=>'DESC'),
	'time' => array('modifytime'=>'DESC'),
);
$SEO->tags->lo_name = lang('mylist_lo_'.$lo);

$offset = 20; //每页数量
$start = get_start($_GET['page'], $offset);

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("mylist/index"));

$db = $_G['db'];
$total=0;
if($catid = _get('catid','', MF_INT_KEY)) {
	$params = "/catid/$catid";
	$seo_page = 'list';
	if($cate = $_G['loader']->model('mylist:category')->read($catid)) {
		$SEO->tags->category_name = $cate['name'];
		$urlpath[] = url_path($cate['name']);
		$total = $db->from('dbpre_mylist','m')->use_index('city_time')
			->where(array('city_id'=>$_CITY['aid'],'catid'=>$catid,'num'=>array('where_more',array(1))))->count();
		if($total>0) $list = $db->sql_roll_back('from,index,where')->from('dbpre_mylist')
			->order_by($lo_arr[$lo])->limit($start, $offset)->get();
	}
} elseif($tagid = _get('tagid','', MF_INT_KEY)) {
	$params = "/tagid/$tagid";
	$seo_page = 'tag';
	if($tag = $_G['loader']->model('mylist:tag')->read($tagid)) {
		$SEO->tags->tag_name = $tag['name'];
		$urlpath[] = url_path($tag['name']);
		$total = $db->from('dbpre_mylist_tag_data','td')->where(array('td.city_id'=>$_CITY['aid'],'tag_id'=>$tagid))->count();
		if($total>0) $list = $db->sql_roll_back('where')->join('dbpre_mylist_tag_data','td.mylist_id','dbpre_mylist','m.id')
			->select('m.*')->order_by($lo_arr[$lo])->limit($start, $offset)->get();
	}
} elseif($uid = _get('uid', '', MF_INT_KEY)) {
	$params = "/uid/$uid";
	if($member = $_G['loader']->model(':member')->read($uid)) {
		$SEO->tags->tag_name = $member['username'];
		$urlpath[] = url_path($member['username'].'的榜单');
		$total = $db->from('dbpre_mylist','m')->where(array('uid'=>$uid))->count();
		if($total>0) $list = $db->sql_roll_back('from,where')->from('dbpre_mylist')
			->order_by($lo_arr[$lo])->limit($start, $offset)->get();
	}
} elseif($k = _get('k','', MF_TEXT)) {
	$params = "/k/$k";
	$seo_page = 'search';
	$db->where('city_id', $_CITY['aid']);
	$k = explode(" ", preg_replace("/\s+/", " ", $k));
	if($k) {
		$SEO->tags->keyword = implode(' ', $k);
		$urlpath[] = url_path('包含关键字'.implode(',',$k).'的榜单');
		$x = count($k);
		if($x>5) redirect('对不起，关键字输入过多，无法完成搜索。');
		foreach ($k as $i => $v) {
			if( $x > 1 && !$i) {
				$kh = "(";
			} elseif($x > 1 && $i >= count($k)-1) {
				$kh = ")";
			} else {
				$kh = "";
			}
			$db->where_like('title', "%{$v}%", $i?"OR":"AND", $kh);
		}
		$total = $db->from('dbpre_mylist','m')->count();
		if($total>0) $list = $db->sql_roll_back('from,where')
			->order_by($lo_arr[$lo])->limit($start, $offset)->get();
	}
	
} else {
	$urlpath[] = url_path("{$_CITY['name']}的全部榜单");
	$total = $db->from('dbpre_mylist')->use_index('city_time')
		->where(array('city_id'=>(int)$_CITY['aid'],'num'=>array('where_not_equal',array(0))))->count();
	if($total>0) $list = $db->sql_roll_back('from,index,where')
			->order_by($lo_arr[$lo])->limit($start, $offset)->get();
}

if($total > $offset) {
	$multipage = multi($total, $offset, $_GET['page'], url("mylist/index/lo/{$lo}{$params}/page/_PAGE_"));
}

//解析seo设置赋值
$_HEAD['title'] = $SEO->pares(isset($seo_page)?$seo_page:'index')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

$active['catid'][$catid] = ' class="selected"';
$active['lo'][$lo] = ' class="selected"';
include template('mylist_index');
?>