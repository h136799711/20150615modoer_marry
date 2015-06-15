<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$picture_obj = $_G['loader']->model('item:picture');

$total = $picture_obj->db->from($picture_obj->table,'p')
						->where(array('uid'=>$space->uid))
						->where('status', 1)
						->count();

if($total > 0)  {
	$offset = 9;
	$start = get_start($_GET['page'],$offset);
	$list = $picture_obj->db->sql_roll_back('where')->from($picture_obj->table)
							->order_by('addtime','DESC')->limit($start, $offset)->get();

	$multipage = multi($total, $offset, $_GET['page'], url("space/$space->uid/pr/item_pictures/page/_PAGE_"));
}

$flag = 'item/pictures';

//页面SEO
$_HEAD['title']	= $space->username.'添加的图片' . $_CFG['titlesplit'] . $space->spacename;
$tplname = 'item_pictures';

//设置模板ID
$templateid = $space->space_styleid;

//载入模型的内容页模板
include space_template($tplname, (int)$templateid);