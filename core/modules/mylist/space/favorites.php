<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$offset = 10;
$start = get_start($_GET['page'],$offset);

$fav_obj = $_G['loader']->model('mylist:favorite');
$where = array('f.uid'=>$space->uid);
$orderby = array('m.modifytime'=>'desc');
list($total, $list) = $fav_obj->find('m.*', $where, $orderby, $start, $offset);
if($total > $offset) {
	$multipage = multi($total, $offset, $_GET['page'], url("space/$space->uid/pr/mylist_favorites/page/_PAGE_"));
}

$flag = 'mylist';
$_HEAD['title']	= $space->username.'收藏的主题'.$_CFG['titlesplit'].$space->spacename;

//设置模板ID
$templateid = $space->space_styleid;
//载入模型的内容页模板
include space_template('mylist_list', (int)$templateid);