<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_comment extends ms_base
{

	function __construct(&$hook)
	{
		parent::__construct();
		$hooks = array('load_comment');
		//!!!此处需要优化,没必要的模块配置被全局加载
		$cfg = $this->loader->variable('config','comment');
		if($cfg['interface_hooks']) {
			$arr = explode(',', $cfg['interface_hooks']);
			if($arr) $hooks = array_merge($hooks, $arr);
		}
		$hook->register($hooks, $this);
	}

	//加载评论显示模块
	function load_comment($params)
	{
		global $_CFG, $_G;
		$loader = _G('loader');
		//当前登录会员类
		$user = _G('user');
		//评论模型类
		$COMMENT = $loader->model(':comment');

		//评论模块配置信息
		$cfg = $loader->variable('config','comment');
		//模块设置不现实任何评论内容
		if(!$params || $cfg['hidden_comment']) return;

		$idtype = $params['idtype']; //评论对象标示
		$id = $params['id'];    //评论对象ID
		$title = $params['title']; //评论标题
		$nextjsfun = $params['nextjsfun']; //提交评论后执行的js函数名
		$comments = $params['comments']; //评论数量
		$extra_id = (int)$params['extra_id']; //扩展ID

		//评论系统接口管理器
		$ITFN = $_G['loader']->model('comment:interface_manage');
		//获取评论接口类
		$ITF = $ITFN->factory();
		//使用评论功能还有第三方社会化评论
		$use_local_comment = get_class($ITF) == 'msm_comment_local';
		if($cfg['use_local_comment']) $use_local_comment = true;

		//评论权限
		$access = $user->check_access('comment_disable', $COMMENT, false);
		//评论开关
		$enable_post = $params['enable_post'] && !$cfg['disable_comment'];
		//评论时允许评分
		$enable_grade = $params['enable_grade'];
		//加载模板
		if($_G['in_mobile']) {
			//手机html5模板
			include mobile_template('comment_load');
		} else {
			include template('comment_load');
		}
	}

	function login_after($params)
	{
		$this->loader->model('comment:interface_manage')->hook('login_after', $params);
	}

	function logout_after($params = null)
	{
		$this->loader->model('comment:interface_manage')->hook('logout_after', $params);
	}


}