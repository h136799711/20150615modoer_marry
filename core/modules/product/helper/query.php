<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_product {
    //获取产品的店内分类列表
    function category($params) {
		extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

		$db->from('dbpre_product_category');
        $db->select($select?$select:'*');
        $db->where('sid', $sid);
        $orderby && $db->order_by($orderby);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
	}

    //获取产品的分类列表
    function gcategory($params) {
        extract($params);
        $loader =& _G('loader');
        $pid = (int) $pid;
        if($pid > 0) {
            $C =& $loader->model('product:gcategory');
            $root_id = $C->get_parent_id($pid);
            if(!$category = $loader->variable('gcategory_' . $root_id, 'product')) return '';
        } else {
            $category = $loader->variable('gcategory','product');
        }
        $result = ''; $index = 0;
        foreach($category as $key => $val) {
            if($val['pid'] == $pid && $val['enabled']) {
                if($num>0 && ++$index > $num) break;
                $result[] = $val;
            }
        }
        return $result;
    }

	//获取产品列表
	//select,cachetime,rows,orderby,sid
	function getlist($params) {
		extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

        //$db->from('dbpre_product','pt');
		$db->join('dbpre_product','pt.sid','dbpre_subject','s.sid','LEFT JOIN');
        $db->select($select?$select:'pt.pid,pt.modelid,pt.sid,pt.catid,pt.subject,pt.dateline,pt.grade,pt.pageview,pt.comments,pt.thumb,pt.price,pt.description,s.name,s.subname');
        if($city_id) $db->where('s.city_id',explode(',',trim($city_id)));
		if($catid > 0) $db->where('pt.uid',$uid);
		if($sid > 0) $db->where('pt.sid',$sid);
        if($uid > 0) $db->where('pt.uid',$uid);
        if($finer > 0) $db->where('pt.finer',$finer);
        if($comments > 0) $db->where_more('pt.comments',$comments);
        $db->where('pt.status', 1);
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
	}

	function getorder($params) {
		extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

		$db->join('dbpre_product_ordergoods','g.orderid','dbpre_product_order','o.orderid','LEFT JOIN');
        $db->select($select?$select:'g.*,o.buyername,o.addtime');
        if($pid > 0) $db->where('g.pid',$pid);
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
	}

	//获取购物车产品列表
	//select,cachetime,rows,orderby,sid
	function getcartlist($params) {
		extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

		$db->join('dbpre_product_cart','c.pid','dbpre_product','p.pid','LEFT JOIN');
		$db->join_together('p.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        $db->select($select?$select:'c.*,p.*,s.name,s.subname');
        if($sid) $db->where('c.sid', $sid);
        $db->where('c.cartid', $cartid);
        $orderby && $db->order_by($orderby);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $v['buyattr'] = msm_product_buyattr::buyattr_strtoarray($v['buyattr']);
            $result[] = $v;
        }
        $r->free_result();
        return $result;
	}

	//获取物流方式列表
	function getshippinglist($params) {
		extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

		$db->from('dbpre_product_shipping');
        $db->select($select?$select:'*');
        $db->where('sid', $sid);
        $db->where('enabled', 1);
        $orderby && $db->order_by($orderby);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
	}

	function getaddress($params) {
		extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

		$db->from('dbpre_member_address');
        $db->select($select?$select:'*');
        $db->where('uid', $uid);
        $orderby && $db->order_by($orderby);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
	}

	function getproduct($params) {
		extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

		$db->from('dbpre_product_ordergoods');
        $db->select($select?$select:'*');
        $db->where('orderid', $orderid);
        $orderby && $db->order_by($orderby);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
	}

	//取得属性组
	function getatt($params) {
		extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

		$db->from('dbpre_att_list');
        $db->select($select?$select:'*');
        $db->where('type', 'att');
        $db->where('catid', $catid);
        $orderby && $db->order_by($orderby);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
	}

    /**
     * 通过参数pid获取下单属性组
     * @param  array $params 参数
     * @return [type]         [description]
     */
    function get_buyattr($params) {
        extract($params);
        $buyattr_obj = _G('loader')->model('product:buyattr');

        $r = $buyattr_obj->find_all('*',array('pid'=>$pid),'listorder');
        if(!$r) return null;

        $result = array();
        while($v = $r->fetch_array()) {
            $v['value'] = explode(',', $v['value']);
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }
}
?>