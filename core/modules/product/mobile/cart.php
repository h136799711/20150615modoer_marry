<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$cart_slg = mc_product_cart::instance(); //我的购物车

if(_get('op') == 'once') {

	$pid = _get('pid', 0, MF_INT_KEY);
	$quantity = _get('quantity', 0, MF_INT_KEY);
	if(!$quantity) $quantity = 1;

	$product = _G('loader')->model(':product')->read($pid);
	if(!$product) redirect('product_empty');
	if(_G('user')->isLogin && _G('loader')->model('item:subject')->is_mysubject($product['sid'], _G('user')->uid)) {
		redirect('抱歉，不能购买自己的产品！');
	};
	set_cookie('product_num', $quantity);

	location(U("product/mobile/do/order/pid/$pid"));

} else {
	//删除虚拟商品
	$cart_slg->delete_virtual_goods();

	$cartid = $cart_slg->cartid;
	list($total, $list) = $cart_slg->get_goods_list();
}


$header_title = $_HEAD['title'] = '我的购物车';
include mobile_template('product_cart');

/** end **/