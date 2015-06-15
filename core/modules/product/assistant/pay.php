<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$O = _G('loader')->model('product:order_pay');

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("product/index"));

if(check_submit('dosubmit')) {

    $pay_obj = _G('loader')->model('product:order_pay');
    $orderid = _post('orderid', null, 'intval');

    $pay_obj = _G('loader')->model('product:order_pay');
    $result = $pay_obj->submit();

    if(!$result) {
        redirect($pay_obj->error());
    } else {
        //支付完成后跳转
        $next_url = url("product/member/ac/pay/op/succeed/orderid/$orderid");
        if(DEBUG) redirect('global_op_succeed', $next_url);
        location($next_url);
    }

} else {

    $orderid = _input('orderid', null, MF_INT);
    if(!$orderid) redirect('非法操作，请返回！', url("product/member/ac/m_order"));
    if(!$detail = $O->read($orderid, FALSE,'e.*')) redirect('订单不存在或者是个无效订单，请返回！', url("product/member/ac/m_order"));
    if($detail['buyerid'] != $user->uid) redirect('这不是您的订单！');
    ($detail['status'] == '2' || $detail['status'] == '4') && $op = 'succeed';

    if($op == 'succeed' && $detail['status'] >= 2 and $detail['status'] <= 5) {
        $message = $detail['paymentname']=='cod' ? '发货请求已通知!' : '订单支付成功！';
        $urlpath[] = url_path('订单支付完成');
        $tplname = 'product_pay_succeed';
    } else {
        $urlpath[] = url_path('订单付款');
        if($detail['status']!='1') redirect('订单已完成支付，或订单状态异常，无法进行支付！');

        $PAY = _G('loader')->model(':pay');
        $offlinepay = $O->get_offline_pay_des($detail['sid']);
        $tplname = 'product_pay_submit'; 
    }
    include template($tplname);
    output();
}

/* end */