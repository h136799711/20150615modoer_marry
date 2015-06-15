<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
class msm_group_category extends ms_model {

    var $table = 'dbpre_group_category';
	var $key = 'catid';
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
		$this->add_field('name,listorder,title,tags,keywords,description');
		$this->add_field_fun('listorder', 'intval');
        $this->add_field_fun('name,title,keywords,description,tags', '_T');
	}

    function save($post,$catid=null) {
        $catid = parent::save($post,$catid);
        return $catid;
    }

    function delete($catids,$del_group = TRUE) {
        $ids = parent::get_keyids($catids);
        if(!$del_group) return;
        //$EX =& $this->loader->model('exchange:gift');
        //$EX->delete_catids($ids);
        //unset($EX);
        parent::delete($ids);
    }

    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $catid => $val) {
            $val = $this->check_post($val, $catid);
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('catid',$catid);
            $this->db->update();
        }
        $this->write_cache();
    }

    function check_post(& $post, $catid = 0) {
        if(!$post['name']) redirect('分类名称不能为空。');
        if($this->check_exists($post['name'], $catid)) {
            redirect('分类名称已存在。');
        }
        $tags = $post['name'] . $this->c_tag->split . $post['tags'];
        $post['tags'] = $this->c_tag->check_filter($tags);
        if($post['tags'] && is_array($post['tags'])) {
            $post['tags'] = $this->c_tag->array_to_field($post['tags']);
        }
        return $post;
    }

    function check_exists($name,$out_catid=0) {
        $this->db->from($this->table);
        if($out_catid) $this->db->where_not_equal('catid', $out_catid);
        return $this->db->where('name',$name)->get_one();
    }

    function write_cache($return = false) {
        $this->db->from($this->table);
        $this->db->order_by('listorder','ASC');
        $result = array();
        if($r = $this->db->get()) {
            while($v=$r->fetch_array()) {
                $result[$v['catid']] = $v;
            }
            $r->free_result();
        }
        ms_cache::factory()->write('group_category', $result);
        if($return) return $result;
        //write_cache('category', arrayeval($result), $this->model_flag);
    }

}
?>