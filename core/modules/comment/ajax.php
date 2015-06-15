<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'comment');

$CM =& $_G['loader']->model(':comment');

$op = _input('op');
if($op=='update_comments') {
	$idtype = _post('idtype', '', MF_TEXT);
	$id = _post('id', '', MF_INT_KEY);
	$comments = _post('comments', '', MF_INT_KEY);
	$grade = _post('grade', '', MF_INT_KEY);

	if($CM->update_comments($idtype, $id, $comments, $grade)) {
		echo 'OK';
		output();
	}
}
?>