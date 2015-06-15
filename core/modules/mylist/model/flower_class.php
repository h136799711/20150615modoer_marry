<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_mylist_flower extends ms_model {

    var $table = 'dbpre_flowers';
    var $key = 'fid';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'mylist';
        $this->modcfg = $this->variable('config');
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
            'dateline' => $this->timestamp,
        );
        $this->db->from($this->table)->set($insert)->insert();
        $fid = $this->db->insert_id();
        if($fid > 0) {
            $this->loader->model(':mylist')->update_flowers($mylist_id, 1);
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
            $this->loader->model(':mylist')->update_flowers($mylist_id, -1);
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

    //提醒
    // xxx 把鲜花送给 user 的榜单 xxx
    function _notice($mylist) {

    }

}
?>