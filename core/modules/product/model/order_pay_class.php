<?php
/**
* 订单支付业务代码
*/
class msm_product_order_pay extends msm_product_order
{
    //买家支付提交
    public function submit()
    {
        $payment = _post('payment', '', MF_TEXT);
        $orderid = _post('orderid', 0, MF_INT_KEY);

        if($payment == 'balance' || $payment == 'cod') {
            if(!$this->check_password()) return false;
            $fun = 'pay_'.$payment;
            if(!$order = $this->order_check($orderid)) return;
            //执行支付业务
            if(!$this->$fun($order)) return;
            //提醒卖家
            if($order['orderstyle'] == '1') {
                //实物商品提醒卖家发货，虚拟的不需要提醒（自动发货）
                mc_product_notice::pay($order);
            }
            return true;
        } elseif($payment) {
            return $this->pay_online($orderid, $payment);
        } else {
            return $this->add_error('无效的支付方式。');
        }
    }

    /**
     * 在线支付处理
     * 生成在线支付接口记录，进入支付页面
     * @param  [type] $orderid [description]
     * @param  [type] $payment [description]
     * @return [type]          [description]
     */
    function pay_online($orderid, $payment) {
        $detail = $this->order_check($orderid);
        //货到付款的不能在线支付
        if($detail['is_cod']) return $this->add_error('您的订单是货到付款类型，不能用于在线支付。');
        $P = $this->loader->model(':pay');
        $S = $this->loader->model('item:subject');
        if(!$subject = $S->read($detail['sid'])) redirect('item_empty');
        $post = array(
            //订单标识，用于区别moder内部各个模块的orderid
            'order_flag' => 'product',
            //订单号
            'orderid' => $orderid,
            //订单的标题
            'order_name' => '会员(UID:'._G('user')->uid.'),商城订单：' . $orderid,
            //支付用户uid
            'uid' => _G('user')->uid,
            //接口标识
            'payment_name' => $payment,
            //价格单位元
            'price' => $detail['order_amount'],
            //分润
            'royalty' => '',
            //货物信息
            'goods' => '',
            //此连接用于在支付成功后让关联订单代码执行订单支付后的逻辑（PHP函数get方式服务器端后台执行）
            'notify_url' => U("product/pay_notify/oid/$orderid", true),
            //此连接用户支付完毕后跳转返回的连接地址（客户端浏览器打开）
            'callback_url' => U("product/member/ac/m_order/op/detail/orderid/$orderid", true),
            'callback_url_mobile' => U("product/mobile/do/myorder/orderid/$orderid", true),
        );
        //设定订单的支付方式
        $this->up_payname($orderid,$payment);
        //生成支付接口记录，并跳转到支付页面
        $P->create_pay($post);
    }

    //支付模块通知在线支付成功，进入下单提交流程
    function pay_online_succeed($orderid) {
        $order = $this->read($orderid);
        if(!$order) return $this->add_error(lang('product_order_empty')."(ORDERID:$orderid)");
        $pay_obj = $this->loader->model(':pay');
        //获取支付接口记录
        $pay = $pay_obj->read_ex('product', $orderid);
        //判断支付接口记录是否存在和状态
        if(!$pay) return $this->add_error("支付记录不存在。(ORDERID:$orderid)");
        if(!$pay['pay_status']) return $this->add_error("等待支付状态，如果您已经完成支付，请稍后再查看。(ORDERID:$orderid)");
        if($pay['my_status']) return $this->add_error("订单已处理，不能重复提交。"); //已经处理过了
        //先把钱充值到会员现金账户，避免下面提交时出现问题，现金丢失
        $this->_pay_online_recharge($pay['uid'], $pay['price']);
        //更新记录表自定义状态，避免重复充值
        $pay_obj->update_mystatus($pay['payid'], '1');
        //开始下单，扣现金
        return $this->pay_balance($order);
    }

    /**
     * 卖家确认收到线下支付款项
     * @param  string $ordersn 购物车订单号
     * @param  int $orderid   订单表自增ID号(订单号)
     * @return boolean          成功返回true反之false，$this->error() 可获得错误信息
     */
    public function confirm_offline_pay($ordersn, $orderid)
    {
        $order = $this->order_check($orderid, false);
        if(!$order) return;
        //货到付款的不能在线支付
        if($order['is_cod']) return $this->add_error('您操作的订单是货到付款类型，线下付款确认。');
        //支付确认人员角色
        $role = $this->in_admin ? 'admin' : 'owner'; //后台管理员 Or 店长
        $role_username = $this->in_admin ? _G('admin')->adminname : _G('user')->username;
        if(!$this->in_admin) {
            //判断是否店长帐号在操作
            if(!$this->loader->model('item:subject')->is_mysubject($order['sid'], _G('user')->uid)) {
                return $this->add_error('抱歉，您不是该商品管理员，无法确认订单。');
            }
        }
        //更新订单状态
        $set = array(
            'paytime' => _G('timestamp'),   //支付时间
            'status' => 2,  //已付款
            'paymentname' => 'offline', //支付方式为线下支付
            'is_offline_pay' => $role,  //确认支付角色
            'offline_pay_role' => $role_username, //帐号
        );
        $this->db->from($this->table)->set($set)->where('orderid', $order['orderid'])->update();
        //自动发货虚拟卡密
        if($order['orderstyle'] == '2') {
            $this->_send_serial($order);
        } else {
            //提醒买家发货
            mc_product_notice::pay($order);
        }
        return true;
    }

