<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$offset = 10;
$start = get_start($_GET['page'],$offset);

$mylist_obj = $_G['loader']->model(':mylist');
$total = $mylist_obj->db->from($mylist_obj->table,'m')
			->where(array('uid'=>$space->uid))->count();

if($total > 0) {
	$list = $mylist_obj->db->sql_roll_back('from,where')->from($mylist_obj->table)
							->order_by($lo_arr[$lo])->limit($start, $offset)->get();
	$multipage = multi($total, $offset, $_GET['page'], url("space/$space->uid/pr/mylist/page/_PAGE_"));
}

$flag = 'mylist';
$_HEAD['title']	= $space->username.'发布的榜单'.$_CFG['titlesplit'].$space->spacename;

//设置模板ID
$templateid = $space->space_styleid;
//载入模型的内容页模板
include space_template('mylist_list', (int)$templateid);