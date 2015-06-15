<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$SR =& $_G['loader']->model(MOD_FLAG.':serial');
$op = _input('op');
switch ($op) {
	case 'delete':
		$pid = _post('pid',null,MF_INT_KEY);
		if(empty($pid)) redirect(lang('global_sql_keyid_invalid','pid'));
		$ids = _post('ids',null);
		$SR->delete($pid,$ids);
		redirect('global_op_succeed_delete',cpurl($module,$act,'list',array('pid'=>$pid)));
		break;
	case 'save':
		$pid = _post('pid',null,MF_INT_KEY);
		$serial = _post('serial',null,MF_TEXT);
		$SR->save($pid, $serial);
		redirect('productcp_serial_add_succeed',cpurl($module,$act,'list',array('pid' =>$pid)));
		break;
	default:
		$pid = _get('pid',null,MF_INT_KEY);
		$start = get_start($_GET['page'],$offset=40);
		list($total,$list) = $SR->find($pid,$start,$offset);
		if($total) {
	        $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'list'));
	    }
		$admin->tplname = cptpl('serial_list', MOD_FLAG);
		break;
}
?>