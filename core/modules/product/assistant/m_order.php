<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$O =& $_G['loader']->model('product:order');
$P =& $_G['loader']->model(':product');

$_G['loader']->helper('form',MOD_FLAG);

switch($op) {
	//确认收货
	case 'order_confirm':
		if(!$orderid = (int)$_POST['orderid']) redirect(lang('global_sql_keyid_invalid', 'orderid'));
		if(!$detail = $O->read($orderid,FALSE,'e.*')) redirect('订单不存在或者是无效的订单');
		if($_POST['dosubmit']) {
			//password
			if(!$_POST['password']) {
				redirect('对不起，您未填写支付密码！');
			} elseif(!$user->check_paypw($_POST['password'])) {
				redirect('对不起，您填写的支付密码不正确！');
			}
			$O->order_confirm($orderid);
			$L = $_G['loader']->model('product:orderlog');
			$orderlog['orderid'] = $orderid;
			$orderlog['operator'] = $user->username;
			$orderlog['order_status'] = '已发货';
			$orderlog['changed_status'] = '已完成';
			$orderlog['remark'] = '确认收货';
			$L->save($orderlog);
			redirect('global_op_succeed');
		} else {
			$tplname = 'order_ajax_confirm';
		}
		break;
	case 'detail':
		$orderid = (int) $_GET['orderid'];
		//手机模块适应
		if(check_module('mobile') && is_mobile() && S('mobile:auto_switch')) {
			location(url("product/mobile/do/myorder/orderid/$orderid"));
		}

		if(!$detail = $O->read($orderid,FALSE,'e.*')) redirect('抱歉，订单不存在或者是无效订单！', url('product/member/ac/m_order'));
		if($detail['buyerid'] != $user->uid && $detail['sellerid'] != $user->uid) redirect('抱歉，您没有权限查看！');
		if($detail['orderstyle'] == '2') {
			$serial = $_G['loader']->model('product:serial')->getlist($orderid, $user->uid);
		}
		$tplname = 'order_detail';
		break;
	case 'comment':
		$orderid = (int) $_GET['orderid'];
		if(!$pid = (int) $_GET['pid']) redirect('无效的PID！', url('product/member/ac/m_order'));
		if($O->check_comment_exists($orderid, $pid, $user->uid)) redirect('您已经评价过此商品！', url('product/member/ac/m_order'));
		$G =& $_G['loader']->model('product:ordergoods');
		if(!$detail = $O->read($orderid,FALSE,'e.*')) redirect('抱歉，订单不存在或者是无效订单！', url('product/member/ac/m_order'));
		$_G['loader']->helper('form');
		$product = $G->getinfo($pid, $orderid);

		//使用评论模需要的相关配置信息
		$comment_cfg = array (
			'idtype'		=> 'product',
			'id'			=> $pid,
			'extra_id'		=> $orderid,
			'title'			=> 'Re:'.$product['pname'],
			'comments'		=> $detail['comments'],
			'grade'			=> $detail['grade'],
			'enable_post'   => true,
			'enable_grade'  => true,
			'use_local_comment' => true,
		);

		$tplname = 'order_comment';
		break;
	case 'commented':
		$orderid = _input('orderid', 0, MF_INT_KEY);
		$pid = _input('pid', 0, MF_INT_KEY);
		$O->set_commented($orderid, $pid);
		echo 'OK';
		output();
	default:
		//手机模块适应
		if(check_module('mobile') && is_mobile() && S('mobile:auto_switch')) {
			location(url("product/mobile/do/myorder"));
		}
		$op = 'list';
		$status_name = array();
		for($i=1;$i<=6;$i++) {
			$status_name[$i] = strip_tags(lang('product_status_'.$i));
		}
		$status = _get('status', 0, MF_INT);
		$where = array();
		if($ordersn = _get('ordersn', '', '_T')) $where['o.ordersn'] = $ordersn;
		$where['o.buyerid'] = $user->uid;
		if($status) {
			$where['o.status'] = $status;
		} else {
			$where['o.status'] = array('where_between_and', array(1,6));
		}
		$starttime = _get('starttime','','_T');
		$endtime = _get('endtime','','_T');
		if($starttime && $endtime) {
			$where['o.addtime'] = array('where_between_and', array(strtotime($starttime), strtotime($endtime)+24*3600));
		} elseif($starttime && !$endtime) {
			$where['o.addtime'] = array('where_between_and', array(strtotime($starttime), $_G['timestamp']));
		}
		$status && $where['o.status'] = $status;
		$offset = 10;
		$start = get_start($_GET['page'], $offset);
		list($total, $list) = $O->find('o.*', $where, array('orderid'=>'DESC'), $start, $offset, TRUE,'s.name,s.subname');
		if($total) {
			$multipage = multi($total, $offset, $_GET['page'], url("product/member/ac/m_order/page/_PAGE_"));
		}
		$tplname = 'order_list';
}
?>