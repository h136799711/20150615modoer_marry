<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_serial extends ms_model {

    var $table = 'dbpre_product_serial';
    var $key = 'pid';
    var $model_flag = 'product';
    
    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
        $this->modcfg = $this->variable('config');
    }
    
    function get_product() {
        return $this->loader->model('product:product');
    }
    
    function find($pid,$start,$offset) {
        $result = array(0,null);
        $this->db->from($this->table);
        $this->db->where('pid',$pid);
        $result[0] = $this->db->count();
        if($result[0]>0) {
            $this->db->sql_roll_back('from,where');
            $this->db->limit($start,$offset);
            $result[1] = $this->db->get();
        }
        return $result;
    }
    
    function getlist($oid,$uid) {
        return $this->db->from($this->table)
            ->where('oid',$oid)
            ->where('uid',$uid)
            ->get();
    }
    
    function save($pid, $serial, $show_error=true) {
        $product = $this->get_product()->read($pid);
        if(empty($product)) redirect('product_empty');
        if($product['p_style'] != '2') return;
        $list = $this->_parser($serial);
        if(empty($list) && $show_error) redirect('productcp_serial_add_empty');
        if($list) foreach ($list as $value) {
            $set = array();
            $set['pid'] = $pid;
            $set['serial'] = $value;
            $set['status'] = 1;
            $set['dateline'] = $this->global['timestamp'];
            $this->db->from($this->table)->set($set)->insert();
        }
        $num = $this->get_num($pid);
        return $num;
    }

    function get_serial($pid, $num) {
        $r = $this->db->from($this->table)
            ->where('pid',$pid)
            ->where('status',1)
            ->limit(0,$num)
            ->order_by('id')
            ->get();
        if(!$r) return false;
        $ids = array();
        while ($val = $r->fetch_array()) {
            $ids[] = $val['id'];
        }
        $r->fetch_array();
        return $ids;
    }

    function update_serial($ids, $oid, $uid) {
        $this->db->from($this->table)
            ->where('id', $ids)
            ->set('status', 0)
            ->set('oid', $oid)
            ->set('uid', $uid)
            ->set('sendtime', $this->timestamp)
            ->update();
    }

    function delete($pid, $ids) {
        $ids = parent::get_keyids($ids);
        $this->db->from($this->table)
            ->where('id', $ids)
            ->where('pid', $pid)
            ->delete();
        $num = $this->get_num($pid);
        $this->get_product()->update_num($pid, $num);
    }

    function delete_serial($pid) {
        $ids = parent::get_keyids($pid);
        $this->db->from($this->table)->where('pid', $ids)->delete();
    }
    
    function get_num($pid) {
        return $this->db->from($this->table)
            ->where('pid', $pid)
            ->where('status', 1)
            ->count();
    }
    
    function _parser($serial) {
        $serial = preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/","\n",$serial);
        $list = explode("\n", $serial);
        foreach ($list as $key => $value) {
            if(trim($value)=='') $list[$key];
        }
        return $list;
    }
}