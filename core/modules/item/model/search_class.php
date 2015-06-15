<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_search extends msm_item_itembase {

    var $table = 'dbpre_search_cache';
	var $key = 'ssid';

	function __construct() {
		parent::__construct();
		$this->init_field();
	}

	function init_field() {
		parent::add_field('keyword,city_id,catid,ordersort,ordertype');
        parent::add_field_fun('keyword,ordersort,ordertype,catid', MF_TEXT);
	}

    function read_k($keyword, $catid=0, $city_id=null) {
        $this->db->from($this->table);
        is_numeric($catid) && $this->db->where('catid', $catid);
        if($city_id) {
            $city_id = is_array($city_id) ? implode(',', $city_id) : (int) $city_id;
            $this->db->where('city_id', $city_id);
        }
        $this->db->where('keyword', $keyword);
        return $this->db->get_one();
    }

    function search($start=0, $offset=0, $life=TRUE) {
        $post = $this->get_post($_GET); //获取
        $this->check_post($post); //验证
        $post = $this->convert_post($post); //转换
        $result = array(0, ''); //结果
        if($life) {
            $config = $this->variable('config');
            $search_life = $config['search_life'] > 0 ? ($config['search_life'] * 60) : 3600; //秒
        }

        if($post['catid']) {
            $pids = explode(',', $post['catid']);
            $pids = _int_keyid($pids);
        }
        if($post['city_id']) {
            if(is_array($post['city_id'])) {
                $city_id = $post['city_id'];
            } else {
                $city_id = explode(',', $post['city_id']);
            }
            $city_ids = _int_keyid($city_id);
        }

        // if($detail = $this->read_k($post['keyword'], $pids, $city_ids)) {
        //     $ssid = $detail['ssid'];
        //     if($life && $detail['dateline'] >= $this->global['timestamp'] - $search_life) {
        //         if($detail['total']) {
        //             $result[0] = $detail['total'];
        //         } else {
        //             return $result;
        //         }
        //     }
        // }

        //在没有搜索缓存或者缓存中有结果时，对结果进行搜索
        $this->db->from('dbpre_subject');
        $this->db->select('*');
        if($city_ids) {
            $this->db->where('city_id', $city_ids);
        }
        if($pids) {
            $this->db->where('pid', $pids);
        }
        $post['keyword'] = trim(preg_replace("/\s+/", ' ', $post['keyword']));
        $keywords = explode(' ', $post['keyword']);
        $split = "AND";
        $kh = "";
        $count = count($keywords);
        foreach ($keywords as $i => $kw) {
            if(!$i && $count > 1) {
                $kh="(";
            } elseif($count > 1 && $count-1 == $i) {
                $kh=")";
            } else {
                $kh="";
            }
            $this->db->where_concat_like('name,subname', "%{$kw}%", $split, $kh);
            $split = " OR ";
        }
        $this->db->where('status', 1);
        
        //echo $this->db->get_sql();
        if(!$result[0]) {
            $result[0] = $this->db->count();
        }
        if($result[0]) {
            $this->db->sql_roll_back('from,select,where');
            $orderby = array($post['ordersort']=>$post['ordertype']);
            $this->db->order_by($orderby);
            $this->db->limit($start, $offset);
            $result[1] = $this->db->get();
        }
        $this->db->clear();

        // if($ssid) {
        //     $this->update_result($detail['ssid'], $result[0]);
        // } else {
        //     $this->save_result($post, $result[0]);
        // }
        return $result;
    }

    function update_result($ssid, $total=-1) {
        $this->db->from($this->table);
        $this->db->set_add('count');
        $total>-1 && $this->db->set('total', $total);
        $this->db->where('ssid', $ssid);
        $this->db->update();
    }

    function save_result($post, $total) {
        $this->db->from($this->table);
        $this->db->set('keyword', $post['keyword']);
        $this->db->set('catid', (int) $post['catid']);
        if($post['city_id']) $this->db->set('city_id', implode(',',$post['city_id']));
        $this->db->set('count', 1);
        $this->db->set('dateline', $this->global['timestamp']);
        $this->db->set('total', $total);
        $this->db->insert();
        return $this->db->insert_id();
    }

    function check_post($post) {

        $order_arr = array('addtime','avgsort','reviews','pageviews');
        $ordertype_arr = array('desc','asc');

        if(trim($post['keyword'])=='') redirect(lang('item_search_keyword_empty'));
        if(strlen($post['keyword']) < 2) redirect(lang('item_search_keyword_len'));
        if(!in_array($post['ordersort'], $order_arr)) redirect(lang('global_sql_where_invalid', 'ordersort'));
        if(!in_array($post['ordertype'], $ordertype_arr)) redirect(lang('global_sql_where_invalid', 'ordertype'));
        if($post['catid']=='') redirect(lang('global_sql_keyid_invalid', 'catid'));
        if(!is_numeric($post['catid'])) {
            $catids = explode(',', $post['catid']);
            if(!$catids) redirect(lang('global_sql_keyid_invalid', 'catid'));
            foreach ($catids as $catid) {
                if(!is_numeric($catid)||!$catid||$catid<1) redirect(lang('global_sql_keyid_invalid', 'catid'));
            }
        }
    }

}
?>