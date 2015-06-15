<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_space_attend extends ms_model {

    var $table = 'dbpre_space_attend';
    var $key = 'id';
    var $model_flag = 'space';

    function __construct()
    {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }
    
	function init_field() {
		$this->add_field('uid,is_part,name,phone,part_num,content,dateline');
		$this->add_field_fun('uid,is_part,part_num,dateline', 'intval');
		$this->add_field_fun('name,phone,content', '_T');
	}

  	function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE) {
	    $this->db->from($this->table);
		$where && $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
		$this->db->select($select?$select:'*');
        if($orderby) $this->db->order_by($orderby);
        if($start < 0) {
            if(!$result[0]) {
                $start = 0;
            } else {
                $start = (ceil($result[0]/$offset) - abs($start)) * $offset; //按 负数页数 换算读取位置
            }
        }
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();

        return $result;
    }
	function save($post, $id = NULL) {
        $edit = $id != null;
        if($edit) {
            if(!$detail = $this->read($id)) redirect(lang('global_sql_invalid_field','id'));
        }
        if(!$edit) $post['dateline'] = time();
        $id = parent::save($post,$id);
        
        return $id;
    }
    
    function attend_count($uid){
		$this->db->from($this->table);
		$this->db->where('uid',$uid);
		return $this->db->count();
	}
    
    function check_post(&$post) {
        if(!$post['name']) redirect(lang('global_sql_invalid_field','name'));
    }
}
?>