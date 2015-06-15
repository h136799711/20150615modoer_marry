<?php
/**
* 通知行为
*/
class mc_product_notice
{

    //付款提醒
    public static function pay($order)
    {
        if(!$order) return;
        //ac/g_order/sid/2/status/2
        $c_username = '<a href="'.url("space/index/uid/$order[buyerid]").'" target="_blank">'.$order['buyername'].'</a>';
        $status = $order['is_cod']?'3':'2';
        $c_ordersn = '<a href="'.url("product/member/ac/g_order/sid/$order[sid]/status/$status").'">'.$order['ordersn'].'</a>';
        $note = lang('product_notice_pay_succeed', array($c_username, $c_ordersn));
        _G('loader')->model('member:notice')->save($order['sellerid'], 'product', 'pay', $note);
        //短信提醒
        $order['sellerid'] > 0 && self::send_sms($order['sellerid'],
            lang('product_sms_pay_succeed_sell',array($order['ordersn'])));
        $order['buyerid'] > 0 && self::send_sms($order['buyerid'],
            lang('product_sms_pay_succeed_buy',array($order['ordersn'])));
    }

    //已发货提醒
    public static function send_goods($order)
    {
        if(!$order) return;
        $c_ordersn = '<a href="'.url("product/member/ac/m_order/status/4").'">'.$order['ordersn'].'</a>';
        $note = lang('product_notice_send_goods', $c_ordersn);

        _G('loader')->model('member:notice')->save($order['buyerid'], 'product', 'send_goods', $note);
    }

    //订单已完成提醒
    public static function deal_close($order)
    {
        if(!$order) return;
        $c_username = '<a href="'.url("space/index/uid/$order[buyerid]").'" target="_blank">'.$order['buyername'].'</a>';
        $c_ordersn = '<a href="'.url("product/member/ac/g_order/sid/$order[sid]/status/5").'">'.$order['ordersn'].'</a>';
        $note = lang('product_notice_deal_close', array($c_username, $c_ordersn));
        _G('loader')->model('member:notice')->save($order['sellerid'], 'product', 'deal_close', $note);
        //短信提醒
        $order['sellerid'] > 0 && self::send_sms($order['sellerid'],
            lang('product_sms_deal_close',array($order['ordersn'])));
    }

    //调整费用提醒
    public static function change_amount($order, $orderamount)
    {
        if(!$order) return;
        //member-ac-pay-orderid-47
        $c_ordersn = '<a href="'.url("product/member/ac/pay/orderid/$order[orderid]").'">'.$order['ordersn'].'</a>';
        $c_amount = '&yen;' . $orderamount;
        $note = lang('product_notice_change_amount', array($c_ordersn, $c_amount));

        _G('loader')->model('member:notice')->save($order['buyerid'], 'product', 'change_amount', $note);
    }

    public static function send_sms($uid, $message)
    {
        //log_write('product_sms',"$uid\n$message");
        if(!check_module('sms')||!S('product:send_sms')) return;
        $member = _G('loader')->model(':member')->read($uid);
        if(!$member||!$member['mobile']) return;

        _G('loader')->model('sms:factory',false);
        $sms = msm_sms_factory::create();
        if(!$sms) return;
        return $sms->send($member['mobile'], $message);
    }

}