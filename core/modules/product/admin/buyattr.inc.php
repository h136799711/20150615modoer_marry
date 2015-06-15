<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$P = _G('loader')->model(':product');
$PB = $_G['loader']->model('product:buyattr');

$op = _input('op');
$pid = _input('pid', 0, MF_INT_KEY);

$product = $P->read($pid);
if(!$product) redirect('product_empty');

switch ($op) {
	case 'listorder':
		if($_POST['listorder']) foreach ($_POST['listorder'] as $id => $listorder) {
			$PB->_update_value((int)$id, 'listorder', (int)$listorder);
		}
		location(cpurl($module,$act,'list',array('pid' =>$pid)));
		break;
	case 'delete':
		$ids = _post('ids',null);
		if(empty($pid)) redirect(lang('global_sql_keyid_invalid','pid'));
		$PB->delete_ex($pid, $ids);
		redirect('global_op_succeed_delete', cpurl($module,$act,'list',array('pid' =>$pid)));
		break;
	case 'add':
            $buyattr_list = $PB->parse_post($_POST['buyattr']);
            //解析错误提示
            if($PB->has_error()) redirect($PB->error());
			//插入购买属性表
	        if($buyattr_list && is_array($buyattr_list)) {
	            foreach ($buyattr_list as $buyattr) {
	                $PB->add($pid, $buyattr);
	            }
	        }
	        redirect('global_op_succeed', cpurl($module,$act,'list',array('pid' =>$pid)));
		break;
	case 'edit':
		$id = _post('id',null);
		$buyattr = _post('name','',MF_TEXT).'='._post('value','',MF_TEXT);
		$buyattr_list = $PB->parse_post($buyattr);
		//解析错误提示
        if($PB->has_error()) redirect($PB->error());
        //更新保存
		$PB->edit($id, $buyattr_list[0]);
		redirect('global_op_succeed', cpurl($module,$act,'list',array('pid' =>$pid)));
		break;

	case 'save':
		$pid = _post('pid',null,MF_INT_KEY);
		$serial = _post('serial',null,MF_TEXT);
		$SR->save($pid, $serial);
		redirect('productcp_serial_add_succeed', cpurl($module,$act,'list',array('pid' =>$pid)));
		break;
	default:
		$pid = _get('pid',null,MF_INT_KEY);
		$list = $PB->find_all('*', array('pid'=>$pid), 'listorder');
		$admin->tplname = cptpl('buyattr_list', MOD_FLAG);
		break;
}
?>