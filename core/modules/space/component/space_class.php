<?php
/**
* @author moufer<moufer@163.com>
* @pageage space
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* 个人空间
*/
class mc_space extends ms_base 
{

	public $member_obj;
	public $space_obj;

	//会员的个人空间表和会员表信息
	private $_data = array();

	function __construct()
	{
		parent::__construct();
		$this->member_obj = $this->loader->model(':member');
		$this->space_obj = $this->loader->model(':space');
	}

	function __get($key)
	{
		if (isset($this->_data[$key]))
		{
			return $this->_data[$key];
		}
		return;
	}

	//设置需要查看的个人空间UID
	function set_user($uid, $is_username = false)
	{
		//读取会员信息
		$member = $this->member_obj->read($uid, $is_username ? MEMBER_READ_USERNAME : MEMBER_READ_UID);
		if( ! $member)
		{
			$this->add_error('member_empty');
			return;
		}

		//读取个人空间信息
		$space = $this->space_obj->read($member['uid']);
		//如果个人空间不存在，则创建
		if ( $this->space_obj->create($member['uid'], $member['username']))
		{
			//创建完成后，再读取个人空间表
			$space = $this->space_obj->read($member['uid']);
		}

		//将member表和space表数据复制成为类成员变量
		foreach (array('member', 'space') as $name)
		{
			foreach ($$name as $key => $value)
			{
				$this->$key = $value;
			}
		}

		return true;
	}

	//获取个人空间加载页面
	function routing()
	{
		$pr = trim(_get('pr', '', MF_TEXT), '_');
		if(strpos($pr, '_')) {
			list($module, ) = explode('_', $pr);
			$pr = substr($pr, strlen($module)+1);
		} elseif($pr) {
			$module = $pr;
			$pr = 'index';
		}
		if(!$pr) {
			$module = 'space';
			$pr = 'index';
		}
		if(!check_module($module)) return;
		//是否是个人空间模块内的页面文件
		if($module=='space') {
			$pagefile = MUDDER_CORE.'modules'.DS.'space'.DS.$pr.'.php';
		} else {
			$pagefile = MUDDER_CORE.'modules'.DS.$module.DS.'space'.DS.$pr.'.php';
		}
		if ( ! is_file($pagefile)) {
			return;
		}
		//返回page页面文件地址
		return $pagefile;
	}

	//获取个人空间导航连接
	function fetch_nav()
	{
		return _G('hook')->hook('space_nav_link', $this, TRUE);
	}
}