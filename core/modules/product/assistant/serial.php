<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2010-2011 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

$P =& _G('loader')->model(':product');
$SR =& $_G['loader']->model('product:serial');

$op = _input('op');
switch ($op) {
	case 'delete':
		$pid = _post('pid',null,'intval');
		if(empty($pid)) redirect(lang('global_sql_keyid_invalid','pid'));
		$ids = _post('ids',null);
		$SR->delete($pid,$ids);
		$P->_update_value($pid, 'stock', $SR->get_num($pid)); //更新库存
		redirect('global_op_succeed_delete',url("product/member/ac/serial/op/list/pid/$pid"));
		break;
	case 'save':
		$pid = _post('pid', null, 'intval');
		$serial = _post('serial',null,MF_TEXT);
		$num = $SR->save($pid, $serial);
		$P->_update_value($pid, 'stock', $num); //更新库存
		redirect('productcp_serial_add_succeed',url("product/member/ac/serial/op/list/pid/$pid"));
		break;
	default:
		$pid = _get('pid',null,'intval');
		$product = $P->read($pid);
		if(!$product) redirect('product_empty');
		$start = get_start($_GET['page'],$offset=40);
		list($total,$list) = $SR->find($pid,$start,$offset);
		if($total) {
	        $multipage = multi($total, $offset, $_GET['page'], url("product/member/ac/serial/op/list/page/_PAGE_"));
	    }
		$tplname = 'serial_list';
		break;
}
?>