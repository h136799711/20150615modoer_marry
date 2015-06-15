<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'group');

$op = _input('op',null,MF_TEXT);
if($op == 'reload') {
	$rpid = _input('rpid',null,MF_INT_KEY);
	$reply = $_G['loader']->model('group:reply')->read($rpid);
	if($reply) {
		$_G['loader']->helper('msubb');
	}
	echo msubb::pares($reply['content']);
	output();
}

$tpid=_get('id',null,MF_INT_KEY);
$GT = $_G['loader']->model('group:topic');
$topic = $GT->read($tpid);
if(empty($topic)) redirect('group_topic_empty');
if($topic['status'] < 1 && $topic['uid'] != $user->uid) redirect('group_topic_not_audit');

$gid = $topic['gid'];
$G = $_G['loader']->model(':group');
if(!$group = $G->read($gid)) redirect('group_empty');
if($group['status'] < 1) redirect('当前小组没有完成审核，或已经删除，您无法访问。', url("group/$gid"));

//是否小组成员
$gmember = $G->member->read($gid, $user->uid);
//禁言到期，自动恢复状态
if($gmember['status'] == '-1' && $gmember['bantime'] <= $_G['timestamp']) {
    $G->member->unban_post($gid, $user->uid, false);
}
//浏览权限
$readaccess = $G->setting->read($gid,'readaccess');
if($readaccess&&!$gmember['status']) redirect('您没有权限访问小组，请先申请加入该小组。');

//获取话题回应
$where = array();
$where['tpid'] = $tpid;
$where['status'] = 1;
$orderby = 'dateline';
$select = '*';
$offset = 15;
$start = get_start($_GET['page'], $offset);
$reply_index = $start + 1;
$RP = $_G['loader']->model('group:reply');
list($total, $list) = $RP->find($select, $where, $orderby, $start, $offset, true);
if($total > 0) {
    $multipage = mobile_page($total, $offset, $_GET['page'], url("group/mobile/do/topic/id/$tpid/page/_PAGE_"));
}

$_G['loader']->helper('msubb');

//AJAX方式加载下一页
if($_G['in_ajax']) {
	include mobile_template('group_topic_loop');
	output();
}

//增加点击率
if($topic['uid'] != $user->uid) {
    $GT->pageview($tpid);
}

$_HEAD['title'] = $topic['subject']._G('cfg','titlesplit').$group['groupname']._G('cfg','titlesplit')."第{$_GET[page]}页";;
$_HEAD['keywords'] = $topic['username'].','.$group['username'];
if($group['tags']) {
    $_HEAD['keywords'] .= ','.trim(str_replace('|',',',$group['tags']),',');
}
$description = preg_replace("/(\[\/.*?\/\])/i", '', $topic['content']);
$_HEAD['description'] = trimmed_title(preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "", strip_tags($description)),100,'...');
!$_HEAD['description'] && $_HEAD['description']=$MOD['meta_description'];

$header_forward = url("group/mobile/do/group/id/$group[gid]");
include mobile_template('group_topic');
?>