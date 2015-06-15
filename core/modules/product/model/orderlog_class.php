<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_orderlog extends ms_model {

    var $table = 'dbpre_product_orderlog';
    var $key = 'logid';
    var $model_flag = 'product';
    
    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

    function init_field() {
        $this->add_field('orderid,operator,order_status,changed_status,remark,log_time');
        $this->add_field_fun('orderid,log_time', 'intval');
        $this->add_field_fun('operator,order_status,changed_status,remark', '_T');
    }

    function read($logid) {
        $this->db->from($this->table);
        $this->db->select('*');
        $this->db->where('logid',$logid);
        $result = $this->db->get_one();
        return $result;
    }

    function find($select, $where, $order_by, $start, $offset, $total = TRUE) {
        $this->db->from($this->table);
        $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) return $result;
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select);
        $this->db->order_by($order_by);
        $this->db->limit($start,$offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function save($post,$logid=NULL) {
        $post['log_time'] = $this->global['timestamp'];
        $logid = parent::save($post,$logid);
        return $logid;
    }
    
    function change_save($post,$statusnum=NULL) {
        $post['log_time'] = $this->global['timestamp'];
        $logid = parent::save($post,$logid);
        $this->return_p($post['orderid']);
        if($statusnum) $this->change_status($post['orderid'], $statusnum);
        //退款通知

        return $logid;
    }

    function change_status($orderid,$statusnum) {
        $this->db->from('dbpre_product_order');
        $this->db->where('orderid',$orderid);
        $this->db->set('status',$statusnum);
        $this->db->update();
    }

    function delete($logids) {
        $logids = parent::get_keyids($logids);
        $this->db->from($this->table)
            ->where('logid',$logids)
            ->delete();
    }

    //返还库存/减少销量/返还消费积分/返还已支付费用
    function return_p($orderid) {
        $O =& $this->loader->model('product:order');
        if(!$detail = $O->read($orderid)) redirect('无效的订单！');
        $this->db->from('dbpre_product_ordergoods');
        $this->db->where('orderid',$orderid);
        if(!$row = $this->db->get()) return;
        while ($value = $row->fetch_array()) {
            //返还产品库存，减少销量
            $this->sale_num_add($value['pid'], $value['quantity']);
        }
        $sn = $detail['ordersn'];
        //返还已支付的费用和消费积分
        if($detail['status'] >=1 && $detail['status'] <=4) {
            $P =& $this->loader->model('member:point');
            //返还消费积分
            if($detail['integral'] > 0) {
                $P->update_point2($detail['buyerid'], $detail['integral_pointtype'], $detail['integral'], 
                    lang('product_update_point_order_cancel', $sn)
                );
            }
            //返还已支付的费用
            if($detail['status'] >= 2) {
                //货到付款给商家的和线下付款给商家的，不在线退款
                if(!$detail['is_cod'] && in_array($detail['is_offline_pay'],array('null','admin'))) {
                    if($detail['order_amount'] > 0) {
                        $P->update_point2($detail['buyerid'], 'rmb', $detail['order_amount'], 
                            lang('product_update_price_order_cancel', $sn)
                        );
                    }
                }
            }
        }
        $this->_notice_refund($detail); //退款提醒
    }
    
    function sale_num_add($pid,$num) {
        if(!$pid || $pid < 1) return;
        $this->db->from('dbpre_product');
        $this->db->set_dec('sales', $num);
        $this->db->set_add('stock', $num);
        $this->db->where('pid', $pid);
        $this->db->update();
    }

    //退款提醒
    function _notice_refund($order) {
        if(!$order) return;
        $c_ordersn = '<a href="'.url("product/member/ac/m_order/op/detail/orderid/$order[orderid]").'">'.$order['ordersn'].'</a>';
        $note = lang('product_notice_refund', array($c_ordersn));
        $this->loader->model('member:notice')->save($order['buyerid'], 'product', 'refund', $note);
    }

}

/* end */