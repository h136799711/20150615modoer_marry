<?php
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'card');

$category = $_G['loader']->variable('category','item');
if(isset($_GET['catid'])) {
    $catid = (int) $_GET['catid'];
}

$DC =& $_G['loader']->model('card:discount');

$where = array();
$where['city_id'] = array(0,$_CITY['aid']);
if($catid>0) $where['pid'] = $catid;
$where['available'] = 1;

$offset = $MOD['offset'] > 0 ? $MOD['offset'] : 10;
$start = get_start($_GET['page'], $offset);
$orderby = array('c.addtime'=>'DESC');

$select = 'c.*';
$subject_select = 's.city_id,s.catid,s.name,s.subname,s.thumb,s.pageviews,s.reviews,s.pictures,avgsort';

list($total,$list) = $DC->find($select,$where,$orderby,$start,$offset,TRUE,$subject_select);
if($total) {
    $multipage = mobile_page($total, $offset, $_GET['page'], url("card/mobile/do/list/catid/$catid/page/_PAGE_"));
}

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("card/mobile/do/list"));
if($catid>0 && isset($category[$catid])) {
    $urlpath[] = url_path($category[$catid]['name'], url("card/mobile/do/list/catid/$catid"));
}

$header_forward = U("mobile/index");
include mobile_template('card_list');
/* end */