    //检测支付密码
    protected function check_password()
    {
        //密码验证
        if(!_post('password')) {
            return $this->add_error('对不起，您未填写帐号密码！');
        } elseif(!_G('user')->check_paypw(_post('password'))) {
            return $this->add_error('对不起，您填写的密码不正确！');
        }
        return true;
    }

    /**
     * 余额支付
     * @param  array $order 订单记录
     * @return boolean        支付成功true，反之false
     */
    protected function pay_balance($order)
    {
        //货到付款的不能在线支付
        if($order['is_cod']) return $this->add_error('您的订单是货到付款类型，不能用于在线支付。');
        //扣除账户金额
        if($order['order_amount']) {
            $this->deduct_price($order['ordersn'], $order['buyerid'], $order['order_amount']);
        }
        //更新订单状态为已支付
        $set = array(
            'paytime' => _G('timestamp'),   //支付时间
            'status' => 2,  //已支付状态
            'paymentname' => 'balance', //支付方式位余额支付
        );
        //更新订单
        $this->db->from($this->table)->set($set)->where('orderid', $order['orderid'])->update();

        //自动发货虚拟卡密
        if($order['orderstyle']=='2') {
            $this->_send_serial($order);
        }
        return true;
    }

    /**
     * 货到付款，要求发货
     * @param  array $order 订单记录
     * @return boolean        支付成功true，反之false
     */
    protected function pay_cod($order)
    {
        if(!$order['is_cod']) {
            return $this->add_error('您的订单不是货到付款类型。');
        }
        //更新订单状态
        $set = array(
            'paytime' => _G('timestamp'),   //支付时间
            'status' => 3,  //等待发货
            'paymentname' => 'cod', //支付方式位余额支付
        );
        $this->db->from($this->table)->set($set)->where('orderid', $order['orderid'])->update();

        return true;
    }

    /**
     * 支付前订单验证
     * @param  int  $orderid    订单号
     * @param  boolean $check_self 是否检测是自己的订单
     * @return array              验证成功返回订单数据，反之则返回fasle
     */
    protected function order_check($orderid, $check_self = TRUE) {
        $order = $this->read($orderid);
        if(!$order) return $this->add_error('product_order_empty');
        //判断订单是不是自己的
        if($check_self) {
            if($order['buyerid'] != _G('user')->uid) {
                return $this->add_error('抱歉，该订单不是您的订单！');
            }
        }
        //判断是否已经支付
        if($order['paytime']) return $this->add_error('订单已经支付，请不要重复提交！');
        //已经支付过
        if($order['status']=='2') return $this->add_error('订单已经完成支付。');
        return $order;
    }

    /**
     * 从帐号余额中扣除
     * @param  string $ordersn 订单号
     * @param  int $uid     帐号UID
     * @param  float $price   扣除金额
     * @return boolean          是否成功扣除
     */
    protected function deduct_price($ordersn, $uid, $price) {
        //判断会员余额
        $member = $this->loader->model('member:member')->read($uid);
        if($price > $member['rmb']) {
            return $this->add_error('抱歉，您的账户余额不足以支付此订单！');
        }
        $PT = $this->loader->model('member:point');
        $PT->update_point2($uid, 'rmb', -$price, lang('product_update_point_payrmb_des', $ordersn));
        return true;
    }

    //在线支付成功后，先把钱充值进会员账号，避免订单提交失败后，钱丢失
    function _pay_online_recharge($uid, $price) {
        if(!$uid) return;
        //增加充值记录
        $PT = $this->loader->model('member:point');
        $PT->update_point2($uid, 'rmb', $price, lang('member_point_pay_des'));
    }

    //获取线下支付帐号
    function get_offline_pay_des($sid) {
        $PS = $this->loader->model('product:subjectsetting');
        $offlinepay = trim($PS->read($sid, 'offlinepay'));
        return $offlinepay;
    }

    //更新订单支付方式
    function up_payname($orderid, $payname) {
        if(!$orderid || $orderid < 1) return;
        $this->db->from($this->table)
            ->set('paymentname', $payname)
            ->where('orderid', $orderid)
            ->update();
    }

}