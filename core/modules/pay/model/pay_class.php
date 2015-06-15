<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_pay extends ms_model {
    
    var $table = 'dbpre_pay';
    var $key = 'payid';
    var $model_flag = 'pay';

    var $modcfg = array();
    var $payments = array(); //保存接口列表
    var $cz_enable = false; //是否可以进行充值操作（至少存在一个支付接口）

    var $payid = 0;    //当前正在处理的支付记录ID
    var $payment = null; //当前支付接口类

    //电脑常规支付接口
    public static $pc_payment_list = array('alipay','tenpay','chinabank','paypal');
    //手机网站支付接口
    public static $mobile_payment_list = array('alipay_mobile');

    function __construct() {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        foreach(array_merge(self::$pc_payment_list, self::$mobile_payment_list) as $payment) {
            $this->modcfg[$payment] and $this->payments[] = $payment;
        }
        $this->cz_enable = !empty($this->payments);
    }

    function read_ex($order_flag, $orderid) {
        if(!$order_flag||!$orderid) return;
        $this->db->from($this->table);
        $this->db->where('order_flag', $order_flag);
        $this->db->where('orderid', $orderid);
        return $this->db->get_one();
    }

    function has_pc_payment($payment = '')
    {
        $list = $this->get_pc_payments();
        if(!$payment && $list) return true;
        if($payment && in_array($payment, $list)) return;
        return false;
    }

    function has_mobile_payment($payment = '')
    {
        $list = $this->get_mobile_payments();
        if(!$payment && $list) return true;
        if($payment && in_array($payment, $list)) return;
        return false;
    }

    function get_pc_payments()
    {
        $list = array();
        foreach (self::$pc_payment_list as $payment) {
            $this->modcfg[$payment] and $list[] = $payment;
        }
        return $list;
    }

    function get_mobile_payments($page_mod = '')
    {
        $list = array();
        foreach (self::$mobile_payment_list as $payment) {
            $this->modcfg[$payment] and $list[] = $payment;
        }
        return $list;
    }

    //创建支付并跳转到支付页面
    function create_pay($post) {
        if(!$this->cz_enable) redirect('pay_disabled');
        $post['creation_time'] = $this->timestamp;
        if(!$post['order_flag']) redirect('未指定支付来源模块，请联系管理员。');
        if(!$post['orderid']) redirect('未指定支付订单号，请联系管理员。');

        if($pay = $this->read_ex($post['order_flag'], $post['orderid'])) {
            $pay['pay_status'] and redirect('支付已完成，不能再次提交。');
            //可以更新下支付接口记录里的数据
            $this->db->from($this->table);
            
            if($post['notify_url']) $this->db->set('notify_url', $post['notify_url']);
            if($post['callback_url']) $this->db->set('callback_url', $post['callback_url']);
            if($post['callback_url_mobile']) $this->db->set('callback_url_mobile', $post['callback_url_mobile']);
            if($post['order_name']) $this->db->set('order_name', $post['order_name']);
            if($post['payment_name']) $this->db->set('payment_name', $post['payment_name']);
            if($post['price']) $this->db->set('price', $post['price']);
            if(isset($post['royalty'])) $post['royalty'] = '';
            $this->db->set('royalty', $post['royalty']);

            $this->db->where('payid', $pay['payid']);
            $this->db->update();
            $this->payid = $pay['payid'];
        } else {
            if(!$post['order_name']) redirect('您未填写订单名称，请联系管理员。');
            if(!$post['payment_name'] && !$this->check_payment($post['payment_name'])) redirect('未知的支付接口，请联系管理员。');
            if(!$post['price'] && $post['price'] <= 0) redirect('对不起，未填写正确的支付金额。');
            if(!$post['notify_url']) redirect('未设置订单支付完成通知地址，请联系管理员。');
            if(!$post['callback_url']) redirect('未设置订单返回地址，请联系管理员。');
            $this->payid = parent::save($post);
        }
        $this->goto_pay($post['payment_name']);
    }

    /**
     * 实例化一个支付接口类
     * @param  string $payment 接口标识
     * @return mixed
     */
    function instante_payment($payment) {
        if(!$payment || !in_array($payment, $this->payments)) redirect('pay_payment_unselect');
        $this->payment = mc_pay_payment::factory($payment);
        if(!$this->payment) {
            show_error('pay_payment_unselect');
        }
    }

    function goto_pay($payment) {
        $this->instante_payment($payment);
        $this->payment->goto_pay($this->payid, $this->create_unique());
    }

    /**
     * 支付接口检查
     * @param  string $payment 接口标识
     * @return [type]          [description]
     */
    function check_payment($payment) {
        if(!$payment || !in_array($payment, $this->payments)) redirect('pay_payment_unselect');
    }

    //接口方返回支付情况，本地监测，并更新订单状态
    function pay_notify($payment) {
        $this->instante_payment($payment);
        $succeed = $this->payment->notify_check();
        if($succeed) {
            //获取ID必须放在 notify_check 后面，有些接口的notify_check函数会解析异步通知信息里的相关订单ID
            $this->get_payid();
            $this->succeed_pay();
        }
        if($this->payment->notify_return) {
            $this->payment->notify_return();
        }
    }

    //支付返回
    function pay_return($payment) {
        $this->instante_payment($payment);
        $succeed = $this->payment->return_check();
        $this->get_payid();
        if($succeed) $this->succeed_pay();
        $pay = $this->read($this->payid);
        if(empty($pay)) {
            return $this->add_error('订单['.$this->payid.']不存在。');
        }
        //返回手机web地址
        if(is_mobile() && check_module('mobile') && $pay['callback_url_mobile']) {
            return $pay['callback_url'];
        }
        return $pay['callback_url'] ? $pay['callback_url'] : true;
    }

    //更新支付记录
    function succeed_pay() {
        $pay = $this->read($this->payid);
        if(!$pay) {
            log_write('pay', date('Y-m-d H:i:s', $this->timestamp)."订单【payid：{$this->payid}】不存在");
            return ; //也要记录下
        }
        if($pay['pay_status']) {
            log_write('pay', date('Y-m-d H:i:s', $this->timestamp)."订单【payid：{$this->payid}】已完成支付");
            if($pay['my_status'] < 1 && $pay['notify_url']) {
                $this->send_notify($pay['notify_url']);
            }
        } else {
            $this->db->from($this->table);
            $this->db->where('payid', $this->payid);
            $this->db->set('pay_status', 1);
            $this->db->set('pay_time', $this->timestamp);
            $this->db->set('payment_orderid', $this->get_payment_orderid());
            $this->db->update();
            //向订单发送支付成功的通知，让订单维护代码更新支付后的流程
            if($pay['my_status'] < 1 && $pay['notify_url']) {
                $this->send_notify($pay['notify_url']);
            }
        }
        //如果更新支付信息后结束
        if($this->payment->notify_end) {
            location($pay['callback_url']);
        }
    }

    //打开执行订单完成逻辑
    function send_notify($notify_url) {
        $notify_url = str_replace('&amp;', '&', $notify_url);
        if(strpos($notify_url,'http://') === false) {
            $notify_url = _G('cfg','siteurl') . trim($notify_url,'/');
        }
        $content = trim($this->do_get($notify_url));

        log_write('pay', 'send_notify:'.$notify_url."\r\n".$content);
        
        //新版返回值是json组成 { code:200 }
        if(strlen($content) > 10) {
            $json = @json_decode($content);
            if($json && $json->code == 200) {
                $content = 'succeed';
            }
        }
        if($content != 'succeed') {
            //模块订单更新操作返回失败(更新支付表的状态:-1)
            $this->update_mystatus($this->payid, -1);
            //记录下不返回succeed时的内容，便于排查
            log_write('pay', 'send_notify:'.$notify_url."\r\n".$content);
        } else {
            //订单逻辑执行成功，更新支付表的状态:1
            $this->update_mystatus($this->payid, 1);
        }
    }

    //更新自定义状态
    function update_mystatus($payid, $status) {
        $this->db->from($this->table);
        $this->db->where('payid', $payid);
        $this->db->set('my_status', $status);
        $this->db->update();
    }

    //生成一个惟一订单标识
    function create_unique() {
        $s = date('Ymd') . $this->payid;
        return $s;
    }

    //解析惟一标识里的payid（主要是防止重复安装模块时payid会和以前安装时的id相同，
    //造成支付平台出现商家订单号冲突，所以需要一个惟一的标识作为商家订单号发送到支付平台）
    function parse_payid($unid) {
        $payid = substr($unid, 8);
        return $payid;
    }

    //从支付通知里获取订单的id
    function get_payid() {
        $this->payid = (int)$this->parse_payid($this->payment->get_unid());
    }

    //从支付通知里获取支付接口的订单id,仅作记录
    function get_payment_orderid() {
        $id = _T($this->payment->get_payment_orderid());
        return $id ? $id : '';
    }

    function do_get($url) {
        $url = str_replace('&amp;', '&', $url);
        if(strpos($url,'?')===false) {
            $url.='?x='.mt_rand();
        } else {
            $url.='&x='.mt_rand();
        }
        if(function_exists('curl_exec')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $result =  curl_exec($ch);

            //错误记录
            $error = curl_error($ch);
            if($error) {
                log_write('pay', $url . "\r\npay_class::do_get\r\n" . $error);
            }

            curl_close($ch);
            return $result;
        } else {
            echo '<h3>PHP function (curl_exec) does not exist.</h3>';
            exit();
        }
    }

    function do_post($url, $params) {
        $fields_string = '';
        foreach($params as $key=>$value){
            $fields_string .="{$key}={$value}&";
        }
        rtrim($fields_string,'&');
        $xurl = $url . '?' . $fields_string;
        if(!function_exists('curl_exec')) {
            echo '<h3>PHP function (curl_exec) does not exist.</h3>'; exit;
        }
        $ch = curl_init ($url."?");
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);     
        $result = curl_exec($ch);

        //错误记录
        $error = curl_error($ch);
        if($error) {
            log_write('pay', $url . "\r\npay_class::do_post\r\n" . $error);
        }

        curl_close ($ch);  
        return $result;
    }

}
