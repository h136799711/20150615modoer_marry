<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_ordergoods extends ms_model {

    var $table = 'dbpre_product_ordergoods';
    var $key = 'gid';
    var $model_flag = 'product';
	
    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

	function init_field() {
		$this->add_field('orderid,pid,pname,price,quantity,goods_image');
		$this->add_field_fun('orderid,pid,quantity', 'intval');
        $this->add_field_fun('pname,goods_image', '_T');
        $this->add_field_fun('price', 'floatval');
	}

	function read($gid,$is_orderid=false) {
        $this->db->from($this->table);
        $this->db->select('*');
        $is_orderid?$this->db->where('orderid',$gid):$this->db->where('gid', $gid);
        $result = $this->db->get_one();
        return $result;
    }

    function getinfo($pid,$orderid) {
        $this->db->from($this->table);
        $this->db->select('*');
        $this->db->where('orderid',$orderid);
        $this->db->where('pid',$pid);
        $result = $this->db->get_one();
        return $result;
    }

    function get_by_order($orderid) {
        return $this->db->from($this->table)->where('orderid',$orderid)->get_all();
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

	function save($post,$gid=NULL) {
        $P =& $this->loader->model('product:product');
        if(!$detail = $P->read($post['pid'])) redirect('product_empty');
        $gid = parent::save($post,$gid);
        $this->sale_num_add($post['pid'], $post['quantity']);
        return $gid;
    }

    function savep($orderid, $goods_list) {
        //商品只有1个时
        if(isset($goods_list['pid'])) {
            $goods_list = array($goods_list);
        }
        foreach ($goods_list as $goods) {
            $this->db->from($this->table);
            $this->db->set('orderid', $orderid);
            $this->db->set('pid', $goods['pid']);
            $this->db->set('pname', $goods['pname']);
            $this->db->set('price', $goods['price']);
            $this->db->set('quantity', $goods['quantity']);
            $this->db->set('buyattr', $goods['buyattr']&&is_array($goods['buyattr']) ? serialize($goods['buyattr']) : '');
            $this->db->set('goods_image', $goods['goods_image']);
            $this->db->insert();
            //更新库存和销量
            $this->sale_num_add($goods['pid'], $goods['quantity']);
        }
    }

    function delete($gids) {
		$gids = parent::get_keyids($gids);
		$this->db->from($this->table)
			->where('gid',$gids)
			->delete();
	}

	//增加产品销售数量并减少库存
    function sale_num_add($pid,$num) {
        if(!$pid || $pid < 1) return;
        $this->db->from('dbpre_product');
        $this->db->set_add('sales', $num);
        $this->db->set_dec('stock', $num);
        $this->db->where('pid', $pid);
        $this->db->update();
    }

    //减少产品销售数量并返还库存
    function sale_num_dec($pid,$num) {
        if(!$pid || $pid < 1) return;
        $this->db->from('dbpre_product');
        $this->db->set_dec('sales', $num);
        $this->db->set_add('stock', $num);
        $this->db->where('pid', $pid);
        $this->db->update();
    }

    //虚拟产品直接完成订单
    function order_update($gid,$status) {
        if(!$gid) return;
        if(!$detail = $this->read($gid)) redirect('订单产品不存在！');
        $this->db->from('dbpre_product_order');
        $this->db->set('status',$status);
        $this->db->where('orderid',$detail['orderid']);
        $this->db->update();
    }
}