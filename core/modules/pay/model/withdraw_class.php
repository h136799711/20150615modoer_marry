<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_pay_withdraw extends ms_model {

    var $table = 'dbpre_pay_withdraw';
    var $key = 'id';
    var $model_flag = 'pay';

    var $min_price = 1;
    var $max_price = 500;
    var $limit_price = 1000;
    var $allow_count = 3;

    function __construct() {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->_init();
    }

    function _init() {
        if(is_numeric($this->modcfg['withdraw_min']) && $this->modcfg['withdraw_min'] > 0) {
            $this->min_price = $this->modcfg['withdraw_min'];
        }
        if(is_numeric($this->modcfg['withdraw_max']) && $this->modcfg['withdraw_max'] > 0) {
            $this->max_price = $this->modcfg['withdraw_max'];
        }
        if(is_numeric($this->modcfg['withdraw_limit']) && $this->modcfg['withdraw_limit'] > 0) {
            $this->limit_price = $this->modcfg['withdraw_limit'];
        }
        if(is_numeric($this->modcfg['withdraw_count']) && $this->modcfg['withdraw_count'] > 0) {
            $this->allow_count = $this->modcfg['withdraw_count'];
        }
    }

    function get_myinfo() {
        $uid = (int)$this->global['user']->uid;
        $datetime = strtotime(date('Y-m-1', $this->timestamp));
        $this->db->from($this->table);
        $this->db->select('price', 'price', 'SUM( ? )');
        $this->db->select('*', 'count', 'COUNT( ? )');
        $this->db->where('uid', $uid);
        $this->db->where_more('applytime', $datetime);
        $this->db->where('status',array('wait','succeed'));
        return $this->db->get_one();
    }

    function withdraw($price) {
        $price = cfloat($price);
        $this->check_post($price);
        //冻结用户帐号内的提现金额
        $P =& $this->loader->model('member:point');
        $P->update_point2($this->global['user']->uid, 'rmb', -$price, '会员提现申请');
        //添加提现记录
        $array = array();
        $array['uid'] = $this->global['user']->uid;
        $array['username'] = $this->global['user']->username;
        $array['price'] = $price;
        $array['charges'] = '0';
        $array['realname'] = $this->global['user']->realname;
        $array['accounts'] = $this->global['user']->alipay;
        $array['pointtype'] = 'rmb';
        $array['applytime'] = $this->timestamp;
        $array['ip'] = $this->global['ip'];
        $array['status'] = 'wait';
        $array['status_des'] = '';
        $array['optime'] = '';
        $array['opowner'] = '';
        $this->db->from($this->table);
        $this->db->set($array);
        $this->db->insert();
    }

    function update_status($id, $status, $des='') {
        if(!in_array($status,array('succeed','fail','cancel'))) {
            redirect('对不起，您提交的提现申请状态无法识别。');
        }
        if($status!='succeed' && !$des) {
            redirect('对不起，您未填写失败/取消的原因，请返回填写后再提交。');
        }
        $apply = $this->read($id);
        if(!$apply) redirect('对不起，您准备更新状态的提现申请不存在。');
        if($apply['status'] != 'wait') redirect("对不起，您提现申请已经被 $apply[opowner] 于 ".
            date('Y-m-d H:i',$apply['optime'])." 处理为 ".lang('pay_withdraw_status_'.$apply['status']));
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $this->db->set('status',$status);
        $this->db->set('status_des', $des);
        $this->db->set('optime', $this->global['timestamp']);
        $this->db->set('opowner', $this->global['admin']->adminname);
        $this->db->update();
        //失败或者取消，则把冻结的金额退回到会员帐号
        if($status == 'fail' || $status == 'cancel') {
            $P =& $this->loader->model('member:point');
            $P->update_point2($apply['uid'], 'rmb', $apply['price'], '会员提现失败或取消退款');
        }
        //发送提醒
        $this->loader->model('member:notice')->save($apply['uid'], 'pay', 'withdraw', 
            lang('pay_notice_apply', array($id, url("pay/member/ac/withdraw/op/log"))));
    }

    function check_post($price) {
        if($price <= 0 || !is_numeric($price)) redirect('未填写一个有效的体现金额。');
        $myinfo = $this->get_myinfo();
        if($myinfo['count']>0) {
            $allow_price = $this->limit_price - $myinfo['price'];
            $allow_count = $this->allow_count - $myinfo['count'];
        } else {
            $allow_count = $this->allow_count;
            $allow_price = $this->limit_price;
        }
        $allow_price = min($this->global['user']->rmb, $allow_price);

        if($price > $allow_price) {
            redirect("对不起，您提现的金额超过了 $allow_price 元。");
        }
        if($allow_count <= 0) {
            redirect("对不起，您今天的提现已满 $this->allow_count 次，无法提现。");
        }
        if($price < $this->min_price) {
            redirect("对不起，您提现的金额不能小于 $this->min_price 元。");
        }
        if($price > $this->max_price) {
            redirect("对不起，您提现的金额不能小于 $this->min_price 元。");
        }
        if($this->global['user']->rmb < $price) {
            redirect("对不起，您的帐号不足 $price 元，无法提现。");
        }
    }

}