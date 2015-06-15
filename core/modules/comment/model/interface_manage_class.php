<?php
/**
* 评论接口管理类
*/
class msm_comment_interface_manage extends ms_myiterator {

	private $itf_path = '';

	function __construct() {
		//接口文件目录
		$this->itf_path = MUDDER_MODULE . 'comment' . DS . 'interface' . DS;
		parent::__construct();
		//加载评论接口基类
		$this->loader->model('comment:interface', false);
		//获取评论模块配置
		$this->cfg = $this->loader->variable('config','comment');
	}

	//获取所有评论接口类信息列表
	function interface_list() {
		if(!$this->_list) return;
		$result = array();
		foreach ($this->_list as $cname=>$obj) {
			$result[$cname] = $obj->get_interface_info();
		}
		reset($this->_list);
		return $result;
	}

	//工厂模式，返回一个实例化评论接口类
	function factory($interface_name = null) {
		if(!$interface_name) $interface_name = $this->cfg['comment_interface'];
        if(!$interface_name) $interface_name = 'local';
        if(!preg_match("/^[a-z0-9_\-]+$/i", $interface_name)) return;
        $filename = $this->itf_path . $interface_name . '.php';
        if(!file_exists($filename)) return;
        $classname = 'msm_comment_' . $interface_name;
        if(!class_exists($classname)) include_once $filename;
        if(get_parent_class($classname) == 'msm_comment_interface') {
            return new $classname();
        }
        return false;
	}

	//系统钩子函数（挂载点），通知当前给评论接口
	function hook($hook_name, $params) {
		$this->factory()->hook($hook_name, $params);
	}

	//生成配置表单
	function create_form() {
	}

	//检测提交的配置信息
	function check_setting() {
	}

	//扫描接口文件
	function scan_interfaces() {
		$this->_list = array();
		try {
			$iterator = new DirectoryIterator($this->itf_path);
		} catch (Exception $e) {
			redirect($e);
		}
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() && pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION) == 'php') {
                $apiname = $fileinfo->getBasename('.php');
                $classname = 'msm_comment_' . $apiname;
                if(!class_exists($classname)) include_once $fileinfo->getRealPath();
                if(get_parent_class($classname)=='msm_comment_interface') {
                    $this->_list[$apiname] = new $classname();
                }
            }
        }
	}

	protected function load_list() {}

}
/** end **/