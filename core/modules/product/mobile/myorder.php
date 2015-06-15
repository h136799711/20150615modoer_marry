<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
 */
!defined('IN_MUDDER') && exit('Access Denied');

$O = $_G['loader']->model('product:order');

$op = _input('op', '', MF_TEXT);
switch($op) {

	default:

		if($orderid = _get('orderid', null, MF_INT_KEY)) {

	        if(!$detail = $O->read($orderid,FALSE,'e.*')) redirect('抱歉，订单不存在或者是无效订单！');
	        if($detail['buyerid'] != _G('user')->uid && $detail['sellerid'] != _G('user')->uid) redirect('抱歉，您没有权限查看！');
	        if($detail['orderstyle'] == '2') {
	            $serial = _G('loader')->model('product:serial')->getlist($orderid, _G('user')->uid);
	        }

	        //卖家详情
	        $seller = _G('loader')->model(':member')->read($detail['sellerid']);
	        //商户
	        $store = _G('loader')->model('item:subject')->read($detail['sid']);

	        $header_title = '订单详情';
			include mobile_template('product_myorder_detail');

		} else {

			$status = _get('status', 0, MF_INT);
			$where = array();
			$where['o.buyerid'] = _G('user')->uid;
			if($status) {
			    $where['o.status'] = $status=='1' ? $status : ($status=='4'?array('where_between_and', array(4,5)):array('where_between_and', array(2, 3)));
			} else {
			    $where['o.status'] = array('where_between_and', array(1,6));
			}

			$offset = 10;
			$start = get_start($_GET['page'], $offset);
			list($total, $list) = $O->find('o.*', $where, array('orderid'=>'DESC'), $start, $offset, TRUE,'s.name,s.subname');
			if($total) {
			    $multipage = mobile_page($total, $offset, $_GET['page'], U("product/mobile/do/myorder/status/$status/page/_PAGE_"));
			}

			if($_G['in_ajax']) {
				include mobile_template('product_myorder_loop');
				output();
			}

			$header_title = '我的订单';
			include mobile_template('product_myorder');
		}

}

/** end **/