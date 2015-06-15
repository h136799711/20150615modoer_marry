<?php
/**
* @author 风格店铺
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$O =& $_G['loader']->model('product:order');
$P =& $_G['loader']->model(':product');

$_G['loader']->helper('form', MOD_FLAG);
$_G['loader']->helper('form', 'item');
$sid = (int) $_GET['sid'];
$op = _input('op');
switch($op) {
case 'view':
    $orderid = (int) $_GET['orderid'];
    if(!$detail = $O->read($orderid,FALSE,'e.*')) redirect('抱歉，订单不存在或者是无效订单！', url('product/member/ac/g_order/sid/$sid'));
    if($detail['orderstyle']=='2') {
        $serial = $_G['loader']->model('product:serial')->getlist($orderid,$user->uid);
    }
    $admin->tplname = cptpl('order_detail', MOD_FLAG);
    break;
case 'pay_offline':
    $ordersn = _T($_POST['ordersn']);
    $orderid = (int)$_POST['orderid'];
    
    $order_pay = _G('loader')->model('product:order_pay');
    $result = $order_pay->confirm_offline_pay($ordersn, $orderid);
    if(!$result) redirect($order_pay->error());

    redirect('global_op_succeed',cpurl($module,$act,'list'));
default:
    $op = 'list';
    $status_name = array();
    for($i=1; $i<=6; $i++) {
        $status_name[$i] = strip_tags(lang('product_status_'.$i));
    }

    if($_GET['pid'] > 0) {
        $r = $P->db->from('dbpre_product_ordergoods')->where('pid',$_GET['pid'])->select('orderid')->get();
        $orderids = array();
        if($r) while ($v = $r->fetch_array()) {
            $orderids[] = $v['orderid'];
        }
    }
    $status = _get('status', 0, MF_INT);
    $paymentname = _get('paymentname','',MF_TEXT);
    $where = array();
    $orderids && $P->db->where('orderid', $orderids);
    $_GET['sid'] && $P->db->where('s.sid', $_GET['sid']);
    if($ordersn = _get('ordersn', '', '_T')) $where['o.ordersn'] = $ordersn;
    $starttime = _get('starttime', '', '_T');
    $endtime = _get('endtime','','_T');
    if($starttime && $endtime) {
        $where['o.addtime'] = array('where_between_and', array(strtotime($starttime), strtotime($endtime)+24*3600));
    }elseif($starttime && !$endtime){
        $where['o.addtime'] = array('where_between_and', array(strtotime($starttime), $_G[timestamp]));
    }
    if($buyername = _get('buyername','','_T')) $where['o.buyername'] = $buyername;
    $sid && $where['o.sid'] = $sid;
    $status && $where['o.status'] = $status;
    $paymentname && $where['o.paymentname'] = $paymentname;
    $offset = 20;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $O->find('o.*', $where, array('orderid'=>'DESC'), $start, $offset, TRUE,'s.name,s.subname');
    if($total) {
        $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        $mylist = $orderids = array();
        while($val = $list->fetch_array()) {
            $mylist[] = $val;
            $orderids[] = $val['orderid'];
        }
        $oq = $_G['db']->from('dbpre_pay')->where('orderid', $orderids)->where('order_flag', 'tuan_order')->get();
        $paylist = array();
        if($oq) {
            while ($v = $oq ->fetch_array()) {
                $paylist[$v['orderid']] = date('Ymd',$v['creation_time']) . $v['payid'];
            }
        }
    }
    $admin->tplname = cptpl('order_list', MOD_FLAG);
}
?>