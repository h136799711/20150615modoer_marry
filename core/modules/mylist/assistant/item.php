<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$MI = $_G['loader']->model('mylist:item');

switch($op) {
	case 'add':
		$mylist_id = _post('id', 0, MF_INT_KEY);
		$sid = _post('sid', 0, MF_INT_KEY);
		$id = $MI->add($mylist_id, $sid);
		if($id) {
			echo $id;
		} else {
			echo "ERROR";
		}
		output();
		break;
	case 'excuse':
		$id = _post('id', 0, MF_INT_KEY);
		$excuse = _TA(decodeURIComponent(_post('excuse')));
		$rows = $MI->post_excuse($id, $excuse);
		if($_G['charset'] != 'utf-8') {
			$excuse = charset_convert($excuse, $_G['charset'], 'utf-8');
		}
		$ret = array(
			'code' => 'ok',
			'message'=> $excuse,
		);
		echo json_encode($ret);
		output();
		break;

	case 'delete':
		$id = _post('id', 0, MF_INT_KEY);
		$rows = $MI->delete($id);
		echo 'OK';
		output();
		break;

	case 'set_thumb':
		$id = _post('id', 0, MF_INT_KEY);
		$item = $MI->read($id);
		if(!$item) redirect('mylist_item_empty');
		if($item['uid'] != $user->uid) redirect('mylist_manage_access_denied');
		$subject = $_G['loader']->model('item:subject')->read($item['sid']);
		if(!$subject) redirect('item_empty');
		$_G['loader']->model(':mylist')->update_thumb($item['mylist_id'], $subject['thumb']);
		echo 'ok';
		output();

	default:

}
/** end **/