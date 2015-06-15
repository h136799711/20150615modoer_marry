<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'comment');

$CM =& $_G['loader']->model(':comment');
//是否按时间从早到晚排列
$is_asc = $MOD['addtime'] == 'asc';
//评论单页显示数量
$offset = $MOD['list_num'] > 0 ? $MOD['list_num'] : 10;
//回应单次显示数量
$reply_offset = 3;

$in_mobile = _input('in_mobile');
if($in_mobile) {
	_G('loader')->helper('function','mobile');
}

if(_input('op')=='reply_get') {

	$msg = array(
		'code' => 200,
		'info' => array(
			'order' => $MOD['addtime'],
			'num' => 0,
			'r_num' => 0,
		),
	);

	$root_cid = _input('root_cid', null, MF_INT_KEY);
	$root_comment = $CM->read($root_cid);
	if(!$root_comment) redirect('对不起，评论信息不存在。');
	$start = _input('start', 0, MF_INT_KEY);
	if($root_comment['root_subtotal'] < $start) {
		$msg['info']['num'] = 0;
	} else {
		$reply_list = array();
		$replys = $CM->reply_list($root_cid, array('dateline' => $is_asc == 'ASC'?'DESC':'ASC'), $start, $reply_offset);
		if($replys) {
			$msg['info']['num'] = $replys->num_rows(); //取得的数量
			$msg['info']['r_num'] = $root_comment['root_subtotal'] - $start - $msg['info']['num'];
			$val['reply_list'] = array();
			while ($rep_val = $replys->fetch_array()) {
				if($rep_val['reply_user']) $rep_val['reply_user'] = explode("\t",$rep_val['reply_user']);
				//加入数组顺序要与排序方式相反
				array_unshift($reply_list, $rep_val);
			}
		}
	}
	$msg = json_encode($msg);
	echo $msg;
	if($reply_list) {
		echo "|\t|\t|\t|\t|";
		if($in_mobile) {
			include mobile_template('comment_reply_li');
		} else {
			include template('comment_reply_li');
		}
	}
	output();

} else {
	$idtype = _T($_GET['idtype']);
	if(!$CM->check_idtype($idtype)) redirect('comment_idype_unkown');
	if(!$id = (int) $_GET['id']) redirect(lang('global_sql_keyid_invalid','id'));
	$endpage = $_GET['endpage'] > 0;

	$where = array();
	$where['idtype'] = $idtype;
	$where['id'] = $id;
	$where['status'] = 1;
	$where['root_cid'] = 0;
	$MOD['addtime'] != 'desc' && $MOD['addtime'] = 'asc';
	$orderby = array('dateline'=>strtoupper($MOD['addtime']));
	if($is_asc) {
		$start = $endpage ? -1 : get_start($_GET['page'], $offset);
	} else {
		$start = $endpage ? 0 : get_start($_GET['page'], $offset);
	}
	list($total, $list) = $CM->find($select, $where, $orderby, $start, $offset, TRUE);
	if($endpage) $_GET['page'] = ceil($total/$offset);
	if($in_mobile) {
		$multipage = mobile_page($total, $offset, $_GET['page'], url("comment/list/idtype/$idtype/id/$id/in_mobile/1/page/_PAGE_"));
	} else {
		$multipage = multi($total, $offset, $_GET['page'], url("comment/list/idtype/$idtype/id/$id/page/_PAGE_"), '', "mo.comment.get_list('$idtype',$id,{PAGE})");
	}

	$comment_list = $reply_root = array();
	if($list) while ($val = $list->fetch_array()) {
		if($val['root_subtotal'] > 0) {
			$replys = $CM->reply_list($val['cid'], array('dateline'=>$is_asc=='ASC'?'DESC':'ASC'), 0, $reply_offset);
			if($replys) {
				$val['reply_list'] = array();
				while ($rep_val = $replys->fetch_array()) {
					if($rep_val['reply_user']) $rep_val['reply_user'] = explode("\t",$rep_val['reply_user']);
					//取最近时间排序，加入数组则要反过来
					array_unshift($val['reply_list'], $rep_val);
				}
			}
		}
		$comment_list[] = $val;
	}

	if(!defined('IN_AJAX')) {
	    $_HEAD['keywords'] = $MOD['meta_keywords'];
	    $_HEAD['description'] = $MOD['meta_description'];
	}

	$urlpath = array();
	$urlpath[] = url_path($MOD['name'], url("comment/list"));

	if($in_mobile) {
		include mobile_template('comment_list');	
	} else {
		include template('comment_list');	
	}
	
}
?>