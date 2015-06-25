<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

define('SCRIPTNAV', 'product');
$id = abs(_get('id','0','intval'));

if(!$id) {
    $id = abs(_get('id','0','intval'));
    if(!$id) redirect("缺少ID参数!");
}

$P =& $_G['loader']->model('product:package');
$PAY =& $_G['loader']->model('pay:pay');
$payments = $PAY->payments;

$pkg = $P->read($id);


if(!$pkg['onshelf']) redirect('抱歉，该套餐已经下架，挑选其他套餐。', get_forward());


if($pkg['tags']) {
    $pkg['tags'] = trim($pkg['tags'],'|');
	$tags = explode('|',trim($pkg['tags'],','));
}


$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/package"));

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];



include template('product_package_detail');
