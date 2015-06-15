<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
class msm_group_type extends ms_model {

    var $table = 'dbpre_group_topic_type';
	var $key = 'typeid';
    var $model_flag = 'group';

    var $c_tag = null;

    var $typenames = array();
    var $typeurls = array();
    var $idtypes = array();

	function __construct() {
		parent::__construct();
        $this->model_flag = 'group';
		$this->init_field();
        $this->modcfg = $this->variable('config');
        $this->c_tag = $this->loader->model('group:tag');
	}

	function init_field() {
		$this->add_field('name,listorder,gid');
		$this->add_field_fun('gid,listorder', 'intval');
        $this->add_field_fun('name', '_T');
	}

    function get_list($gid) {
        $this->db->from($this->table);
        $this->db->where('gid',$gid);
        $this->db->order_by('listorder','ASC');
        return $this->db->get();
    }

    function get_count($gid) {
        $this->db->from($this->table);
        $this->db->where('gid',$gid);
        return $this->db->count();
    }

    function save($post,$typeid=null) {
        $typeid = parent::save($post,$typeid);
        return $typeid;
    }

    function delete($gid,$typeid) {
        $this->db->from($this->table);
        $this->db->where(array('gid'=>$gid,'typeid'=>$typeid));
        $this->db->delete();
        //清楚话题里的typeid
        $this->loader->model('group:topic')->clear_typeid($gid,$typeid);
    }

    function listorder($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $typeid => $val) {
            $this->db->from($this->table);
            $this->db->set('listorder',(int)$val['listorder']);
            $this->db->where('typeid',$typeid);
            $this->db->update();
        }
        $this->write_cache();
    }

    function check_post($post, $typeid = 0) {
        if(!$post['name']) redirect('话题分类名称不能为空。');
        if($typeid>0){
            if($this->check_exists($post['name'], $typeid)) {
                redirect('话题分类名称"'.$post['name'].'"已存在。');
            }
        } else {
            $post['dateline'] = $this->global['timestamp'];
        }
        return $post;
    }

    function check_exists($name,$out_typeid=0) {
        $this->db->from($this->table);
        if($out_typeid) $this->db->where_not_equal('typeid', $out_typeid);
        return $this->db->where('name',$name)->get_one();
    }

    function is_exists($gid,$typeid) {
        return $this->db->from($this->table)->where(array('gid'=>$gid,'typeid'=>$typeid))->count() > 0;
    }

}
?>