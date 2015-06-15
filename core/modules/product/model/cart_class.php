<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_cart extends ms_model {

    var $table = 'dbpre_product_cart';
    var $key = 'cid';
    var $model_flag = 'product';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

    //兼容旧模板函数
    function getcookie($key)
    {
        return mc_product_cart::instance()->cartid;
    }

	function init_field() {
		$this->add_field('cartid,sid,pid,pname,uid,p_image,quantity,price,overdue');
		$this->add_field_fun('sid,pid,uid,quantity,overdue', 'intval');
		$this->add_field_fun('price', 'floatval');
        $this->add_field_fun('cartid,pname,p_image', '_T');
	}

	function makecart() {
        $cart = time().'CN'.mt_rand(1000,9999);
        return $cart;
    }

    function makecartid() {
        $cartid = 'PC'.time().'CN'.mt_rand(1000,9999);
        return $cartid;
    }

	function read($cid,$pid=NULL) {
        $this->db->from($this->table);
        $this->db->select('*');
        $this->db->where('cid',$cid);
        if($pid) $this->db->where('pid',$pid);
        $result = $this->db->get_one();
        return $result;
    }

    //取得购物车内某个商铺内的产品是否存在免运费
    function get_no_shipping($cartid, $sid) {
        $this->db->join($this->table,'c.pid','dbpre_product','p.pid','LEFT JOIN');
        $this->db->where('c.cartid', $cartid);
        $this->db->where('c.sid', $sid);
        $this->db->where_more('freight', 1);
        return $this->db->count() == 0;
    }

    //取得购物车内某个商铺内的产品是支持货到付款
    function get_support_cod($cartid, $sid) {
        $this->db->join($this->table,'c.pid','dbpre_product','p.pid','LEFT JOIN');
        $this->db->where('c.cartid', $cartid);
        $this->db->where('c.sid', $sid);
        $this->db->where('is_cod', 0);
        return $this->db->count() == 0;
    }

    function read_by_pid($cartid, $pid) {
        return $this->db->from($this->table)->where('cartid', $cartid)->where('pid', $pid)->get_one();
    }

    function get_products($cartid) {
        return $this->db->join('dbpre_product_cart','pc.pid','dbpre_product','p.pid', 'LEFT JOIN')
            ->join_together('p.pid','dbpre_product_field','pf.pid', 'LEFT JOIN')
            ->select('p.*,pf.*,p.pid,pc.cid,pc.quantity')
            ->where('pc.cartid', $cartid)
            ->get_all();
    }

    //获取购物车内部分商品数据
    function get_products_by_cids($cartid, $cids) {
        return $this->db->join('dbpre_product_cart','pc.pid','dbpre_product','p.pid', 'LEFT JOIN')
            ->join_together('p.pid','dbpre_product_field','pf.pid', 'LEFT JOIN')
            ->select('p.*,pf.*,p.pid,pc.cid,pc.quantity,pc.buyattr')
            ->where('pc.cartid', $cartid)
            ->where('pc.cid', $cids)
            ->get_all();
    }

    //通过购物车获取商家的产品数据
    function get_products_by_sid($cartid, $sid) {
        return $this->db->join('dbpre_product_cart','pc.pid','dbpre_product','p.pid', 'LEFT JOIN')
            ->join_together('p.pid','dbpre_product_field','pf.pid', 'LEFT JOIN')
            ->select('p.*,pf.*,p.pid,pc.cid,pc.quantity')
            ->where('pc.cartid', $cartid)
            ->where('pc.sid', $sid)
            ->get_all();
    }

    //获取购物车内部分商品数据
    function get_products_by_pids($cartid, $pids) {
        return $this->db->join('dbpre_product_cart','pc.pid','dbpre_product','p.pid', 'LEFT JOIN')
            ->join_together('p.pid','dbpre_product_field','pf.pid', 'LEFT JOIN')
            ->select('p.*,pf.*,p.pid,pc.cid,pc.quantity')
            ->where('pc.cartid', $cartid)
            ->where('pc.pid', $pids)
            ->get_all();
    }

    function find($select, $where, $order_by, $start, $offset, $total = TRUE, $select_p=null, $select_subject=null) {
        if($select_p) {
            $this->db->join($this->table, 'c.pid', 'dbpre_product', 'p.pid', 'LEFT JOIN');
        } else {
            $this->db->from($this->table, 'c');
        }
        $select_subject && $this->db->join_together('p.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) return $result;
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select);
        $select_p && $this->db->select($select_p);
        $select_subject && $this->db->select($select_subject);
        $this->db->order_by($order_by);
        $select_subject && $this->db->group_by('c.sid');
        $this->db->limit($start,$offset);
        $result[1] = $this->db->get();
        return $result;
    }

	function save($post,$cid=NULL) {
        $cid = parent::save($post,$cid);
        return $cid;
    }

    function delete($cartid, $cids) {
		$cids = parent::get_keyids($cids);
		return $this->db->from($this->table)
            ->where('cartid',$cartid)
			->where('cid',$cids)
			->delete();
	}

    function delete_pid($cartid, $pid) {
        return $this->db->from($this->table)
            ->where('cartid', $cartid)
            ->where('pid',$pid)
            ->delete();
    }

    //删除某个类型的商品
     function delete_style($cartid, $p_style) {
        return $this->db->from($this->table)
            ->where('cartid', $cartid)
            ->where('p_style',$p_style)
            ->delete();
    }

    function delete_cartid($sid, $cartid) {
        return $this->db->from($this->table)
            ->where('sid', $sid)
            ->where('cartid', $cartid)
            ->delete();
    }

    function delete_sid($sid, $cartid) {
        $this->delete_cartid($sid, $cartid);
    }

	//清空购物车
	function cart_clear($cartid) {
		return $this->db->from($this->table)
			->where('cartid',$cartid)
			->delete();
	}

    function up_num($pid,$cartid,$num) {
        $this->db->from($this->table);
        $this->db->set_add('quantity',$num);
        $this->db->where('pid',$pid);
        $this->db->where('cartid',$cartid);
        $this->db->update();
    }

    //检测购物车里是否有该产品.
    function check_exists($pid,$cartid) {
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        $this->db->where('cartid', $cartid);
        return $this->db->count() > 0;
    }

    //检测购物车里是否有产品，禁止非法提交空数据.
    function check_isnull($sid,$cartid) {
        $this->db->from($this->table);
        $this->db->where('sid', $sid);
        $this->db->where('cartid', $cartid);
        return $this->db->count() > 0;
    }

    //检测是否有送货地址.
    function check_address($uid) {
        $this->db->from('dbpre_member_address');
        $this->db->where('uid', $uid);
        return $this->db->count() > 0;
    }

    //增加库存
    function add_num($pid,$num) {
        $this->db->from('dbpre_product');
        $this->db->set_add('stock',$num);
        $this->db->where('pid',$pid);
        $this->db->update();
    }

    //减少库存
    function dec_num($pid,$num) {
        $this->db->from('dbpre_product');
        $this->db->set_dec('stock',$num);
        $this->db->where('pid',$pid);
        $this->db->update();
    }

    //增加购物车产品数量
    function num_add($cid, $addnum = 1) {
        $this->db->from($this->table);
        $this->db->set_add('quantity', $addnum);
        $this->db->where('cid', $cid);
        $this->db->update();
    }

    //减少购物车产品数量
    function num_dec($cid, $decnum = 1) {
        $this->db->from($this->table);
        $this->db->set_dec('quantity', $decnum);
        $this->db->where('cid', $cid);
        $this->db->update();
    }

    //变动购物车产品数量
    function num_change($cid, $num) {
        $this->db->from($this->table);
        $this->db->set('quantity', $num);
        $this->db->where('cid', $cid);
        $this->db->update();
    }

    //统计购物车中产品数量.
    function count_products($cartid) {
        if(!$cartid) return 0;
        $total = $this->db->from($this->table)->select('quantity', 'num', 'SUM( ? )')->where('cartid', $cartid)->get_value();
        return (int) $total;
    }

    //计算购物车中产品可使用的消费积分数
    function count_integrals($sid,$cartid) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where('cartid',$cartid);
        if(!$row = $this->db->get()) return;
        $pids = array();
        while ($value = $row->fetch_array()) {
            $pids[] = $value['pid'];
        }
        $row->free_result();
        $this->db->from('dbpre_product');
        $this->db->select('integral', 'totalintegral', 'SUM( ? )');
        $this->db->where_in('pid', $pids);
        $total = $this->db->get_one();
        return $total['totalintegral'];
    }

    //计算购物车中每个商铺的产品总价
    function count_sum($sid,$cartid) {
        $this->db->from($this->table);
        $this->db->select('price*quantity', 'totalprice', 'SUM( ? )');
        $this->db->where('sid',$sid);
        $this->db->where('cartid',$cartid);
        $total = $this->db->get_one();
        return $total['totalprice'];
    }

    //处理登陆后购物车产品
    function login_change($cartid) {
        $this->db->from($this->table);
        $this->db->set('uid',$this->global['user']->uid);
        $this->db->where('cartid',$cartid);
        $this->db->update();
    }

}