<?php
!defined('IN_MUDDER') && exit('Access Denied');

$cz_type = S('pay:cz_type') ? unserialize(S('pay:cz_type')) : false;
if(!in_array('rmb', $cz_type)) {
	redirect('对不起，网站没有打开现金充值功能。');
}

if(!S('pay:alipay_mobile')) {
	redirect('对不起，网站没有打开支付宝支付接口。');
}

$log_obj = $_G['loader']->model('pay:log');

if(check_submit('dosubmit')) {
	
	$log_obj->create(_post('pay'), null, true);

} elseif($op=='return') {

    $orderid = _get('orderid', 0, 'intval');
    $order = $log_obj->read($orderid);
    if(empty($order)) redirect('订单不存在！');

    $flash_message = '充值完毕！';
    $header_title = '我的积分';
    include mobile_template('member_point');

} else {

	$header_title = '在线充值';
	include mobile_template('pay_recharge');

}