<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_group {

    function category($params=null) {
        $loader =& _G('loader');
        $category = $loader->variable('category','group');
        $result = '';
        foreach($category as $key => $val) {
            $result[$key] = $val;
        }
        return $result;
    }

    function category_tag($params=null) {
        $loader =& _G('loader');
        $catid = (int) $params['catid'];
        $category = $loader->variable('category','group');
        $result = '';
        foreach($category as $key => $val) {
            if($key == $catid) {
                $tags = $loader->model('group:tag')->field_to_array($val['tags']);
                unset($tags[0]);
                return $tags;
            }
        }
    }

    //唯一参数gid
    function topic_type($params=null) {
        $loader =& _G('loader');
        $gid = (int) $params['gid'];
        $C =& $loader->model('group:type');
        $content = '';
        if(!$r = $C->get_list($gid)) return '';
        while($v = $r->fetch_array()) {
            $result[]=$v;
        }
        $r->free_result();
        return $result;
    }

    //获取小组数据
    function get_group($params=null) {
        extract($params);
        $db =& _G('db');
        $db->from('dbpre_group', 'g');
        if($auth) $db->where('g.auth', 1);
        if($finer) $db->where('g.finer', 1);
        if($city_id) {
            $city_id = _int_keyid(explode(',', trim($city_id)));
            if($city_id) $db->where('g.city_id', $city_id);
        }
        $db->where('g.status', 1);
        $db->select($select ? $select : 'g.*');
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

    //获取话题数据
    function get_topic($params=null) {
        extract($params);
        $gid = (int) $params['gid'];
        $city_id = _T($params['city_id']);
        $db =& _G('db');
        $db->join('dbpre_group', 'g.gid' ,'dbpre_group_topic','gt.gid');
        $db->where('gt.status', 1);
        if($gid > 0) {
            $db->from('gt.gid',$gid);
        }elseif($city_id) {
            $db->where('g.city_id', _int_keyid(explode(',', $city_id)));
        }
        if($uid > 0) {
            $db->where('gt.uid',$uid);
        }
        $db->select($select ? $select : 'gt.tpid,gt.gid,gt.subject,gt.replies,gt.uid,gt.username,g.groupname,g.topics,g.icon,g.auth');
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