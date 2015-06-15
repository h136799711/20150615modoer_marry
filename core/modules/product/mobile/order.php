<?php
/**
 * 商品购物结算
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

//我的购物车
$cart_slg = mc_product_cart::instance();

//单一商品快速购买
$pid = _get('pid', 0, MF_INT_KEY);
if($pid > 0) {
	$cart_slg->delete_by_pid($pid);
	$quantity = (int) _cookie('product_num');
	$cid = $cart_slg->add($pid, $quantity);
	if(!$cid) redirect($cart_slg->error());
	$cids = array($cid);
} else {
	//购买商品的购物车商品ID
	$cids = explode('_', _get('cids', '0', MF_TEXT));
}

//结算验证
$checkout_obj = $cart_slg->checkout($cids);

//处理结果
if(!$checkout_obj) {
    redirect($cart_slg->error());
}

//商城配置
$setting_obj = _G('loader')->model('product:subjectsetting'); //是否要求买家指定发货日期
if($use_deliverytime = $setting_obj->read($sid, 'use_deliverytime')) {
    $onedaydelivery_limit = $setting_obj->read($sid, 'onedaydelivery_limit');
    if(!$onedaydelivery_limit && !is_numeric($onedaydelivery_limit)) $onedaydelivery_limit = 16;
}

//买家(当前登录用户)的收货地址列表
$address_list = array();
$address_obj = _G('loader')->model('member_address');
$r = $address_obj->get_list(_G('user')->uid);
if($r) while ($v = $r->fetch()) {
	$address_list[] = $v;
}

$header_title = $_HEAD['title'] = '填写订单';
include mobile_template('product_order');