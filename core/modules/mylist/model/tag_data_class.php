<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_mylist_tag_data extends ms_model {

    var $table = 'dbpre_mylist_tag_data';
	var $key = 'id';
    var $model_flag = 'mylist';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'mylist';
        $this->modcfg = $this->variable('config');
	}

	// $tags 为固定格式，有mylist:tag类返回提供
	function save($tags, $mylist_id) {
		$M = $this->loader->model(':mylist');
		$mylist = $M->read($mylist_id);
		if(!$mylist) redirect('mylist_empty');
		$src_tags = $this->_get_mylist_tags($mylist_id);
		$del_data_ids = array();
		if($src_tags) foreach ($src_tags as $tag_id => $data) {
			if(isset($tags[$tag_id])) {
				unset($tags[$tag_id]);
			} else {
				$del_data_ids[] = $data['id'];
			}
		}
		$arows = 0; //添加数量
		foreach ($tags as $id => $name) {
			$post = array('tag_id' => $id, 'tag_name' => $name);
			$arows += $this->_add_tag_data($post, $mylist);
		}
		if($del_data_ids) $this->_delete_ids($del_data_ids);
		return $arows;
	}

	//根据榜单ID删除数据
	function delete_mylist($mylist_id) {
		$this->db->from($this->table)->where('mylist_id', $mylist_id)->delete();
		return $this->db->affected_rows();
	}

	//更新城市ID（榜单的city_id更新）
	function update_city_id($mylist_id, $city_id) {
		$this->db->from($this->table)->where('mylist_id', $mylist_id)->set('city_id', $city_id)->update();
		return $this->db->affected_rows();
	}

	//新增标签
	function _add_tag_data($tag, $mylist) {
		$post = $tag;
		$post['mylist_id'] = $mylist['id'];
		$post['city_id'] = $mylist['city_id'];
		$this->db->from($this->table)->set($post)->insert();
		return $this->db->affected_rows();
	}

	//删除已经不存在的标签
	function _delete_ids($ids) {
		$this->db->from($this->table)->where('id', $ids)->delete();
	}

	//已经存在的
	function _get_mylist_tags($mylist_id) {
		$q = $this->db->from($this->table)->where('mylist_id', $mylist_id)->get();
		if(!$q) return;
		$result = array();
		while ($v = $q->fetch_array()) {
			$result[$v['tag_id']] = $v;
		}
		return $result;
	}
}
?>