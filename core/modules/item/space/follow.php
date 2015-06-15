<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$favorite_obj = $_G['loader']->model('item:favorite');

$select = 's.city_id,s.name,s.subname,s.pid,s.catid,status,reviews,pictures,guestbooks,s.addtime,owner,favorites,avgsort';
$where = array();
$where['f.uid'] = $space->uid;
$start = get_start($_GET['page'], $offset = 20);
list($total, $list) = $favorite_obj->find($select,$where, $start, $offset);
if($total > 0) {
	$multipage = multi($total, $offset, $_GET['page'], url("space/$space->uid/pr/item_follow/page/_PAGE_"));
}

$flag = 'item/list';

//页面SEO
$_HEAD['title']	= $space->username.'关注的主题' . $_CFG['titlesplit'] . $space->spacename;
$tplname = 'item_list';

//设置模板ID
$templateid = $space->space_styleid;

//载入模型的内容页模板
include space_template($tplname, (int)$templateid);