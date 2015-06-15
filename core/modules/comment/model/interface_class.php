<?php
/**
* 社会化评论插件积累
*/
abstract class msm_comment_interface extends ms_base {

	//配置信息
	protected $cfg = array();
	//评论对象唯一ID
	private $id = '';

	//插件信息(名称，地址，简介)
	//'name','url','intro'
	protected $interface_info = array();
	//需要挂载的函数钩子名称
	protected $hooks = array();

	//显示评论信息
	abstract public function display($common_cfg);
	//获取评论对象评论统计信息
	abstract public function get_info();
	
	public function __construct() {
		parent::__construct();
		$this->interface_info['flag'] = str_replace('msm_comment_','',get_class($this));
		$this->cfg = $this->loader->variable('config','comment');
		$this->id = $id;
	}

	//获取配置表单项目
	public function form_elements() {}

	//检测提交的配置信息
	public function check_setting($post) {}

	//函数钩子挂载
	public function hook($hook_name, $params) {}

	//获取函数钩子名称数组
	public function get_hook() {
		return $this->hooks;
	}

	//获取接口信息
	public function get_interface_info() {
		return $this->interface_info;
	}

}
/** end **/