<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_pay_log extends ms_model {

    var $table = 'dbpre_pay_log';
    var $key = 'orderid';
    var $model_flag = 'pay';

    var $modcfg = array();
    var $cz_type = array();

    public static function get_cz_type()
    {
        $cz_type = S('pay:cz_type');
        if($cz_type) $cz_type = @unserialize($cz_type);
        return $cz_type&&is_array($cz_type) ? $cz_type : false;
    }

    function __construct() {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->cz_type = @unserialize($this->modcfg['cz_type']);
    }

    //通过账号现金余额兑换积分
    //$rmb 需要扣除的现金;$type 兑换成积分类型，$coin 积分数量
    function exchange($rmb, $type, $point) {
        if($rmb <= 0) redirect('支付金额不能小于等于0');
        if($point <= 0) redirect('兑入积分不能小于等于0');
        if($type=='rmb') redirect('对不起，兑换双方类型相同，不能进行兑换。');
        if($this->global['user']->rmb < $rmb) redirect('抱歉，您的账号现金不足，无法进行兑换。');
        $MP = $this->loader->model('member:point');
        if(!$MP->is_valid_type($type)) redirect('对不起，兑入积分类型无效。');
        //扣除
        $MP->update_point2($this->global['user']->uid, 'rmb', -$rmb, '现金兑换积分');
        //增加
        $MP->update_point2($this->global['user']->uid, $type, $point, '现金兑换积分');
    }

    function create($post, $orderid=null, $is_mobile = false) {

        $edit = $orderid != null;
        if($edit) {
            if(!$detail = $this->read($orderid)) redirect('pay_order_empty');
            if($detail['uid'] != $this->global['user']->uid) redirect('pay_order_owner_invalid');
            if($order['status'] == 1) redirect('pay_order_error_status_1');
            if($order['status'] == 2) redirect('pay_order_error_status_2');
        }

        if(!$this->cz_type) redirect('pay_disabled');
        if(!is_numeric($post['czprice']) || $post['czprice'] <= 0 || $post['czprice'] < $this->modcfg['czmin']) {
            redirect(lang('pay_price_min', $this->modcfg['czmin']));
        } elseif($czprice > $this->modcfg['czmax']) {
            redirect(lang('pay_price_max', $this->modcfg['czmax']));
        } elseif(!$post['cztype'] || !in_array($post['cztype'], $this->cz_type)) {
            redirect('pay_cztype_empty');
        }

        if($post['cztype']=='rmb') {
            $ratio = 1;
        } else {
            $ratio = $this->modcfg['ratio_'.$post['cztype']];
        }
        if(!is_numeric($ratio) || $ratio <= 0) {
            redirect('pay_ratio_empty');
        }
        //计算这算的积分
        if($post['cztype'] == 'rmb') {
            $point = $post['czprice'];
        } else {
            $point = $post['czprice'] * $ratio;
        }
        if($point < 1 && $post['cztype']!='rmb') redirect(lang('pay_point_empty', display('member:point',"point/$post[cztype]")));

        //支付接口类型
        $payment = _post('payment', '', MF_TEXT);
        if($payment == 'rmb') {
            $payword = _post('payword','');
            if(!$payword) {
                redirect('对不起，您未填写支付密码！');
            } elseif(!$this->global['user']->check_paypw($payword)) {
                redirect('对不起，您填写的支付密码不正确！');
            }
            //通过账号余额转账的
            $this->exchange($post['czprice'], $post['cztype'], $point);
            redirect('pay_succeed',url('pay/member/ac/pay'));
            return;
        }

        //接口检查
        $P = $this->loader->model(':pay');
        if(!$P->cz_enable) redirect('pay_disabled');
        //判断接口
        $P->check_payment($payment);

        //新建交易订单
        $insert = array();
        !$edit && $insert['uid'] = $this->global['user']->uid;
        !$edit && $insert['username'] = $this->global['user']->username;
        $insert['price'] = $post['czprice'];
        $insert['point'] = $point;
        $insert['cztype'] = $post['cztype'];
        !$edit && $insert['dateline'] = $this->global['timestamp'];
        $insert['exchangetime'] = 0;
        $insert['status'] = 0;
        $insert['ip'] = $this->global['ip'];
        //加入数据库，返回订单号
        $orderid = parent::save($insert, $orderid);
        //跳转支付页面
        //pay_order_title' => '%s_%s_%s充值',//网站名称,会员名,积分类型
        
        $notify_url = U("pay/recharge_notify/orderid/$orderid", true, 'normal');
        $callback_url = U("pay/member/ac/pay/op/return/orderid/$orderid", true, 'normal');
        if($is_mobile) {
            $callback_url = U("pay/mobile/do/recharge/op/return/orderid/$orderid", true, 'normal');
        }

        $notify_url = str_replace('&nbsp;', '&', $notify_url);
        $callback_url = str_replace('&nbsp;', '&', $callback_url);

        $post = array(
            //订单标识，用于区别moder内部各个模块的orderid
            'order_flag' => 'pay_recharge',
            //订单号
            'orderid' => $orderid,
            //订单的标题
            'order_name' => lang('pay_order_title', array(_G('cfg','sitename'), 
                $this->global['user']->username, lang('pay_type_point'))),
            //支付用户uid
            'uid' => $this->global['user']->uid,
            //接口标识
            'payment_name' => $payment,
            //价格单位元
            'price' => $post['czprice'],
            //分润功能（仅支持支付宝） 例如：111@126.com^0.01^分润备注一|222@126.com^0.01^分润备注二
            'royalty' => '',
            //此连接用于在支付成功后让关联订单代码执行订单支付后的逻辑（PHP函数get方式服务器端后台执行）
            'notify_url' => $notify_url,
            //此连接用户支付完毕后跳转返回的连接地址（客户端浏览器打开）
            'callback_url' => $callback_url,
            'goods' => '',
        );
        //生成支付接口记录，并跳转到支付页面
        $P->create_pay($post);
    }

    //此处由上一个函数里的“notify_url”地址进入时触发
    function pay_succeed($orderid) {
        $P = $this->loader->model(':pay');
        //获取支付接口记录
        $pay = $P->read_ex('pay_recharge', $orderid);
        //判断支付接口记录是否存在和状态
        if(!$pay) redirect("支付记录不存在。(OID:$orderoid)");
        if(!$pay['pay_status']) redirect("等待支付状态，如果您已经完成支付，请稍后再查看。(OID:$orderoid)");
        if($pay['my_status'] < 0) return; //已经处理过了
        if(!$order = $this->read($orderid)) redirect('pay_order_empty'); //查找订单
        if($order['status'] == 1) return; //已经处理过了（也可以省略，前面pay表的my_status已经接管了）

        $update = array();
        if($order['price'] != $pay['price']) {
            $update['price'] = $pay['price'];
            //取得新的兑换比率
            $update['point'] = $this->_ratio($pay['price'], $order['cztype']);
            $point = $update['point'];
        } else {
            if($order['cztype']=='rmb') {
                $point = $order['price'];
            } else {
                $point = $order['point'];
            }
        }
        if($pay['payment_orderid']) {
            $update['port_orderid'] = $pay['payment_orderid'];
        }
        $update['exchangetime'] = $this->global['timestamp'];
        $update['status'] = 1;
        //会员增加对应积分
        $this->_czPoint($order['uid'], $point, $order['cztype']);
        //更新订单状态
        $this->db->from($this->table);
        $this->db->set($update);
        $this->db->where('orderid', $orderid);
        $this->db->update();
        //更新记录表自定义状态
        $P->update_mystatus($pay['payid'], 1);
    }

    function update_status($uid=null) {
        $hour = $this->modcfg['staletime'] > 0 ? $this->modcfg['staletime'] : 24;
        $endtime = strtotime(date('Y-m-d',$this->global['timestamp'] - $hour*3600));
        $this->db->from($this->table);
        $uid > 0 && $this->db->where('uid', $uid);
        $this->db->set('status', 2); //表示过期的订单
        $this->db->where('status', 0); //表示未支付的订单 
        $this->db->where_less('dateline',$endtime);
        $this->db->update();
    }

    //支付成功后，给会员充值积分
    function _czPoint($uid, $point, $type='rmb') {
        if(!$uid) return;
        if(!in_array($type, $this->cz_type)) return;

        $PT =& $this->loader->model('member:point');
        $PT->update_point2($uid, $type, $point, lang('member_point_pay_des'));
        return;
    }

    //计算汇率
    function _ratio($price, $type) {
        //计算这算的积分
        if($type == 'rmb') {
            $point = $price;
        } else {
            $point = $price * (int) $this->modcfg['ratio_'.$type];
        }
        return $point;
    }
}
?>