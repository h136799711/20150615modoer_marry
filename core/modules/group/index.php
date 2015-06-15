<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

//官方小组
$G = $_G['loader']->model(':group');
$where = array();
$where['status'] = 1;
$where['auth'] = 1;
$where['city_id'] = array(0, $_CITY['aid']);
$orderby = array('members'=>'DESC');//array('topics'=>'DESC');
$offset = 10;
$start = get_start($_GET['page'], $offset);
list(, $auth_list) = $G->find("*", $where, $orderby, $start, $offset, false);
//地方小组
$where = array();
$where['status'] = 1;
$where['city_id'] = $_CITY['aid'];
$orderby = array('members'=>'DESC');//array('topics'=>'DESC');
$offset = 10;
$start = get_start($_GET['page'], $offset);
list(, $area_list) = $G->find("*", $where, $orderby, $start, $offset, false);
//推荐小组
$where = array();
$where['status'] = 1;
$where['finer'] = 1;
$where['city_id'] = array(0, $_CITY['aid']);
$orderby = array('members'=>'DESC');//array('topics'=>'DESC');
$offset = 10;
$start = get_start($_GET['page'], $offset);
list(, $finer_list) = $G->find("*", $where, $orderby, $start, $offset, false);

//解析seo设置赋值
$_HEAD['title'] = $SEO->pares('index')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

include template('group_index');
?>