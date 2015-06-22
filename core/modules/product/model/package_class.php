<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_package extends ms_model {
	
	var $model_flag = 'product_package';
	var $table = 'dbpre_product_package';
	var $key = 'id';
	
	var $modcfg = '';

	//public $gcategory_model;
	//public $att_model;
	protected $models = array();

	function __get($key) {
		//模型类延迟加载
		if (in_array($key, array('product_model'))) {
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
		$this -> add_field('gcatid,sid,name,price,ori_price,create_time,update_time,start_time,end_time,pids,onshelf,tags,desc,city_id');
		$this -> add_field_fun('price', 'floatval');
	}
	
	/**
	 * 后台列表更新
	 */
	function update($post) {
		if (!is_array($post) || !$post)
			redirect('global_op_nothing');
		foreach ($post as $id => $val) {
			unset($val['id']);
			$val['onshelf'] = $val['onshelf'] ? 1 : 0;
			$val['finer'] = $val['finer'] ? 1 : 0;
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
		
		return $result;
	}
	
	
	//提交验证
	function check_post($post, $edit = false) {
		
		if ($post['city_id'] > 0) {
			$area = $this -> loader -> model('area') -> read($post['city_id']);
			if (!$area || !$area['enabled'] || $area['pid'] > 0)
				redirect('对不起，您选择的城市不存在或已停用。');
		} else {
			$post['city_id'] = 0;
		}
		
		if (empty($post['sid']))
			redirect('对不起，您未选择主题！');
		
//		if (!$post['price'] || !is_numeric($post['price']) || $post['price'] < 0)
//			redirect('对不起，您未填写套餐销售价格！');
		
		if ($post['promote'] > 0 && (!$post['promote_start'] || !$post['promote_end'])) {
			redirect('您开启了本套餐的促销价，请填写促销日期！');
		}
		
		

		return $post;
	}
	
	
	
	
	/**
	 * 读取套餐信息
	 * @param $pkgid 套餐ID
	 * @param $read_product 是否读取相应的产品信息，属于该套餐的产品
	 */
	function read($pkgid , $read_product = TRUE){
		if (!is_numeric($pkgid) || $pkgid < 1)
			redirect(lang('global_sql_keyid_invalid', 'id'));
		
		$this -> db -> from($this -> table);
		$this -> db -> where('id', $pkgid);
		$this -> db -> select('`gcateid`,`desc`,`tags`,`pageview`,finer,city_id,sid,ori_price,price,id,name,create_time,start_time,end_time,pids,onshelf');
		
		if (!$result = $this -> db -> get_one())
			return;
		if (!$read_product)
			return $result;
		$pids = $result['pids'];
		if(strpos($pids,",") !== FALSE){
			$pids = explode(",", $pids);
		}
//		dump($pids);
		if (!$read_product = $this -> read_product($pids))
			return $result;
		$result['_products'] = $read_product;
		
		return $result;
	}
	
	/**
	 * 读取产品信息，传入商品ID数组
	 */	
	function read_product($pids) {
		$this -> db -> from('dbpre_product');
		$this -> db -> where_in('pid', $pids);
		$r = $this -> db -> get_all();
		return $r;
	}
}