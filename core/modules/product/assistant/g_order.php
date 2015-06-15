<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$O =& $_G['loader']->model('product:order');
$L =& $_G['loader']->model('product:orderlog');
$P =& $_G['loader']->model(':product');

$_G['loader']->helper('form',MOD_FLAG);
$_G['loader']->helper('form','item');

//正在处理的主题ID
$sid = $_G['manage_subject']['sid'];

switch($op) {
    //取消订单
    case 'cancel_order':
        if(!$orderid = (int)$_POST['orderid']) redirect(lang('global_sql_keyid_invalid', 'orderid'));
        if(!$detail = $O->read($orderid)) redirect('订单不存在或者是无效的订单');
        if(!in_array($user->uid,array($detail['sellerid'],$detail['buyerid']))) redirect('global_op_access');
        if($_POST['dosubmit']) {
            $_G['loader']->helper('validate');
            $post = $L->get_post($_POST);
            if(!$post['remark'] || strlen($post['remark']) < 6) {
                redirect('请详细填写取消原因！');
            }
            $post['orderid'] = $orderid;
            $post['operator'] = $user->username;
            if($detail['status'] == 1) {
                $post['order_status'] = '待付款';
            } elseif($detail['status'] == 2) {
                $post['order_status'] = '已付款';
            } elseif($detail['status'] == 3) {
                $post['order_status'] = '待发货';
            }
            $post['changed_status'] = '已取消';
            $L->change_save($post, '6');
            redirect('global_op_succeed');
        } else {
            $tplname = 'order_ajax_cancel';
        }
        break;
    //调整费用
    case 'change_amount':
        if(!$orderid = (int)$_POST['orderid']) redirect(lang('global_sql_keyid_invalid', 'orderid'));
        if(!$detail = $O->read($orderid,FALSE,'e.*')) redirect('订单不存在或者是无效的订单');
        if($_POST['dosubmit']) {
            $post = $O->get_post($_POST);
            if(preg_match("/[^\d.]/", $post['goods_amount'])) redirect('商品总价不是有效的数字，请重新填写！');
            if(preg_match("/[^\d.]/", $_POST['shipfee'])) redirect('配送费用不是有效的数字，请重新填写！');
            $O->change_amount($detail, $post['goods_amount'], $post['order_amount'], $_POST['shipfee']);
            $L =& $_G['loader']->model('product:orderlog');
            $orderlog['orderid'] = $orderid;
            $orderlog['operator'] = $user->username;
            $orderlog['order_status'] = '待付款';
            $orderlog['changed_status'] = '待付款';
            $orderlog['remark'] = '调整费用';
            $L->save($orderlog);
            redirect('global_op_succeed');
        } else {
            $tplname = 'order_ajax_changeamount';
        }
        break;
    //发货
    case 'change_ship':
        if(!$orderid = (int)$_POST['orderid']) redirect(lang('global_sql_keyid_invalid', 'orderid'));
        if($_POST['dosubmit']) {
            $post = $O->get_post($_POST);
            $O->chang_ship($orderid, $post);
            $orderlog = array();
            $orderlog['orderid'] = $orderid;
            $orderlog['operator'] = $user->username;
            $orderlog['order_status'] = '已付款';
            $orderlog['changed_status'] = '已发货';
            $orderlog['remark'] = trim($post['remark']);
            $L->save($orderlog);
            redirect('global_op_succeed');
        } else {
            if(!$detail = $O->read($orderid, FALSE, 'e.*')) redirect('订单不存在或者是无效的订单');
            $kds = file_get_contents(_G('cfg','siteurl') . 'api/kd100.php?act=comlist');
            if($kds) {
                $_G['loader']->helper('mxml');
                $kds = mxml::to_array($kds, false);
            }
            $tplname = 'order_ajax_ship';
        }
        break;
    //修改物流号
    case 'edit_ship':
        if(!$orderid = (int)$_POST['orderid']) redirect(lang('global_sql_keyid_invalid', 'orderid'));
        if(!$detail = $O->read($orderid, FALSE, 'e.*')) redirect('订单不存在或者是无效的订单');
        if($_POST['dosubmit']) {
            $post = $O->get_post($_POST);
            $O->edit_ship($orderid, $post);
            $orderlog['orderid'] = $orderid;
            $orderlog['operator'] = $user->username;
            $orderlog['order_status'] = '已付款';
            $orderlog['changed_status'] = '已发货';
            $orderlog['remark'] = trim($post['remark']);
            $L->save($orderlog);
            redirect('global_op_succeed');
        } else {
            if(!$detail = $O->read($orderid, FALSE, 'e.*')) redirect('订单不存在或者是无效的订单');
            $kds = file_get_contents(_G('cfg','siteurl') . 'api/kd100.php?act=comlist');
            if($kds) {
                $_G['loader']->helper('mxml');
                $kds = mxml::to_array($kds, false);
            }
            $tplname = 'order_ajax_ship';
        }
        break;
    //订单详情
    case 'detail':
        $orderid = (int) $_GET['orderid'];
        if(!$detail = $O->read($orderid,FALSE,'e.*')) redirect('抱歉，订单不存在或者是无效订单！', url('product/member/ac/g_order/sid/$sid'));
        if($detail['buyerid'] != $user->uid && $detail['sellerid'] != $user->uid) redirect('抱歉，您没有权限查看！');
        if($detail['orderstyle'] == '2') {
            $serial = $_G['loader']->model('product:serial')->getlist($orderid,$user->uid);
        }
        $tplname = 'order_detail';
        break;
    //销售统计
    case 'total':
        $status_name = array();
        for($i=1; $i<=6; $i++) {
            $status_name[$i] = strip_tags(lang('product_status_' . $i));
        }
        $timetype = _get('timetype', '', MF_TEXT);
        $timetype != 'addtime' && $timetype = 'paytime';
        if(!$_GET['starttime'] || !strtotime($_GET['starttime'])) {
            $starttime = date('Ymd', $_G['timestamp'] - 30 * 24 * 3600);
        } else {
            $starttime = $_GET['starttime'];
        }
        if(!$_GET['endtime'] || !strtotime($_GET['endtime'])) {
            $endtime = date('Ymd', $_G['timestamp']);
        } else {
            $endtime = $_GET['endtime'];
        }
        $totalprice = $O->totalcount($sid, $timetype, $starttime, $endtime);
        $tplname = 'order_total';
        break;
    //线下付款确认
    case 'offline_pay':
        $ordersn = _T($_POST['ordersn']);
        $orderid = (int)$_POST['orderid'];

        $order_pay = _G('loader')->model('product:order_pay');
        $result = $order_pay->confirm_offline_pay($ordersn, $orderid);
        if(!$result) redirect($order_pay->error());

        //_G('loader')->model('product:order_pay')->confirm_offline_pay($ordersn, $orderid);

        redirect('global_op_succeed',url("product/member/ac/g_order/status/2"));
        break;
    //订单列表
    default:
        $op = 'list';
        $status_name = array();
        for($i=1;$i<=6;$i++) {
            $status_name[$i] = strip_tags(lang('product_status_'.$i));
        }
        $status = _get('status', 0, MF_INT);
        $where = array();
        if($ordersn = _get('ordersn','','_T')) $where['o.ordersn'] = $ordersn;
        $sid && $where['o.sid'] = $sid;

        if($status) {
            $where['o.status'] = $status;
        } else {
            $where['o.status'] = array('where_between_and', array(1,6));
        }

        $timetype = _get('timetype', '', MF_TEXT);
        $timetype != 'addtime' && $timetype = 'paytime';
        $starttime = _get('starttime','','_T');
        $endtime = _get('endtime','','_T');
        if($starttime && $endtime) {
            $where['o.'.$timetype] = array('where_between_and', array(strtotime($starttime), strtotime($endtime)+24*3600));
        } elseif($starttime && !$endtime) {
            $where['o.'.$timetype] = array('where_between_and', array(strtotime($starttime), $_G[timestamp]));
        }

        if($buyername = _get('buyername','','_T')) $where['o.buyername'] = $buyername;
        if($_GET['sid'] && $_G['loader']->model('item:subject')->is_mysubject($_GET['sid'],$user->uid)) {
            $sid = $_GET['sid'];
            set_cookie('manage_subject', $sid);
            $_G['manage_subject'] = $S->read($sid,'*',false);
        }
        $offset = 10;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $O->find('o.*', $where, array('orderid'=>'DESC'), $start, $offset, TRUE,'s.name,s.subname');
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], url("product/member/ac/g_order/sid/$sid/ordersn/$ordersn/starttime/$starttime/endtime/$endtime/buyername/$buyername/page/_PAGE_"));
        }
        $tplname = 'order_manage';
}
?>