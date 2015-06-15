<?php

/**
* 绑定微信帐号
*/
class mc_weixin_bind extends ms_base
{
	public $passport_obj;
    public $openid='';
    public $sign='';

	function __construct()
	{
		parent::__construct();
		$this->passport_obj = $this->loader->model('member:passport'); 
	}

	public function check_sign()
	{
		$time = strtotime(date('Y-m-d'), $this->timestamp);
		$openid = _input('openid', '', MF_TEXT);
		$sign = _input('sign', '', MF_TEXT);
		if($sign == $this->get_sign_hash($openid, $time)) {
            $this->openid = $openid;
            $this->sign = $sign;
            return true;
        }
		$this->add_error('微信绑定参数验证失败，请返回微信通过绑定连接重新进入绑定。');
		return false;
	}

	public function bind()
	{
		$username = _post('username', '', MF_TEXT);
		$password = _post('password','');

		//检测是否能登录，并返回登录会员数据
		$login_obj  = $this->loader->model('member:login');
        $member     = $login_obj->check_login($username, $password);
		if(!$member) {
			//登录失败，返回所悟信息
			$this->add_error($login_obj);
			return false;
		}
		//检测登陆的账号是否已经绑定了
		if($this->passport_obj->bind_exists_by_uid('wechat', $member['uid'])) {
			return $this->add_error('对不起，您准备绑定的本地账号已被其他微信账号绑定了。');
		}
		return $this->passport_bind($member['uid']);
	}

	public function get_sign_hash($openid, $time)
	{
		return create_formhash($openid, $time, '');
	}

	private function passport_bind($uid)
	{
		$token = new stdClass;
		$token->name = 'wechat';
		$token->id = $this->openid;
		$token->access_token = '';
		$token->expires_in = 0;
		//绑定
		$result = $this->passport_obj->bind($uid, $token);
		if(!$result) {
			$this->add_error($this->passport_obj);
		}

		return $result;
	}

}