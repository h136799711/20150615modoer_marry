<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item_map');

$op = _input('op','',MF_TEXT);

switch($op) {

case 'detail':
    
    $S = $_G['loader']->model('item:subject');
    $sid = _input('sid', 0, MF_INT_KEY);
    if(!$subject = $S->read($sid)) redirect('item_empty');
    $subject_field_table_tr = $S->display_sidefield($subject);
    include template('item_map_infow');
    output();
    break;

default:

    $aid = _input('aid', 0, MF_INT_KEY);
    $catid = _input('catid', 0, MF_INT_KEY);
    $total = _input('total', null, MF_INT_KEY);

    $S = $_G['loader']->model('item:subject');

    $pcatid = $S->get_pid($catid);
    $model = $S->get_model($pcatid,TRUE);
    if(!$model['usearea']) $catid = 0;

    $where = array();
    if(!$aid) {
        $where['city_id'] = (int) $_CITY['aid'];
    } elseif($aid>0) {
        $where['aid'] = $aid;
    }
    if($pcatid>0) $where['pid'] = $pcatid;
    $where['status'] = 1;
    $where['map_lng'] = array('where_not_equal',array('0'));

    $num = 10;
    $start = get_start($_GET['page'], $num);
    if($total>0)
        list(,$list) = $S->find('*', $where, array('addtime'=>'DESC'), $start, $num, false);
    else
        list($total,$list) = $S->find('*', $where, array('addtime'=>'DESC'), $start, $num);

    if($total) {
        $multipage = multi($total, $num, $_GET['page'], url("item/map/catid/$catid/aid/$aid/total/$total/page/_PAGE_"));
    }

    //seo设置
    $SEO->tags->current_category_name = $pcatid ? display('item:category',"catid/$pcatid") : '';
    $SEO->tags->current_area_name = $aid ? display('modoer:area',"aid/$aid") : '';
    $SEO->tags->subject_total = $total;
    //解析seo设置赋值
    $_HEAD['title'] = $SEO->pares('map')->title;
    $_HEAD['keywords'] = $SEO->keywords;
    $_HEAD['description'] = $SEO->description;


    $_G['show_sitename'] = FALSE;
    $mapflag = $_G['cfg']['mapflag'] ? $_G['cfg']['mapflag'] : 'baidu';
    $version = 1.3;

    include template('item_map');
}

?>