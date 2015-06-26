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
		$this -> add_field('pictures,picture,thumb,brokerage,gcatid,sid,name,price,ori_price,create_time,update_time,start_time,end_time,pids,onshelf,tags,desc,city_id');
		$this -> add_field_fun('price', 'floatval');
		$this -> add_field_fun('brokerage', 'floatval');
	}
	
	/**
	 * 20150626修改
	 * @author hbd <hebiduhebi@126.com>
	 * 搜索
	 */
	function find($where, $start, $offset) {
		
		$this -> db -> from($this -> table, 'p');
		
		$this -> db -> where($where);
		
		$result = array(0, '');
		
		if (!$result[0] = $this -> db -> count())
			return $result;
		
		$this -> db -> sql_roll_back('from,where');
		
		$this -> db -> select("p.id,p.`thumb`,p.`brokerage`,p.`gcatid`,p.`sid`,p.`name`,p.`price`,p.`ori_price`,p.`start_time`,p.`end_time`,p.`pids`,p.`onshelf`,p.`city_id`");
		
		$this -> db -> order_by(array('p.`create_time`'=>'desc'));
		
		$this -> db -> limit($start, $offset);
		
		$result[1] = $this -> db -> get_all();
		
		return $result;
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
		//上传图片
		$post['pictures'] = $this -> post_image($post['pictures']);
		if (!$post['pictures'])
			$post['pictures'] = '';
//		dump($post['pictures']);
		//设置封面
		$post['thumb'] = $this -> set_thumb($post['thumb'], $post['pictures']);
		$post['picture'] = dirname($post['thumb']) . '/' . str_replace(array('s_', 'thumb_'), '', basename($post['thumb']));
		//序列化存储
		$post['pictures'] && $post['pictures'] = serialize($post['pictures']);
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
		
		//上传图片
		$post['pictures'] = $this -> post_image($post['pictures']);
		if (!$post['pictures'])
			$post['pictures'] = '';
//		dump($post['pictures']);
		//设置封面
		$post['thumb'] = $this -> set_thumb($post['thumb'], $post['pictures']);
		$post['picture'] = dirname($post['thumb']) . '/' . str_replace(array('s_', 'thumb_'), '', basename($post['thumb']));
		//序列化存储
		$post['pictures'] && $post['pictures'] = serialize($post['pictures']);
		
		$result = parent::save($post);
		
//		dump($post);
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
		$this -> db -> select('`picture`,`pictures`,`thumb`,`brokerage`,`gcatid`,`desc`,`tags`,`pageview`,finer,city_id,sid,ori_price,price,id,name,create_time,start_time,end_time,pids,onshelf');
		
		if (!$result = $this -> db -> get_one())
			return;
		if (!$read_product)
			return $result;
		$pids = $result['pids'];
		if(strpos($pids,",") !== FALSE){
			$pids = explode(",", $pids);
		}
		
		$result['_products'] = array();
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
	
	
	function set_thumb($thumb_key, $post) {
		if (!$post)
			return '';
		if ($thumb_key) {
			if ($file = $post[$thumb_key]) {
				$oldfile = dirname($file) . '/s_' . basename($file);
				$newfile = dirname($file) . '/thumb_' . basename($file);
				if (is_file(MUDDER_ROOT . $oldfile)) {
					rename($oldfile, $newfile);
				}
				return $newfile;
			}
		}
		if ($post) {
			$key = array_keys($post);
			return $this -> set_thumb($key[0], $post);
		}
		return '';
	}

	function post_image($pics, $old = null) {
		if (!$pics && !$old)
			return null;
		if ($old) {
			if (is_serialized($old))
				$old = unserialize($old);
			if (is_array($old))
				foreach ($old as $key => $value) {
					if (!isset($pics[$key]))
						$this -> delete_image($value);
				}
		}
		$result = array();
		if ($pics) {
			foreach ($pics as $key => $value) {
				if (!is_image(MUDDER_ROOT . $value))
					continue;
				if (strposex($value, '/temp/')) {
					$file = $this -> move_image($value);
					if ($file)
						$result[_T(pathinfo($file, PATHINFO_FILENAME))] = $file;
				} elseif (strposex($value, '/product/')) {
					if (is_file(MUDDER_ROOT . $value)) {
						$result[_T(pathinfo($value, PATHINFO_FILENAME))] = $value;
					}
				}
			}
		}
		return $result;
	}
	
	function delete_image($file) {
		if (is_array($file)) {
			foreach ($file as $value) {
				$this -> delete_image($value);
			}
		} else {
			if (is_file(MUDDER_ROOT . $file) && (strposex($file, '/product/') || strposex($file, '/temp/'))) {
				$thumb = dirname($file) . DS . 'thumb_' . basename($file);
				@unlink(MUDDER_ROOT . $file);
				@unlink(MUDDER_ROOT . $thumb);
			}
		}
	}
	
	function move_image($pic) {
		$sorcuefile = MUDDER_ROOT . $pic;
		if (!is_file($sorcuefile)) {
			return false;
		}
		if (function_exists('getimagesize') && !@getimagesize($sorcuefile)) {
			@unlink($sorcuefile);
			return false;
		}

		$this -> loader -> lib('image', null, false);
		$IMG = new ms_image();
		$IMG -> watermark_postion = $this -> global['cfg']['watermark_postion'];
		$IMG -> thumb_mod = $this -> global['cfg']['picture_createthumb_mod'];
		$IMG -> set_thumb_level($this -> global['cfg']['picture_createthumb_level']);
		$wtext = $this -> global['cfg']['watermark_text'] ? $this -> global['cfg']['watermark_text'] : $this -> global['cfg']['sitename'];
		if ($this -> global['user'] -> username) {
			$IMG -> set_watermark_text(lang('item_picture_wtext', array($wtext, $this -> global['user'] -> username)));
		} else {
			$IMG -> set_watermark_text($this -> global['cfg']['sitename']);
		}

		$name = basename($sorcuefile);
		$path = 'uploads';

		if ($this -> global['cfg']['picture_dir_mod'] == 'WEEK') {
			$subdir = date('Y', _G('timestamp')) . '-week-' . date('W', _G('timestamp'));
		} elseif ($this -> global['cfg']['picture_dir_mod'] == 'DAY') {
			$subdir = date('Y-m-d', _G('timestamp'));
		} else {
			$subdir = date('Y-m', _G('timestamp'));
		}

		$subdir = 'product' . DS . $subdir;
		$dirs = explode(DS, $subdir);
		foreach ($dirs as $val) {
			$path .= DS . $val;
			if (!@is_dir(MUDDER_ROOT . $path)) {
				if (!mkdir(MUDDER_ROOT . $path, 0777)) {
					show_error(lang('global_mkdir_no_access', $path));
				}
			}
		}
		$result = array();
		$filename = $path . DS . $name;
		$picture = str_replace(DS, '/', $filename);
		if (!copy($sorcuefile, MUDDER_ROOT . $filename)) {
			return false;
		}
		if ($this -> global['cfg']['watermark']) {
			$wfile = MUDDER_ROOT . 'static' . DS . 'images' . DS . 'watermark.png';
			$IMG -> watermark(MUDDER_ROOT . $filename, MUDDER_ROOT . $filename, $wfile);
		}

		$thumb_w = $this -> modcfg['thumb_width'] ? $this -> modcfg['thumb_width'] : 200;
		$thumb_h = $this -> modcfg['thumb_height'] ? $this -> modcfg['thumb_height'] : 150;
		$dest_img_file = $path . DS . 'thumb_' . $name;
		$IMG -> thumb($sorcuefile, MUDDER_ROOT . $dest_img_file, $thumb_w, $thumb_h);

		if (!DEBUG)
			@unlink($sorcuefile);
		return $picture;
	}
}