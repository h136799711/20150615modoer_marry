<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_space_story extends ms_model {

    var $table = 'dbpre_space_story';
    var $key = 'id';
    var $model_flag = 'space';

    function __construct()
    {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }
    
	function init_field() {
		$this->add_field('uid,path,title,content,dateline,media_type');
		$this->add_field_fun('uid,dateline,media_type', 'intval');
		$this->add_field_fun('path,title,content', '_T');
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
	
	
	function delete_not_in($uid,$ids){
		$this->db->from($this->table);
		$this->db->where('uid',$uid);
		if(count($ids)){
		$this->db->where_not_in('id',$ids);
		}
		$this->db->delete();
	}
	
	function save($post, $id = NULL) {
        $edit = $id != null;
        if($edit) {
            if(!$detail = $this->read($id)) redirect('global_op_empty');
        }
        if(!$edit) $post['dateline'] = time();
        $id = parent::save($post,$id);
        
        return $id;
    }
}
?>