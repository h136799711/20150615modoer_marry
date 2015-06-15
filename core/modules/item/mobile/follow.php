<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

if(!$user->isLogin && !in_array($ac, $guestacs)) {
	$forward = $_G['web']['reuri'] ? ($_G['web']['url'] . $_G['web']['reuri']) : url('meber/mobile');
	location(url('member/mobile/do/login/forward/'.base64_encode($forward)));
}

$op = _input('op', null, MF_TEXT);
$F =& $_G['loader']->model('item:favorite');

switch ($op) {
	case 'add':
        if(!$sid = (int)$_POST['sid']) redirect(lang('global_sql_keyid_invalid', 'sid'));
        $post = array('id' => $sid);
        $F->save($post);
        redirect('item_favorite_succeed');
		break;
	default:
		$select = 's.*';
		$where = array();
		$where['f.uid'] = $user->uid;
		$start = get_start($_GET['page'], $offset = 10);
		list($total, $list) = $F->find($select,$where, $start, $offset);
		$multipage = mobile_page($total, $offset, $_GET['page'], url("item/mobile/do/follow/op/$op/page/_PAGE_"));
		$reviewcfg = $_G['loader']->variable('config','review');
		//ajax
		if($_G['in_ajax']) {
			include mobile_template('item_follow_li');
			output();
		}
		include mobile_template('item_follow');
		break;
}