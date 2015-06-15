<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$topic_obj = $_G['loader']->model('group:topic');
$reply_obj = $_G['loader']->model('group:reply');

$topic_total = get_topic_total( array('t.uid'=>$space->uid,'t.status'=>1) );
$reply_total = get_reply_total( array('r.uid'=>$space->uid,'r.status'=>1) );

if($topic_total > 0) {
	$offset = 20;
	$start = get_start($_GET['page'],$offset);
	$list = $topic_obj->db
		->where(array('t.uid'=>$space->uid,'t.status'=>1))
		->from($topic_obj->table,'t')
		->order_by('dateline','desc')
		->limit($start, $offset)
		->get();

	$multipage = multi($topic_total, $offset, $_GET['page'], url("space/$space->uid/op/group/n/topic/page/_PAGE_"));
}

$flag = 'group';
//页面SEO
$_HEAD['title']	= '我发表的话题'.$_CFG['titlesplit'].$space->spacename;
$tplname = 'group_topics';

//设置模板ID
$templateid = $space->space_styleid;

//载入模型的内容页模板
include space_template($tplname, (int)$templateid);

function get_topic_total($where) 
{
	global $topic_obj,$space;
	return $topic_obj->db->from($topic_obj->table,'t')
							->where(array('uid'=>$space->uid))
							->where('status', 1)
							->count();
}

function get_reply_total($where) 
{
	global $reply_obj,$space;
	return $reply_obj->db->from($reply_obj->table,'r')
							->where($where)
							->select("count(DISTINCT tpid)")
							->get_value();
}