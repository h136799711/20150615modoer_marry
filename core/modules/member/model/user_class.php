<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* 访问会员（登录）的交互会话类
*/
class msm_member_user extends ms_base {

	//会员登录类
	public $login = null;

	//回话唯一ID
	public $session_id = '';

	function __construct()
	{
		parent::__construct();
		$this->login = $this->loader->model('member:login');
		$this->session_id = _G('session')->get_id();

		$this->modcfg = $this->loader->variable('config','member');
		if(isset($this->modcfg['passport']))
			$this->passport = @unserialize($this->modcfg['passport']);
	}

	//类成员属性从login设置和获取
	function __set($key, $value)
	{
		$this->login->$key = $value;
	}

	function __get($key)
	{
		return $this->login->$key;
	}

	//登录
	function login($username, $password, $remember_time)
	{
		return $this->login->login($username, $password, $remember_time);
	}

	//登出
	function logout()
	{
		$this->login->logout();
	}

	//拉取当前会话用户的表数据
	function fetch() 
	{
		return $this->login->fetch();
	}

    //判断密码
    function check_password($password) 
    {
        return $this->loader->model(':member')->check_password($this->uid, $password);
    }

    //判断支付密码
    function check_paypw($password) 
    {
        return $this->loader->model(':member')->check_paypw($this->uid, $password);
    }

	//检测头像是否设置
	function check_avatar() 
	{
		return $this->loader->model(':member')->check_avatar($this->uid);
	}

	//检测操作权限
	function check_access($key, &$callback, $jump=TRUE) 
	{
		if(is_array($key)) {
			foreach ($key as $k) {
				if(!$this->check_access($k, $callback, $jump)) return false;
			}
			return true;
		} else {
			//获取当前用户组的关联操作权限
			$value = $this->get_access($key);
			//callback是模型类时，执行其check_access函数
			if(is_object($callback)) {
				return $callback->check_access($key,$value, $jump);
			}
			return $callback($key, $value);			
		}
	}

	//修改登录密码
	function change_password($old, $new, $new2, $ignore_old = false)
	{
		if($need_old && !$old) redirect('member_post_empty_pw');
		if(!$new || $new != $new2) redirect('member_post_empty_eq_pw');

		$member_obj = $this->loader->model(':member');

		if($error = $member_obj->check_password_format($new, TRUE))
		{
			redirect($error);
		}

		//判断(不忽略旧密码判断)
		if(!$ignore_old) {
			if(md5($old) != $this->password) redirect('member_post_empty_pw_error');
		}

		//修改密码
		$member_obj->modify_password($this->uid, $new);

		//update cookie
		$this->login->auto_login($this->uid);
	}

	//修改支付密码
	function change_pay_password($old, $new, $new2, $ignore_old = false)
	{
		if(!$new || $new != $new2) redirect('member_post_empty_eq_pw');

		$member_obj = $this->loader->model(':member');
		//检测密码格式是否可用
		if($error = $member_obj->check_password_format($new, 1))
		{
			redirect($error);
		}
		//是否已经设置过支付密码
		if($this->paypw && !$ignore_old)
		{
			if(!$old) redirect('member_post_empty_pw');
			if(md5($old) != $this->paypw) redirect('member_post_empty_pw_error');
		}
		//更新数据库
		return $member_obj->modify_paypw($this->uid, $new);
	}

	//修改邮箱账号
	function change_email($email)
	{
		$member_obj = $this->loader->model(':member');
		return $member_obj->modify_email($this->uid, $email);
	}

	//修改手机号
	function change_mobile($mobile)
	{
		$member_obj = $this->loader->model(':member');
		return $member_obj->modify_mobile($this->uid, $mobile);
	}

	//获取当前用户的权限值
	function get_access($key)
	{
		$usergroups = $this->loader->variable('usergroup_'.$this->groupid, 'member');
		$access = $usergroups['access'][$key];
		return $access;
	}
}

/** end **/
