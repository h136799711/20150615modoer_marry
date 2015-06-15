<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2013 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$service = new mc_product_service();
if(!_G('user')->isLogin) {
	$service->add_error('member_op_not_login');
	echo $service->fetch_all_attr('json');
	output();
}

$op = _input('op', '', MF_TEXT);
switch($op) {
	//添加地址
	case 'add':
		$address_obj = new mc_product_address();
		if(!$address_obj->set_uid(_G('user')->uid)) {
			$service->add_error($address_obj);
		} else {
			$address_id = $address_obj->add();
			if(!$address_id) {
				$service->add_error($address_obj);
			} else {
				$service->data = $address_obj->get_one($address_id);
			}
		}
		break;
	default:
		$base = new ms_base();
		$base->add_error('global_op_unkown', 110000);
		echo $base->error_json();
}

echo $service->fetch_all_attr('json');
output();
/** end **/