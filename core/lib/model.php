<?php
/**
* Modoer框架模型基类
* @author moufer<moufer@163.com>
* @copyright modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_model extends ms_base {

	var $db = null;
	// 当前模型操作的表
	var $table = null;
	// 表主键名称
	var $key = null;
	// 表字段列表
	var $field = array();
	// 表字段自动化过滤
	var $field_fun = array();
	// 模块标识
	var $model_flag = null;
	// 类实例化时，自动检测缓存
	var $auto_check_write = false;
	// 缓存名称
	var $cache_name = '';

	//save操作场景(add,edit)
	protected $_save_on     = '';

	//更新操作主键id的值,如果是add操作则本值为空，加入数据库后才会有值
	protected $_save_pkid   = 0;

	//准备保存的数据字段
	protected $_save_data = array();

	function __construct()
	{
		parent::__construct();
		$dsn = null;
		if(!$this->global['db']) {
			$dsn = _G('dns');
		}
		//实例化一个模型内部使用的 activerecord 类
		$this->db = new ms_activerecord($dsn);
		if(!$dsn) {
			$this->db->set_db($this->global['db']->get_db());
		}
		 // 自动检测必要的缓存，并自动生成，影响效率，废弃
		//$this->auto_check_write && $this->check_write();
	}

	// 获取全局变量，未载入全局变量时，从对应的缓存中获取
	function variable($keyname, $show_error = TRUE)
	{
		if($this->model_flag) {
			return $this->loader->variable($keyname, $this->model_flag, $show_error);
		} else {
			return $this->loader->variable($keyname, '', $show_error);
		}
	}

	// 录入当前表需要用户提交的字段数据
	function add_field($field)
	{
		if(is_array($field)) {
			foreach($field as $v) $this->add_field($v);
		} else {
			if(strpos($field, ','))
				$this->add_field(explode(",", $field));
			else
				if(!in_array($field, $this->field)) $this->field[] = $field;
		}
	}

	// 录入提交的字段数据转换所需函数
	function add_field_fun($field, $fun)
	{
		if(is_array($field)) {
			foreach($field as $v) $this->add_field_fun($v, $fun);
		} else {
			if(strpos($field, ','))
				$this->add_field_fun(explode(",", $field), $fun);
			else
				$this->field_fun[$field] = $fun;
		}
	}

	// 通过录入的表字段，来返回一个用户提交的数据数组
	function get_post(& $post)
	{
		if(!$this->field) return FALSE;
		$result = array();
		foreach($this->field as $key) {
			if(isset($post[$key])) $result[$key] = $post[$key];
		}
		return $result;
	}

	// 对获得的数据数组进行对应的值进行函数转换
	function convert_post(& $post)
	{
		if(!$this->field_fun) return $post;
		foreach($this->field_fun as $key => $fun) {
			if(!isset($post[$key])) continue;
			$post[$key] = $fun($post[$key]);
		}
		return $post;
	}

	// 多选数组形式的主键处理
	function get_keyids($ids)
	{
		if(is_numeric($ids)&&$ids>0) $ids = array($ids);
		if(!$ids || !is_array($ids)) redirect('global_op_unselect');
		foreach ($ids as $id) {
			 if(!is_numeric($id)||$id<1) redirect(lang('global_sql_invalid_field','IDS'));
		}
		return $ids;
	}

	// 获取当前表的数据
	function read($value, $select="*")
	{
		if(!$value) redirect(lang('global_sql_keyid_invalid', $this->key));
		$where = array();
		$where[$this->key] = $value;
		$row = $this->db->get_easy($select, $this->table, $where);
		$result = $row ? $row->fetch_array() : FALSE;
		return $result;
	}

	// 获取当前表的所有数据
	function read_all($select="*", $orderby=null, $total=FALSE)
	{
		$result = array();
		$this->db->clear();
		$result = array(0, '');
		$this->db->from($this->table);
		if($total) {
			if(!$result[0] = $this->db->count()) return $result;
			$this->db->sql_roll_back('from');
		}
		$this->db->select($select);
		if($orderby) $this->db->order_by($orderby);
		$result[1] = $this->db->get();
		return $result;
	}

	// 查询当前数据表数量
	function count($where=null)
	{
		$this->db->from($this->table);
		$where && $this->db->where($where);
		return $this->db->count();
	}

	// 查询当前表的数据
	function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE)
	{
		$result = array(0,'');
		if($total) {
			if(!$result[0] = $this->count($where)) {
				return $result;
			}
		}
		$this->db->from($this->table);
		$where && $this->db->where($where);
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

	// 查询当前表的一条数据
	function find_one($select="*", $where=null, $orderby=null)
	{
		$this->db->from($this->table);
		$where && $this->db->where($where);
		$this->db->select($select?$select:'*');
		if($orderby) $this->db->order_by($orderby);
		return $this->db->get_one();
	}

	// 返回符合查询条件的全部数据
	function find_all($select = null, $where = null, $orderby=null)
	{
		$this->db->from($this->table);
		if($where) $this->db->where($where);
		if($orderby) $this->db->order_by($orderby);
		$this->db->select($select ? $select:'*');
		return $this->db->get();
	}

	// 删除当前表中的一些数据
	function delete($ids, $cache=TRUE)
	{
		$ids = $this->get_keyids($ids);

		//更新之前
		$this->_delete_befor($ids);

		//有报错，则终止后续操作
		if($this->has_error()) {
			return;
		}

		$this->db->from($this->table);
		if(is_array($ids)) {
			$this->db->where_in($this->key, $ids);
		} else {
			$this->db->where($this->key, $ids);
		}
		$this->db->delete();

		//有报错，则终止后续操作
		if($this->has_error()) {
			return;
		}

		//删除数量
		$rows = $this->db->affected_rows();

		//删除之后
		$this->_delete_after($ids);

		//是否更新缓存
		if($cache) $this->write_cache();
		return $rows;
	}

	// 保存数据到当前的表
	function save(& $post, $keyid=null, $cache=TRUE, $check=TRUE, $convert=TRUE)
	{
		$edit   = $keyid != null;
		$detail = null;
		if (is_array($keyid)) {
			//更新参数传入的是一个数组，表示这个是一条完整的旧数据数组
			$detail = $keyid;
			$keyid  = $keyid[$this->key];
		}
		// 格式化（转换）字段值
		if ($convert) {
			$post = $this->convert_post($post);
		}
		// 检查字段
		if ($check) {
			$result = $this->check_post($post, $edit ? $keyid : 0);

			//有报错，则终止后续操作
			if ($this->has_error()) {
				return;
			}

			// 如果返回的数组，则说明返回的是提交数组
			if(!empty($result) && is_array($result)) $post = $result;
		}

		$this->_save_data   = $post;  //数据
		$this->_save_on     = $edit ? 'edit' : 'add';   //场景
		$this->_save_pkid   = $keyid;   //主键ID值
		//当前数据
		if($edit) {
			$this->current_data = $detail;
		}

		//更新之前
		$this->_save_befor();
		//有报错，则终止后续操作
		if ($this->has_error()) {
			return;
		}

		//准备操作入库
		$mypost = array();
		//去除相同的字段（不更新）
		if ($detail) {
			foreach($detail as $k => $v) {
				if (isset($this->_save_data[$k]) && $this->_save_data[$k] != $v) {
					$mypost[$k] = $this->_save_data[$k];
				}
			}
			//post没有数据说明没有更新字段，结束函数
			if( ! $mypost || ! count($mypost)) return $this->_save_pkid;
		} else {
			$mypost = $this->_save_data;
		}

		//执行SQL
		$this->db->from($this->table);
		if ($mypost) foreach($mypost as $k => $v) {
			$this->db->set($k, $v);
		}

		if ($edit) {
			// 更新数据
			$this->db->where($this->key, $this->_save_pkid);
			$this->db->update();
		} else {
			// 新建数据
			$this->db->insert();
			$this->_save_pkid = $keyid = $this->db->insert_id();
		}

		//有报错，则终止后续操作
		if ($this->has_error()) {
			return;
		}

		//更新之后
		$this->_save_after();

		// 更新缓存
		if ($cache) {
			$this->write_cache();
		}
		// 返回insert_id
		return $keyid;
	}

	// 写入缓存的。抽象函数，由继承子类来完成具体函数
	function write_cache() {}

	// 验证用户提交的数据。抽象函数，由继承子类来完成具体函数
	function check_post(& $post, $keyid = FALSE) {}

	// 判断缓存是否存在。影响效率，废弃
	function check_write()
	{
		if(!$this->cache_name) return;
		$cachefile = MUDDER_CACHE.$this->model_flag.'_'.$this->cache_name.'.php'; 
		if(!check_cache($cachefile)) {
			$this->write_cache();
		}
	}

	//增加或减少某个字段的值（数字）
	function _update_number($id, $fieldname, $num)
	{
		if(!$num) return;
		$fun = $num > 0 ? 'set_add' : 'set_dec';
		$this->db->from($this->table)
			->where($this->key, $id)
			->$fun($fieldname, abs($num))
			->update();
		return $this->db->affected_rows();
	}

	//更新某个字段的值
	function _update_value($id, $fieldname, $value)
	{
		$this->db->from($this->table)
			->where($this->key, $id)
			->set($fieldname, $value)
			->update();
		return $this->db->affected_rows();
	}

	//save操作之前
	protected function _save_befor() {}

	//save操作之后
	protected function _save_after() {}

	//delete操作之前
	protected function _delete_befor($ids) {}

	//delete操作之后
	protected function _delete_after($ids) {}

}

