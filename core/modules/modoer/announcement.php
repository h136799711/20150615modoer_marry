<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'announcement');
if(!$_CITY) $_CITY = get_default_city();
$ANN = $_G['loader']->model('announcement');

switch($_GET['do']) {
case 'list':
    $offset = 40;
    $start = get_start($_GET['page'],$offset);
    $where = array();
    $where['city_id'] = array(0,$_CITY['aid']);
    $where['available'] = 1;
    list($total,$list) = $ANN->find('id,city_id,title,orders,author,dateline,available',$where,array('orders'=>'ASC','dateline'=>'DESC'),$start,$offset,true);
    if($total) $multipage = multi($total, $offset, $_GET['page'], url('index/announcement/do/list/page/_PAGE_'));
    break;
default:
    $id = _get('id', null, MF_INT_KEY);
    if(!$detail = $ANN->read($id)) redirect('global_ann_empty');
    //判断是否当前内容所属当前城市，不是则跳转
    if(check_jump_city($detail['city_id'])) location(url("city:$detail[city_id]/index/announcement/id/$id"));
    if($detail['city_id'] && $detail['city_id'] != $_CITY['aid']) {
        init_city($detail['city_id']);
    }
    // //城市URL判断
    // if(check_use_global_url('index/announcement')) {
    //     location(url("index/announcement/id/$id"));
    // }
}

include template('modoer_announcement');
?>