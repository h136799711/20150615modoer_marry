<?php
/**
 * 订单提交
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2013 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$service = new mc_product_service();

switch (_input('op')) {

	//提交填写的订单信息，生成订单
	default:

		if($_POST['remark'] && _G('charset')!='utf-8') {
			$_POST['remark'] = charset_convert($_POST['remark'], 'utf-8', _G('charset'));
		}

		$order_obj = _G('loader')->model('product:order_create');
		$orderid = $order_obj->create($_POST);

		if(!$orderid) {
			$service->add_error($order_obj);
		} else {
			//进入支付页面
			$service->url = U("product/member/ac/pay/orderid/$orderid");
			$service->url_mobile = U("product/mobile/do/pay/orderid/$orderid");
		}

		break;
}

echo $service->fetch_all_attr('json');
output();

/** end **/
