<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_att extends ms_model {
    
    var $table = 'dbpre_productatt';
    var $key = 'id';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
        $this->modcfg = $this->variable('config');
    }

    function data($pid) {
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        $r = $this->db->get();
        if(!$r) return null;
        while ($v = $r->fetch_array()) {
            $result[$v['pid'].'_'.$v['att_catid']] = $v['attid'];
        }
        $r->fetch_array();
        return $result;
    }

    function check_atts($catid, $attids) {
        $cats = $this->loader->variable('att_cat','item');
        if(!isset($cats[$catid])) return '';
        $atts = $this->loader->variable('att_list_'.$catid, 'item');
        if(!is_array($attids)) $attids = array($attids);
        $result = array();
        foreach($attids as $attid) {
            if(isset($atts[$attid])) $result[] = $attid;
        }
        return !$result ? '' : implode(',', $result);
    }

    function save($pid, $attid,$catid) {
        if(!$pid) redirect(lang('global_sql_keyid_invalid','pid'));
        if(!$attid) redirect(lang('global_sql_keyid_invalid','attid'));
        $this->db->from($this->table);
        $this->db->set('pid', $pid);
        $this->db->set('attid', (int)$attid);
        $this->db->set('att_catid', (int)$catid);
        $this->db->insert();
    }

    function delete($pid) {
        if(!$pid) return;
        $this->db->from($this->table);
        $this->db->where('pid',$pid);
        $this->db->delete();
    }

    function exists($pid, $attid) {
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        $this->db->where('attid', $attid);
        return $this->db->count() > 0;
    }

    function delete_pid_catid($pid,$catid) {
        if(!$pid) return;
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        $this->db->where('att_catid', $catid);
        $this->db->delete();
    }

    function delete_pid($pid) {
        if(!$pid) return;
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        $this->db->delete();
    }

    function delete_attid($attid) {
        if(!$attid) return;
        $this->db->from($this->table);
        $this->db->where('attid', $attid);
        $this->db->delete();
    }

    /**
     * 获取某个产品的全部属性组值
     * @param  int $id 产品ID
     * @return array     
     */
    function get_product_atts($id)
    {
        $r = $this->db->join($this->table,'at.attid', 'dbpre_att_list', 'al.attid')
            ->where('at.pid', $pid)
            ->select('at.*,al.name')
            ->get();
        if(!$r) return array(array(), array());

        while ($v = $r->fetch_array()) {
            $result[$v['att_catid']][$v['attid']] = $v['name'];
        }

        $q = $this->db->from('dbpre_att_cat')->where('catid',array_keys($result))->get();
        while ($v=$q->fetch_array()) {
            $attcats[$v['catid']] = $v['name'];
        }

        return array($attcats, $result);
    }
}
?>