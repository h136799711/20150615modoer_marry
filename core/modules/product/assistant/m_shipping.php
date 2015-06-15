<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op','list',MF_TEXT);
$S =& $_G['loader']->model('product:shipping');

$_G['loader']->helper('form',MOD_FLAG);
//正在处理的主题ID
$sid = (int)$_G['manage_subject']['sid'];

switch($op) {
    case 'add':
        $tplname = 'shipping_save';
        break;
    case 'edit':
        $shipid = (int) $_GET['shipid'];
        if(!$detail = $S->read($shipid)) redirect('product_shipping_empty');
        $tplname = 'shipping_save';
        break;
    case 'delete':
        $shipid = (int) $_GET['shipid'];
        $S->delete($shipid);
        $forward = get_forward();
        redirect('global_op_succeed_delete', $forward);
    case 'post':
        $shipid = $_POST['do'] == 'edit' ? (int)$_POST['shipid'] : null;
        $post = $S->get_post($_POST);
        $post['sid'] = $sid;
        $S->save($post, $shipid);
        redirect('global_op_succeed', url('product/member/ac/m_shipping'));
        break;
    default:
        $op = 'list';
        $where = array();
        $where['sid'] = $sid;
        $offset = 20;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $S->find('*', $where, array('shipid'=>'ASC'), $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], url("product/member/ac/m_shipping/page/_PAGE_"));
        }
        $tplname = 'shipping_list';
}
?>