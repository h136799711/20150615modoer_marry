<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_orderextm extends ms_model {

    var $table = 'dbpre_product_orderextm';
    var $key = 'orderid';
    var $model_flag = 'product';
	
    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

	function init_field() {
		$this->add_field('orderid,username,address,zipcode,mobile,shipid,shipname,shipfee');
		$this->add_field_fun('orderid,shipid', 'intval');
        $this->add_field_fun('username,address,mobile,shipname,zipcode', '_T');
        $this->add_field_fun('shipfee', 'floatval');
	}

	function read($orderid) {
        $this->db->from($this->table);
        $this->db->select('*');
        $this->db->where('orderid',$orderid);
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

	function save($orderid,$address,$shippings) {
        $S =& $this->loader->model('product:shipping');
        //$A =& $this->loader->model('member:address');
        //if(!$address = $A->read($exid)) redirect('收货人地址不存在！');
        $post = array();
        $post['orderid'] = $orderid;
        $post['username'] = $address['name'];
        $post['address'] = $address['addr'];
        $post['zipcode'] = $address['postcode'];
        $post['mobile'] = $address['mobile'];
        $post['shipid'] = $shippings['shipid'];
        $post['shipname'] = $shippings['shipname'];
        $post['shipfee'] = (int)$shippings['price'];
        $oid = parent::save($post);
        return $oid;
    }

    function delete($orderids) {
		$orderids = parent::get_keyids($orderids);
		$this->db->from($this->table)
			->where('orderid',$orderids)
			->delete();
	}
}