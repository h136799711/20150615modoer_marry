<?php
/**
 * 订单在线支付成功通知
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
 */
!defined('IN_MUDDER') && exit('Access Denied');

$oid = _input('oid', null, MF_INT_KEY);
$service = new mc_product_service();
$pay_obj = _G('loader')->model('product:order_pay');

if(!$pay_obj->pay_online_succeed($oid)) {
	$service->add_error($pay_obj);
}

echo $service->fetch_all_attr('json');
output();

/** end **/