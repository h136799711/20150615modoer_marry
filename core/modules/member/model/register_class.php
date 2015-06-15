<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* 会员注册
*/
class msm_member_register extends ms_base
{
	protected $member_model = null;
	protected $post_data	= array();

	//注册时是否进行手机认证
	public $mobile_verify	 = false;
	//注册时是否发邮箱认证码
	public $email_verify	 = false;

	function __construct()
	{
		parent::__construct();
		$this->member_model = $this->loader->model(':member');
	}

	//注册
	function add($post)
	{
		//关闭注册
		if(S('member:closereg'))
		{
			$this->add_error('member_reg_closed');
			return;
		}

		//二次注册判断
		if($this->registered_again())
		{
			$this->add_error('member_post_registered_again');
			return;
		}
		//发送手机验证码
		$this->mobile_verify = S('member:mobile_verify');
		//发送邮件
		$this->email_verify = S('member:email_verify');

		$this->post_data = $post;
		//进入账号注册，并返回UID
		$uid = $this->_add();

		return $uid;
	}

	//第三方网站账号的自动注册
	function passport_add($token, $userinfo)
	{
		//自动生成用户名
		//$username = $userinfo['username'];
		$username = substr($token->id, 0, 8);
		while ( $this->member_model->check_username_exists($username) )
		{
			$username = substr($token->id, 0, 8) . '_' . mt_rand(100,999);
		}

		//自动生成email
		$email = $userinfo['email'] ? $userinfo['email'] : ($token->name . '_' . $username . '@' . get_fl_domain());

		//录入字段
		$this->post_data['username']	= $username;
		$this->post_data['email']		= $email;
		$this->post_data['password']	= $this->post_data['password2'] = random(); //密码随机产生

		$this->mobile_verify 	= false;
		$this->email_verify		= $userinfo['email'] ? true : false;

		//进入账号注册，并返回UID
		$uid = $this->_add();
	
		return $uid;
	}

	//同ip再次注册判断
	function registered_again()
	{
		$again = (int) S('member:registered_again');
		if( ! $again || $again < 1) return false;

		$this->global['db']->from($this->member_model->table);
		$this->global['db']->where('regip', $this->global['ip']);
		$this->global['db']->order_by('regdate','DESC');
		//IP没有注册过
		if(!$detail = $this->global['db']->get_one())
		{
			return false;
		}

		//小于设置通IP两次注册时间
		return $this->timestamp - $detail['regdate'] <= $again;
	}

	//激活等待验证的会员
	function activation_user($uid)
	{
		//先读取会员数据
		$member = $this->member_model->read($uid, 0, 'uid,point,groupid', FALSE);
		if(!$member)
		{
			$this->add_error('member_empty');
			return;
		}
		//不是等待认证状态
		if($$member['groupid'] == '4')
		{
			$this->add_error('会员不再等待认证激活状态，不能进行激活。');
			return;
		}

		//根据积分计算所属用户组ID
        $group_model = $this->loader->model('member:usergroup');
        $groupid = $group_model->point_by_usergroup($member['point']);
        if(!$groupid) $groupid = 10;

        //更新帐号会员组
        $this->member_model->update_group($member['uid'], $groupid);

        return true;
	}

	//拼装字段，并保存
	protected function _add()
	{
		//手机认证时，手机号必填
		if($this->mobile_verify)
		{
			if(!$this->post_data['mobile'])
			{
				$this->add_error('member_reg_mobile_verify_invalid');
				return;
			}
			else
			{
				$verify_model = $this->loader->model('member:mobile_verify');
		    	$verify = $verify_model->set_uniq($this->global['user']->session_id)->get_status();
		    	//手机号验证是否成功
			    if(!$verify || !$verify['status'] || $this->post_data['mobile'] != $verify['mobile'])
			    {
			        $this->add_error('member_reg_mobile_verify_invalid');
			        return;
			    }
			}
		}

		//自动录入基本信息
		$this->post_data['logintime']	= $this->post_data['regdate'] = $this->timestamp;
		$this->post_data['regip'] 		= $this->post_data['loginip'] = $this->ip;
		$this->post_data['logincount'] 	= 1;
		$this->post_data['groupid'] 	= $this->email_verify ? 4 : 10; //需要邮箱认证，则表示需要激活（4：待验证）

		//注册前进行其他操作
		$this->_add_befor();

		//注册前出错，则跳出
		if($this->has_error()) return;

		//保存数据，并返回会员ID
		$uid =  $this->member_model->save($this->post_data);

		//注册时失败
		if(!$uid) return;

		$this->uid = $uid;

		//删除手机验证操作的记录
		if($this->mobile_verify)
		{
			$verify_model->set_uniq($this->global['user']->session_id)->delete();
		}

		//注册后的其他操作
		$this->_add_after();

    	return $uid;
	}

	//注册前
	protected function _add_befor()
	{
		//挂载函数钩子-注册之前
		$this->hook->hook('member_register_befor', array($this));
	}

	//注册完成后，进行的附加操作
	protected function _add_after()
	{
		$uid = $this->uid;
		//自动登录
    	if($this->global['user']->login->auto_login($uid))
    	{
			//自动加载任务申请
	    	$this->loader->model('member:task')->automatic_apply();
    	}

		//检查是否有邀请注册
		if($inviter_uid = _cookie('inviter_uid', 0, MF_INT_KEY))
		{
		    $invite_model = $this->loader->model('member:invite');
		    if($invite_model->check_enable($inviter_uid))
		    {
		        $invite_model->add($inviter_uid);
		    }
		    del_cookie('inviter_uid');
		}

		//发送欢迎短信息
		if(S('member:salutatory_msg'))
		{
			$this->_send_salutatory_msg($this->global['user']->fetch());
		}

		//发送账号激活邮件
		if($this->email_verify)
		{
			$verify_model = $this->loader->model('member:email_verify');
			$verify_model->send_activation($this->global['user']->fetch());
		}

		//挂载函数钩子-注册之后
		$this->hook->hook('member_register_after', array($this));
	}

	//向新注册用户发送站内欢迎短信
	function _send_salutatory_msg($member)
	{

	    if(!$member) return false;

	    //生成短信息内容
	    $subject = lang('member_reg_msg_subject', S('sitename'));
	    $message = S('member:salutatory_msg') ? S('member:salutatory_msg') : lang('member_reg_msg_content');
	    //替换占位标签
	    $rp = array('$sitename', '$username', '$time', '{sitename}', '{username}', '{time}');
	    $sm = array(
	    	S('sitename'),
	    	$member['username'],
	    	date("Y-m-d H:i:s", _G('timestamp'))
	    );
	    $message = str_replace($rp, $sm, $message);

	    //发送站内短
	    $this->loader->model('member:message')->send(0, $member['uid'], $subject, $message);
	}

}

/** end **/