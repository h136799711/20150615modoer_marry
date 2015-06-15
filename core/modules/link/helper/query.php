<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_link {

    function links($params) {
        extract($params);
        $select = 'title,link,logo,des';
        $where=array();
        $where['ischeck'] = 1;
        $cfg = _G('loader')->variable('config','link');
        if($type == 'char') {
            $where['logo'] = '';
            $rows = (int)$cfg['num_char'];
        } else {
            $where['logo'] = array('where_not_equal',array(''));
            $rows = (int)$cfg['num_logo'];
        }
        if($city_id) $where['city_id'] = explode(',', $city_id);
        !$rows && $rows = 10;
        list(,$r) = _G('loader')->model('link:mylink')->find($select,$where,array('displayorder'=>$ordersc), 0, $rows, false);

        if(!$r) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

}
?>