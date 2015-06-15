<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

//加载父类
_G('loader')->model('member:login', false, null, false);

/**
* UC会员登录
*/
class msm_ucenter_login extends msm_member_login
{
	protected $local_member_obj = null;
	function __construct()
	{
		parent::__construct();
		//加载本地数据库member模型
		$this->local_member_obj = $this->loader->model(':member', true, null, false);
	}

	//登录账号检测，成功则返回会员数据
	function check_login($username, $password) 
	{
		//先从本地数据库获取会员数据
		$member = parent::check_login($username, $password);


		//uc登录
		$result = uc_user_login($username, $password);

		//uc登录失败，uc没有此会员
		if(!$result)
		{
			$this->add_error('member_login_lost');
			return;
		}
		else
		{
			//解析返回值，获取相关数据
			list($uid, $username, $pw, $email, $dofname) = $result;
			if($uid < 0)
			{
				if($member) $this->add_error('请管理员将用户导入UCenter会员表。');
				if(!$member) $this->add_error('ucenter_login_' . $uid);
				return;
			}
			else
			{
				//本地和UC的UID不同，则说明用户名冲突
				if($member && $member['uid'] != $uid)
				{
					$this->add_error('UCenter会员与网站会员名冲突，请管理员先将网站会员导入UCenter会员表。');
					return;
				}
				elseif(!$member)
				{
					//echo 'ddd';
					//如果本地没有找到，可能是密码不同造成，通过UID重新获取数据
					$member = $this->local_member_obj->read($uid, MEMBER_READ_UID);
					if($member)
					{
						//更新本地密码
						$this->local_member_obj->modify_password($uid, $pw);
					}
					else
					{
						//如果还是没有找到，则表示本地没有这个会员，下一步需要把会员保存仅本地数据库(即：激活帐号)
						$reg_model = $this->loader->model('ucenter:register');

						$post = array();
						$post['uid'] 		= $uid;
						$post['username'] 	= $username;
						$post['password'] 	= $pw;
						$post['email'] 		= $email;
						$uid = $reg_model->uc_add($post, true); //第二个参数为表示为激活帐号

						//新建账号失败
						if(!$uid) {
							$this->add_error($reg_model);
							return;
						}
						//再次从数据库获取数据
						$member = $this->local_member_obj->read($uid, MEMBER_READ_UID);
					}
				}
			}
		}

		return $member;
	}

	//登出
	protected function _logout_after() 
	{
		parent::_logout_after();
		//UC同步登出代码
		//vp('_logout_after:'.$this->uid);
		$this->sync_code = uc_user_synlogout();
	}

	protected function _login_after()
	{
		parent::_login_after();
		//UC同步登录代码，cookie自动登录时不获取
		if(!$this->isAutoLogin)
		{
			$this->sync_code = uc_user_synlogin($this->uid);

			//vp('_login_after_uid:'.$this->uid);
			//vp('_login_after_sync_code:'._T($this->sync_code));
		}
	}

}

/** end **/