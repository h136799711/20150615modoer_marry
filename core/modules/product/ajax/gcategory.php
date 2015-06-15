<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2013 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$service = new mc_product_service();
$op = _input('op', '', MF_TEXT);
switch($op) {

	case 'sub':
		$pid = _input('pid', 0, MF_INT_KEY);
		$gcategory_obj = $_G['loader']->model('product:gcategory');
		$service->category = $gcategory_obj->read($pid);
		if($service->category) {
			if(!$service->category['enabled']) {
				$service->add_error('对不起，分类已关闭。');
			}
			_G('loader')->helper('query','product');
			$service->data = query_product::gcategory(array('pid'=>$pid));
		} else {
			$service->add_error($gcategory_obj);
		}
		echo $service->fetch_all_attr('json');
		output();
		break;

};

/** end **/