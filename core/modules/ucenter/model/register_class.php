<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

//加载父类
_G('loader')->model('member:register', false, null, false);

/**
* UC会员注册
*/
class msm_ucenter_register extends msm_member_register
{

	private $jump_uc_register = false;
	private $jump_uc_check_exists = false;
	protected $local_member_obj = null;

	function __construct()
	{
		parent::__construct();
		//加载本地数据库member模型
		$this->local_member_obj = $this->loader->model(':member', true, null, false);
	}

	//针对UC登陆时，Modoer数据库内不存在用户是的录入
	function uc_add($post, $activation = false)
	{
		//UID冲突文件
		if(!$this->member_model->check_uid_clash($post['uid'])) {
			$this->add_error($this->member_model);
			return;
		}

		//跳过UC注册，因为本身就是从UC导入的
		$this->jump_uc_register = true;
		//如果是激活会员的话，跳过UC已存在帐号的验证
		if($activation) {
			$this->jump_uc_check_exists = true;
		}

		//发送手机验证码
		$this->mobile_verify = false;
		//发送邮件
		$this->email_verify = S('member:email_verify') && $post['email'];

		$post['password2'] = $post['password'];

		$this->post_data = $post;
		//进入账号注册，并返回UID
		$uid = $this->_add();

		return $uid;
	}

	//注册前
	protected function _add_befor()
	{
		parent::_add_befor();

		$this->member_model->jump_uc_register = $this->jump_uc_register;
		$this->member_model->jump_uc_check_exists = $this->jump_uc_check_exists;

		if($this->has_error() || $this->jump_uc_register) return;

		return true;
	}

}

/** end **/