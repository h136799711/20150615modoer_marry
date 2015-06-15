<?php
/**
* Modoer模板引擎
*/
class ms_template extends ms_base
{
	public $tpl_root_dir = '';
	public $cache_root_dir = '';

	/**
	 * 当前模板文件
	 * @var string
	 */
	protected $_tpl_file = '';
	/**
	 * 当前模板缓存文件
	 * @var string
	 */
	protected $_cache_file = '';
	
	function __construct()
	{
		parent::__construct();
		$this->tpl_root_dir = 'templates';
		$this->cache_root_dir = 'data'.DS.'templates';
	}

	/**
	 * 获取模板缓存
	 * @param  string  $tpl_file     模板文件名（不包含模板路径）
	 * @param  boolean $force_update 是否强制更新模板缓存
	 * @return string                模板缓存完整完整路径
	 */
	function get_cache_file($tpl_file, $force_update = false)
	{
		//缓存相对路径
		$cache_file = $this->_get_cache_filename($tpl_file);
		//模板相对路径
		$tpl_file = $this->_format_filepath($this->tpl_root_dir.DS.$tpl_file);

		$ret = new stdClass;
		$ret->tpl_file		= $tpl_file;
		$ret->cache_file	= $cache_file;
		//模板URL相对路径
		$ret->tpl_url = str_replace("\\", "/", trim(dirname($ret->tpl_file), DS));

		if(!$force_update && is_file($cache_file)) {
			//缓存是否可用判断
			if(filemtime(MUDDER_ROOT.$tpl_file) < filemtime(MUDDER_ROOT.$cache_file)) {
				//缓存生成时间比模板时间晚，则表示模板可用
				return $ret;
			}
		}

		//没有缓存或需要重新更新缓存
		if(!is_file(MUDDER_ROOT.$tpl_file)) {
			return $this->add_error(lang('global_file_not_exist', $tpl_file));
		}

		$this->_tpl_file = $ret->tpl_file;
		$this->_cache_file = $ret->cache_file;

		//生成緩存
		if($this->_create_cache()) {
			return $ret;
		}
		return false;
	}

	//根据模板文件，或者对应的缓存模板文件
	private function _get_cache_filename($tpl_file) 
	{
		$filename = str_replace(array("\\","/"),'_',$tpl_file);
		return $this->_format_filepath($this->cache_root_dir.DS.$filename).'.php';
	}

	/**
	 * 生成模板缓存文件
	 */
	private function _create_cache()
	{
		if(!$this->_check_dir()) return false;
		$template = file_get_contents(MUDDER_ROOT.$this->_tpl_file);
		if(!$template) {
			return $this->add_error(lang('global_template_no_access', $this->_tpl_file));
		}
		$this->loader->helper('template');
		$cache_template = parse_template($template);
		$ret = file_put_contents(MUDDER_ROOT.$this->_cache_file, $cache_template);
		if(!$ret) {
			return $this->add_error(lang('global_template_cache_no_access', $this->_cache_file));
		}
		@chmod(MUDDER_ROOT.$this->_cache_file, 0777);
		return true;
	}

	//检测文件夹目录不存在则创建
	private function _check_dir()
	{
		
		$cache_dir = dirname($this->_cache_file);
		$dirs = explode(DS, $cache_dir);

		$dir_path = '';
		foreach ($dirs as $dirname) {
			$dir_path .= $dirname.DS;
			if(!is_dir(MUDDER_ROOT.$dir_path)) {
				if(!mkdir($dir_path, 0777)) {
					return $this->add_error(lang('global_mkdir_no_access', $dir_path));
				}
			}
		}
		return true;
	}

	private function _format_filepath($dir_or_file)
	{
		return trim(str_replace(array("\\","/"), DS, $dir_or_file), DS);
	}


}