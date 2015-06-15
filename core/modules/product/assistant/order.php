<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

//我的购物车
$cart_slg = mc_product_cart::instance(); 

//购买商品的购物车商品ID
$cids = explode('_', _get('cids', '0', MF_TEXT));

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

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));
$urlpath[] = url_path('确认订单信息');

include template('product_order');

/* end */