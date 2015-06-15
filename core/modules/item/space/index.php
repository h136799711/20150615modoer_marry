<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$subject_obj = $_G['loader']->model('item:subject');

$where['cuid'] = $space->uid;
$orderby = array('addtime', 'DESC');

$select = 'sid,city_id,aid,pid,catid,name,subname,status,reviews,pictures,guestbooks,addtime,owner,favorites,avgsort';

$start = get_start($_GET['page'], $offset = 20);
list($total, $list) = $subject_obj->find($select, $where, array('addtime'=>'DESC'), $start, $offset);
if($total > 0) {
	$multipage = multi($total, $offset, $_GET['page'], url("space/$space->uid/pr/item/page/_PAGE_"));
}

$flag = 'item/list';

//页面SEO
$_HEAD['title']	= $space->username.'添加的主题' . $_CFG['titlesplit'] . $space->spacename;
$tplname = 'item_list';

//设置模板ID
$templateid = $space->space_styleid;

//载入模型的内容页模板
include space_template($tplname, (int)$templateid);