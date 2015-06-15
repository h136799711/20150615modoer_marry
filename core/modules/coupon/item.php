<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'coupon');

$CO = $_G['loader']->model(':coupon');

$sid = (int) $_GET['sid'];

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("coupon/index"));

//取得主题信息
$I =& $_G['loader']->model('item:subject');
if(!$subject = $I->read($sid)) redirect('item_empty');
$subject_field_table_tr = $I->display_sidefield($subject);

$urlpath[] = url_path(trim($subject['name'].$subject['subname']), url("coupon/list/sid/$sid"));
//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','coupon');

$where = array();
$where['c.sid'] = $sid;
$category = $_G['loader']->variable('category',MOD_FLAG);
if(is_numeric($_GET['catid']) && $_GET['catid'] > 0) {
    $catid = (int) $_GET['catid'];
    $where['c.catid'] = $catid;
    $urlpath[] = url_path($category[$catid][name], url("coupon/list/catid/$catid"));
}
$where['c.status'] = 1;
$offset = $MOD['listnum'] > 0 ? $MOD['listnum'] : 10;
$start = get_start($_GET['page'], $offset);
$select = 'c.couponid,c.catid,c.sid,c.thumb,c.subject,c.starttime,c.endtime,c.des,c.comments,c.pageview,c.effect1';
$orders = array('effect1'=>'DESC','pageview'=>'DESC','dateline'=>'DESC','comments'=>'DESC');
$_GET['order'] = isset($_GET['order']) && in_array($_GET['order'],array_keys($orders)) ? $_GET['order'] : 'dateline';
list($total,$list) = $CO->find($select, $where,array('c.'.$_GET['order']=>$orders[$_GET['order']]),$start,$offset,TRUE,'s.name,s.subname,s.reviews');
if($total) $multipage = multi($total, $offset, $_GET['page'], url('coupon/list/sid/'.$sid.'/catid/'.$catid.'/order/'.$_GET['order'].'/page/_PAGE_'));

$active = array();
$catid>0 && $active['catid'][$catid] = ' class="selected"';
$_GET['order'] && $active['order'][$_GET['order']] = ' class="selected"';

$_HEAD['keywords'] = ($subject ? ($subject[name]  . ',' . $subject[subname] . ',') : '') . $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

$scategory = $I->get_category($subject['catid']);
if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
    $subject['templateid'] = $scategory['config']['templateid'];
}

//页面seo标签
$SEO->tags->subject_name    = $subject['name'].($subject['subname']?"({$subject['subname']})":'');
$SEO->pares('item_list');

//解析seo设置赋值
$_HEAD['title']         = $SEO->title;
$_HEAD['keywords']      = $SEO->keywords;
$_HEAD['description']   = $SEO->description;

if($subject['templateid'] && S('coupon:use_itemtpl')) {
    include template('coupon_list', 'item', $subject['templateid']);
} else {
    include template('coupon_list');
}
?>