<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_mylist {

    function category($params=null) {
        $loader =& _G('loader');
        if(!$params['nocache']) return $loader->variable('category','mylist');
        $db =& _G('db');
        $db->from('dbpre_mylist_category');
        $db->order_by('listorder');
        $result = array();
        if($r = $db->get()) {
            while($v=$r->fetch_array()) {
                $result[$v['catid']] = $v;
            }
            $r->free_result();
        }
        return $result;
    }

    //获取小组数据
    function get_mylist($params=null) {
        extract($params);
        $db =& _G('db');
        $db->from('dbpre_mylist', 'm');
        if($city_id) {
            $city_id = _int_keyid(explode(',', trim($city_id)));
            if($city_id) $db->where('m.city_id', $city_id);
        }
        if($catid>0) $db->where('m.catid', $catid);
        if($uid>0) $db->where('m.uid', $uid);
        $db->where('m.status', 1);
        $db->select($select ? $select : 'm.*');
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r = $db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

}
?>