<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'comment');

$CM =& $_G['loader']->model(':comment');

//评论系统接口管理器
$ITFN = $_G['loader']->model('comment:interface_manage');
//获取评论接口类
$ITF = $ITFN->factory();
//使用评论功能还有第三方社会化评论
$use_local_comment = get_class($ITF) == 'msm_comment_local';
//
if(!$use_local_comment) location('mobile/index');

$p = _get('p', '', MF_TEXT);
list($idtype, $id) = explode('_', $p);
$id = (int)$id;

if(!$CM->check_idtype($idtype)) redirect('comment_idype_unkown');
if(!$id) redirect(lang('global_sql_keyid_invalid','id'));
$typeinfo = $CM->idtypes[$idtype];

//评论对象数据
$item = $_G['db']->from($typeinfo['table_name'])->where($typeinfo['key_name'],$id)->get_one();

//评论权限
$access = $user->check_access('comment_disable', $CM, false);
//评论开关
$enable_post = !$item[$typeinfo['close_name']] && !S('comment:disable_comment');

//评论时允许评分
$enable_grade = !empty($typeinfo['grade_name']);

//是否按时间从早到晚排列
$MOD['addtime'] = 'desc'; //强制倒叙
$is_asc = $MOD['addtime'] == 'asc';
//评论单页显示数量
$offset = $MOD['list_num'] > 0 ? $MOD['list_num'] : 10;

$endpage = $_GET['endpage'] > 0;

$where = array();
$where['idtype'] = $idtype;
$where['id'] = $id;
$where['status'] = 1;
$MOD['addtime'] != 'desc' && $MOD['addtime'] = 'asc';
$orderby = array('dateline'=>strtoupper($MOD['addtime']));
if($is_asc) {
	$start = $endpage ? -1 : get_start($_GET['page'], $offset);
} else {
	$start = $endpage ? 0 : get_start($_GET['page'], $offset);
}
list($total, $list) = $CM->find($select, $where, $orderby, $start, $offset, TRUE);
if($endpage) $_GET['page'] = ceil($total/$offset);

$comment_list = $reply_list = array();
$reply_cids = array();
if($list) while ($val = $list->fetch_array()) {
	if($val['reply_cid'] > 0) {
		if(!in_array($val['reply_cid'], $reply_cids)) {
			$reply_cids[] = $val['reply_cid'];
		}
	}
	$comment_list[$val['cid']] = $val;
}

if($reply_cids) {
	$list = $CM->db->from($CM->table)->where('cid', $reply_cids)->get();
	if($list) while ($val = $list->fetch_array()) {
		$reply_list[$val['cid']] = $val;
	}
}

$multipage = mobile_page($total, $offset, $_GET['page'], url("comment/mobile/do/list/p/$p/page/_PAGE_"));

if($_G['in_ajax']) {
	include mobile_template('comment_list_loop');
	output();
}

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("comment/list"));

$title = $item[$typeinfo['title_name']];
$_HEAD['title'] = '评论_'.$title;
include mobile_template('comment_list');

/* end */