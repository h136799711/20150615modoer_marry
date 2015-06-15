<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$sid = _input('sid', null, MF_INT_KEY);
$subject = $_G['loader']->model('item:subject')->read($sid,'*',FALSE);
if(!$subject) redirect('item_subject_empty');

$SS = $_G['loader']->model('item:subjectsetting');
$setting = $SS->read($sid);

$edit_links = $_G['hook']->hook('admincp_subject_edit_link', $sid, true);

$op = _input('op');
switch ($op) {
	case 'post':
		$PS = $_G['loader']->model('product:subjectsetting');
		$post = array(
			'brokerage'=>_post('brokerage','',MF_INT),
			'offlinepay'=>_post('offlinepay','',MF_TEXT),
			'use_deliverytime'=>_post('use_deliverytime','',MF_INT_KEY),
			'onedaydelivery_limit'=>_post('onedaydelivery_limit','',MF_INT_KEY),
		);
		$PS->save($sid,$post);
		redirect('global_op_succeed', cpurl('product','subjectsetting','',array('sid'=>$sid)));
	default:
		$admin->tplname = cptpl('subjectsetting', MOD_FLAG);
		break;
}
?>