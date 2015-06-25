<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_pkg_bespeak extends ms_model {
	
	var $model_flag = 'pkg_bespeak';
	var $table = 'dbpre_product_pkg_bespeak';
	var $key = 'id';
	
	var $modcfg = '';

	protected $models = array();
	
	function __get($key) {
		//模型类延迟加载
		if (in_array($key, array('pkg_bespeak_model'))) {
			$this -> models[$key] = $this -> loader -> model('product:' . str_replace('_model', '', $key));
			return $this -> models[$key];
		}
	}
	
	function __construct() {
		parent::__construct();
		$this -> init_field();
		$this -> modcfg = $this -> variable('config');
	}
	
	function init_field() {
		$this -> add_field('sid,pkgid,pkgname,create_time,status,notes,name,phone');
	}
	
	/**
	 * 后台列表更新
	 */
	function update($post) {
		if (!is_array($post) || !$post)
			redirect('global_op_nothing');
		foreach ($post as $id => $val) {
			unset($val['id']);
			$this -> db -> from($this -> table);
			$this -> db -> set($val);
			$this -> db -> where('id', $id);
			$this -> db -> update();
		}
	}
	
	
	/**
	 * 编辑套餐信息
	 */
	function edit_save($keyid,$post) {
				
//		$S = &$this -> loader -> model('item:subject');
		
		//关键字标签
//		$post['tag_keyword'] = trim($post['tag_keyword']);

		$post = $this -> check_post($post, false);
		
		$result = parent::save($post,$keyid);
		
		return $result;
	}
	
	
	
	/**
	 * 添加套餐信息
	 */
	function add_save($post) {
				
//		$S = &$this -> loader -> model('item:subject');
		
		//关键字标签
//		$post['tag_keyword'] = trim($post['tag_keyword']);

		$post = $this -> check_post($post, false);
		$result = parent::save($post);
		
//		dump($post);
		return $result;
	}
	
	
	//提交验证
	function check_post($post, $edit = false) {
		
		if (empty($post['name']))
			redirect('对不起，您未填写姓名！');
		
		
		if (empty($post['phone'])) {
			redirect('您未填写联系方式！');
		}
		
		
		return $post;
	}
	
	
	
	
	/**
	 * 读取套餐信息
	 * @param $id 预约ID
	 * @param $read_product 是否读取相应的套餐信息，属于该预约
	 */
	function read($id , $read_pkg = TRUE){
		if (!is_numeric($id) || $id < 1)
			redirect(lang('global_sql_keyid_invalid', 'id'));
		
		$this -> db -> from($this -> table);
		$this -> db -> where('`id`', $id);
		$this -> db -> select('`id`,`pkgname`,`pkgid`,`name`,`create_time`,`notes`,`phone`,`status`');
		
		if (!$result = $this -> db -> get_one())
			return;
		
		if (!$read_pkg)
			return $result;
		
		$pkgid = $result['pkgid'];
		
		$result['_pkginfo'] = array();
		
		if (!$read_pkg = $this -> read_pkginfo($pkgid))
			return $result;
		
		$result['_pkginfo'] = $read_pkg;
		
		return $result;
	}
	
	function read_pkginfo($pkgid){
		$this -> db -> from("dbpre_product_package");
		$this -> db -> where('id', $pkgid);
		if (!$result = $this -> db -> get_one())
			return;
		
		return $result;
	}
	
	
	function find($sid,$p=1,$pname='') {
		
		$offset = 5;
		
		if(!empty($pname)){
			$this -> db -> where_like("p.name", '%'.$pname.'%');
		}
		$this -> db -> where('p.status', 1);
		$this -> db -> where('p.sid',$sid);
		
		$this -> db -> join($this -> table, 'p.pkgid', 'dbpre_product_package', 's.id');
		
		if ($total = $this -> db -> count()) {
			$this -> db -> select('p.*,s.name as pkgname');
			$this -> db -> sql_roll_back('from,where');
			
			$this -> db -> order_by('p.id' ,'DESC');
			$this -> db -> limit(get_start($p, $offset), $offset);
			$list = $this -> db -> get_all();
			
			$multipage = array($total, $offset, $p);
		}
		
		return  array('list'=>$list,'page'=>$multipage);
	}
	
	
	function delete($id){
		
		$this -> db -> from($this->table);
		$this -> db -> where('id', $id);
		if (!$result = $this -> db -> delete())
			return;
		
		return $result;
		
	}
	
	
}