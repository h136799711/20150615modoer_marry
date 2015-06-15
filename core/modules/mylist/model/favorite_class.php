<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_mylist_favorite extends ms_model {

    var $table = 'dbpre_favorites';
    var $key = 'fid';
    var $model_flag = 'mylist';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'mylist';
        $this->modcfg = $this->variable('config');
    }

    function find($select, $where, $orderby, $start=0, $offset=10, $total=true) {
        $result = array(0,'');
        $where['f.idtype'] = 'mylist';
        $result = array(0,'');
        if($total) {
             $this->db->join($this->table,'f.id','dbpre_mylist','m.id');
            if(!$result[0] = $this->db->where($where)->count()) {
                return $result;
            }
        }
        $this->db->join($this->table,'f.id','dbpre_mylist','m.id');//from($this->table)
        $this->db->where($where)->select($select?$select:'*')->order_by($orderby);
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

    function add($mylist_id) {
    	if($this->exists($mylist_id)) return -1;
    	$mylist = $this->loader->model(':mylist')->read($mylist_id);
    	if(!$mylist||!$mylist['status']) redirect('mylist_empty');
    	$insert = array(
    		'idtype' => 'mylist',
    		'id' => $mylist_id,
            'uid' => $this->global['user']->uid,
    		'username' => $this->global['user']->username,
    	);
    	$this->db->from($this->table)->set($insert)->insert();
    	$fid = $this->db->insert_id();
    	if($fid > 0) {
    		$this->loader->model(':mylist')->update_favorites($mylist_id, 1);
    	}
    	return $fid;
    }

    function delete($mylist_id, $uid=null) {
        if(!$uid) $uid = $this->global['user']->uid;
    	$this->db->from($this->table)
    		->where(array('idtype'=>'mylist','id'=>$mylist_id,'uid'=>$uid))
    		->delete();
    	$arows = $this->db->affected_rows();
    	if($arows > 0) {
    		$this->loader->model(':mylist')->update_favorites($mylist_id, -1);
    	}
    }

    function delete_mylist($mylist_id) {
    	if(!$this->in_admin) {
    		$mylist = $this->loader->model(':mylist')->read($mylist_id);
    		if(!$mylist) redirect('mylist_empty');
    		if($mylist['uid'] != $this->global['user']->uid) redirect('mylist_manage_access_denied');
    	}
		$this->db->from($this->table)
    		->where(array('idtype'=>'mylist','id'=>$mylist_id))
    		->delete();
    }

    function exists($mylist_id) {
    	return $this->db->from($this->table)
    		->where(array('idtype'=>'mylist','id'=>$mylist_id,'uid'=>$this->global['user']->uid))
    		->count() > 0;
    }

    function _delete($where, $update_total=TRUE) {
        $this->db->from($this->table);
        $this->db->where('idtype','member');
        $this->db->where($where);
        if(!$q = $this->db->get()) return ;
        $delids = $ids = array();
        while($v=$q->fetch_array()) {
            $delids[] = $v['fid'];
            if(!$update_total) continue;
            $this->member_total($v['uid'], $v['id'], -1);
        }
        $q->free_result();
        $this->db->from($this->table);
        $this->db->where('fid', $delids);
        $this->db->delete();
    }

    //提醒
    // xxx 收藏了 user 的榜单 xxx
    function _notice($mylist) {

    }

}
?>