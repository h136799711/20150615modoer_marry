<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_mylist_category extends ms_model {

    var $table = 'dbpre_mylist_category';
	var $key = 'catid';
    var $model_flag = 'mylist';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'mylist';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function init_field() {
        $this->add_field('name,listorder');
        $this->add_field_fun('listorder', 'intval');
        $this->add_field_fun('name', '_T');
	}

    function save($post) {
        $catid = parent::save($post,null);
        return $catid;
    }

    function delete($catids,$delete_mylist = TRUE) {
        $ids = parent::get_keyids($catids);
        parent::delete($ids);
        if(!$delete_mylist) return;
        $M = $this->loader->model(':mylist');
        $M->delete_catids($ids);
    }

    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $catid => $val) {
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('catid',$catid);
            $this->db->update();
        }
        $this->write_cache();
    }

    function check_post(& $post, $edit = false) {
        if(!$post['name']) redirect('对不起，您未填写分类名称');
    }

    function write_cache($return = false) {
        $this->db->from($this->table);
        $this->db->order_by('listorder');
        $result = array();
        if($r = $this->db->get()) {
            while($v=$r->fetch_array()) {
                $result[$v['catid']] = $v;
            }
            $r->free_result();
        }
        ms_cache::factory()->write('mylist_category', $result);
        if($return) return $result;
        //write_cache('category', arrayeval($result), $this->model_flag);
    }

}
?>