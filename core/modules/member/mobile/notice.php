<?php
!defined('IN_MUDDER') && exit('Access Denied');

$isread = _get('isread', 0, MF_INT);
$notice_obj = _G('loader')->model('member:notice');

$op = _input('op','',MF_TEXT);
if($op == 'set_read') {

	$id = _input('id', 0, MF_INT_KEY);
	$notice_obj->update_note($id);

	$array = array('code' => 200);
	echo json_encode($array);
	output();

} else {

	$total = $notice_obj->get_count($isread);
	if($total > 0) {
	    $offset = 20;
	    $start = get_start($_GET['page'], $offset);
	    $list = $notice_obj->get_list($isread, $start, $offset);
	}

	$_HEAD['title'] = "我的提醒";
	$header_title 	= $_HEAD['title'];
	$header_forward = U("member/mobile/do/message");

	include mobile_template('member_notice');
	
}