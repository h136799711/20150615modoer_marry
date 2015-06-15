<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$S =& $_G['loader']->model('product:shipping');

$_G['loader']->helper('form',MOD_FLAG);
switch($op) {
    case 'add':
        $sid = _get('sid', 0, MF_INT_KEY);
        if(!$sid) redirect(lang('global_sql_keyid_invalid','sid'));
        $admin->tplname = cptpl('shipping_save', MOD_FLAG);
        break;
    case 'edit':
        $shipid = _get('shipid', 0, MF_INT_KEY);
        if(!$detail = $S->read($shipid)) redirect('product_shipping_empty');
        $admin->tplname = cptpl('shipping_save', MOD_FLAG);
        break;
    case 'delete':
        $shipid = _get('shipid', 0, MF_INT_KEY);
        $S->delete($shipid);
        $forward = get_forward();
        redirect('global_op_succeed_delete', $forward);
    case 'save':
        $shipid = $_POST['do'] == 'edit' ? (int)$_POST['shipid'] : null;
        $post = $S->get_post($_POST);
        $S->save($post, $shipid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act,'list'),1));
        break;
    default:
        $op = 'list';
        $sid = _get('sid', 0, MF_INT_KEY);
        if(!$sid) redirect(lang('global_sql_keyid_invalid','sid'));
        $where = array();
        $where['sid'] = $sid;
        $offset = 20;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $S->find('*', $where, array('shipid'=>'ASC'), $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'list',$_GET));
        }
        $admin->tplname = cptpl('shipping_list', MOD_FLAG);
}
?>