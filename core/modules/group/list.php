<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$urlpath = array();
$urlpath[] = url_path("列表",url("group/list"));

$keywords = _get('keyword', '', MF_TEXT);
$pr = _get('pr',null,MF_TEXT);

if($pr) list($auth, $finer, $area)=explode('_', $pr);
$auth = (int)$auth;
$finer = (int)$finer;
$area = (int)$area;
$tag = _get('tag','',MF_TEXT);

$G = $_G['loader']->model(':group');
$where = array('status'=>1);
if($auth) $where['auth']=1;
if($finer) $where['finer']=1;
$where['city_id'] = $area ? $area : array(0, $_CITY['aid']);
if($keywords) $where['groupname']=array('where_like', array("%{$keywords}%"));
$orderby = array('members'=>'DESC');
$offset = 10;
$start = get_start($_GET['page'], $offset);
$select='*';
if($tag!='') {
	$where['tagname'] = $tag;
	$urlpath[] = url_path($tag, url("group/list"));
    list($total, $list) = $G->find_tag($select, $where, $orderby, $start, $offset);
} else {
    list($total, $list) = $G->find($select, $where, $orderby, $start, $offset, true);
}
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("group/list/tag/$tag/pr/$pr/page/_PAGE_"));
}

if($tag) {
    $cate = $_G['db']->from('dbpre_group_category')->where_like('tags',"%|{$tag}|%")->get_one();
}

if($cate) {
	$SEO->tags->category_name = $tag;
	$SEO->tags->category_title = $cate['title'];
	$SEO->tags->category_keywords = $cate['keywords'];
	$SEO->tags->category_description = $cate['description'];
}
//解析seo设置,赋值
$_HEAD['title'] = $SEO->pares($tag?'list_tag':'list')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

include template('group_list');
?>