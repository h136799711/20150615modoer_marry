<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_mysubject extends ms_model {
    
    var $table = 'dbpre_mysubject';
    var $key = 'id';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'item';
    }

    // 我管理的主题
    function mysubject($uid)
    {
        if(isset($this->global['mysubjects'])) {
            return $this->global['mysubjects'];
        }
        $mindate = strtotime(date('Y-m-d',$this->global['timestamp']));
        $result = array();
        $this->db->from('dbpre_mysubject');
        $this->db->where('uid', $uid);
        if(!$query = $this->db->get()) return $result;
        $delete = array();
        $up_sid = array();
        while($val = $query->fetch_array()) {
            if($val['expirydate'] == 0 || $val['expirydate'] >= $mindate) {
                $result[] = $val['sid'];
            } else {
                $delete[] = $val['id'];
                $up_sid[] = $val['sid'];
            }
        }
        if($delete) {
            $this->db->from('dbpre_mysubject');
            $this->db->where_in('id', $delete);
            $this->db->delete();
            $this->update_owner($delete); //更新主题表
        }
        return $result;
    }

    function is_mysubject($sid, $uid)
    {
        if(isset($this->global['mysubjects']) && $uid == $this->global['user']->uid) {
            return in_array($sid, $this->global['mysubjects']);
        }
        return $this->db->from($this->table)
                    ->where(array('uid'=>$uid,'sid'=>$sid))
                    ->count() >= 1;
    }

    function get_uids($sid, $type = 'all')
    {
        return $this->db->from($this->table)
            ->where('sid', $sid)
            ->get_all('','uid');
    }
}
/** end */