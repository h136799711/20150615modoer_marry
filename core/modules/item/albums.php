<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item_album');

$A =& $_G['loader']->model('item:album');
$op = _input('op',null,'_T');

$catid = _input('catid',null,MF_INT_KEY);
$urlpath = array();

if(($_GET['Pathinfo']||$_GET['Rewrite']) && $_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
    $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
}

$mode_arr = array('normal','waterfall');
$mode = _cookie('list_display_item_album_mode', $MOD['item_album_mode'], '_T');
if(is_spider()) {
    $mode = 'normal';
} else {
    $mode = in_array($mode, $mode_arr) ? $mode : 'normal';
}

$category = $_G['loader']->variable('category','item');
$urlpath[] = url_path(lang('item_album_title'), url("item/album"));
if($catid > 0) $urlpath[] = url_path($category[$catid]['name'], url("item/allpic/catid/$pid"));

$where = array();
$keyword = _get('keyword',null,'_T');
if($keyword) $where['a.name'] = array('where_like',array("%$keyword%"));
$where['a.city_id'] = array(0,$_CITY['aid']);
if($catid > 0) $where['s.pid'] = (int) $catid;
$where['num'] = array('where_more',array(1));

$orderby_arr    = array(
    'normal'    => array('albumid'  =>'ASC'),
    'pageview'  => array('pageview' =>'DESC'),
    'num'       => array('num'      =>'DESC'),
);
$orderby = _cookie('list_display_item_album_orderby', $MOD['item_album_order'], '_T');
!isset($orderby_arr[$orderby]) and $orderby = 'normal';

$offset = 16;
if($mode == 'waterfall') {
    //瀑布流
    $wf_count = 5;
    $wf_offset = $offset * $wf_count;
    $wf_page = _get('wfpage', 1, MF_INT_KEY);
    $start = get_start(($wf_page-1)*$wf_count+$_GET['page'], $offset);
    $tplname = 'item_album_waterfall';
} else {
    $start = get_start($_GET['page'], $offset);
    $tplname = 'item_album_list';
}

list($total, $list) = $A->find('a.*', $where, $orderby_arr[$orderby], $start, $offset, TRUE, 's.name as sname,s.subname,s.pid');
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("item/albums/catid/$catid/keyword/$keyword/wfpage/$wf_page/page/_PAGE_"));
    $wf_multipage = multi($total, $wf_offset, $wf_page, url("item/albums/catid/$catid/keyword/$keyword/wfpage/_PAGE_"));
}

if($keyword) $urlpath[] = url_path(lang('item_album_search',$keyword),null);

$active = array();
$active['cate'][(int)$catid] = ' class="selected"';
$active['view'][$view] = ' class="selected"';
$active['orderby'][$orderby] = ' class="selected"';
$active['mode'][$mode] =  ' class="selected"';

$SEO->tags->album_total = $total;
$SEO->tags->current_category_name = $catid ? display('item:category',"catid/$catid") : '综合';
//解析seo设置赋值
$_HEAD['title'] = $SEO->pares('albums')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

$_G['show_sitename'] = FALSE;
if(_input('waterfall') == 'Y' && $_G['in_ajax']) {
    $tplname = 'item_album_waterfall_li';
}
include template($tplname);


?>