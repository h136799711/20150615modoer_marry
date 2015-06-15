<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

if(!_G('user')->isLogin) {
	redirect('member_not_login');
}

//订单支付业务模型
$order_pay_obj = _G('loader')->model('product:order_pay');

if(check_submit('dosubmit')) {

	if(!$order_pay_obj->submit()) {
		redirect($order_pay_obj->error());
	}

	$message = $detail['paymentname']=='cod' ? '发货请求已通知!' : '订单支付成功！';
	$header_title = $_HEAD['title'] = '购买成功';
	$header_forward = U("product/mobile/do/myorder");
	include mobile_template('product_pay_succeed');

	//支付完成后跳转
    // $next_url = url("product/mobile/do/myorder/id/$orderid");
    // if(DEBUG) redirect('恭喜您，订单购买成功！', $next_url);
    // location($next_url);

} else {

	$orderid = _get('orderid',0,MF_INT_KEY);
	$order = $order_pay_obj->read($orderid);

	//没有获得订单数据，提示错误信息
	if(!$order) redirect('product_order_empty');
	if($order['buyerid'] != _G('user')->uid) redirect('这不是您的订单！');

	if($order['status'] == '2' || $order['status'] == '4') {
		location(U("product/mobile/do/order/id/$orderid"));
	}
	if($order['status'] != '1') redirect('订单已完成支付，或订单状态异常，无法进行支付！');

	//线下支付说明
	$offlinepay = $order_pay_obj->get_offline_pay_des($order['sid']);

	//物流信息
	$ship = $_G['loader']->model('product:orderextm')->read($orderid);
	//商品清单
	$goods = $_G['loader']->model('product:ordergoods')->get_by_order($orderid);

	$header_title = $_HEAD['title'] = '订单支付';
	include mobile_template('product_pay');
}