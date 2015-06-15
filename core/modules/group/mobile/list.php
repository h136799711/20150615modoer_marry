<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$pr=_get('pr','',MF_TEXT);
if($pr) list($auth, $finer, $area)=explode('_', $pr);
$auth=(int)$auth;
$finer=(int)$finer;
$area = (int)$area;
$tag = _get('tag','',MF_TEXT);

$G = $_G['loader']->model(':group');
$where = array();
$where['status'] = 1;
if($auth)$where['auth']=1;
if($finer)$where['finer']=1;
$where['city_id'] = $area ? $area : array(0, $_CITY['aid']);
$orderby = array('members'=>'DESC');//array('topics'=>'DESC');
$offset = 10;
$start = get_start($_GET['page'], $offset);
if($tag!='') {
	$where['tagname'] = $tag;
    list($total, $list) = $G->find_tag($select, $where, $orderby, $start, $offset);
} else {
    list($total, $list) = $G->find($select, $where, $orderby, $start, $offset, true);
}
if($total > 0) {
    $multipage = mobile_page($total, $offset, $_GET['page'], url("group/mobile/do/list/page/_PAGE_"));
}

//AJAX方式加载下一页
if($_G['in_ajax']) {
	include mobile_template('group_list_li');
	output();
}

$_HEAD['title'] = $MOD['title']?$MOD['title']:lang('group_title');
$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

include mobile_template('group_list');