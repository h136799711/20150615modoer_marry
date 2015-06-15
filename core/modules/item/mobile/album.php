<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$A =& $_G['loader']->model('item:album');
$catid = _input('catid',null,MF_INT_KEY);
$sid = _input('sid',null,MF_INT_KEY);

$category = $_G['loader']->variable('category','item');
$where = array();
if($sid > 0) {
    $S =& $_G['loader']->model('item:subject');
    if(!$subject = $S->read($sid)) redirect('item_empty');
    $where['a.sid'] = $sid;
} else {
    $where['a.city_id'] = array(0,$_CITY['aid']);
    if($catid > 0) $where['s.pid'] = (int) $catid;
}
$where['num'] = array('where_more',array(1));

$orderby = _cookie('list_display_item_album_orderby','normal','_T');
$orderby_arr = array(
    'normal' => array('albumid'=>'ASC'),
    'pageview' => array('pageview'=>'DESC'),
    'num' => array('num'=>'DESC'),
);
!isset($orderby_arr[$orderby]) and $orderby='normal';

$offset = 20;
$wf_count = 5;
$wf_offset = $offset * $wf_count;
$wf_page = _get('wfpage', 1, MF_INT_KEY);
$start = get_start(($wf_page-1) * $wf_count + $_GET['page'], $offset);

list($total, $list) = $A->find('a.*', $where, $orderby_arr[$orderby], $start, $offset, TRUE);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("item/mobile/do/album/sid/$sid/catid/$catid/wfpage/$wf_page/page/_PAGE_"));
    //$wf_multipage = multi($total, $wf_offset, $wf_page, url("mobile/album/catid/$catid/wfpage/_PAGE_"));
    $wf_multipage = mobile_page($total, $wf_offset, $wf_page, url("item/mobile/do/album/sid/$sid/catid/$catid/wfpage/_PAGE_"));
}

if(_input('waterfall') == 'Y' && $_G['in_ajax']) {
    include mobile_template('item_album_li');
    output();
}
if(!$subject) $header_title = "商铺相册";
include mobile_template('item_album');
?>