<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['assistant_menu']['member'][] = '我的商品订单,product/member/ac/m_order/all/1';

$_G['manage_menu']['product']['title'] = '产品销售';
$_G['manage_menu']['product'][] = '商城设置,product/member/ac/g_setting';
$_G['manage_menu']['product'][] = '产品管理,product/member/ac/g_product';
$_G['manage_menu']['product'][] = '产品订单管理,product/member/ac/g_order/all/1';
$_G['manage_menu']['product'][] = '产品销售统计,product/member/ac/g_order/op/total';
$_G['manage_menu']['product'][] = '物流方式管理,product/member/ac/m_shipping';

?>