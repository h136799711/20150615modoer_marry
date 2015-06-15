<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op   = _input('op', '', MF_TEXT);
$type = _input('type', '', MF_TEXT);
if ( ! $type) {
	$type = _input('passport', '', MF_TEXT);
}
//从手机web页面进入
$source =_input('source' ,'', MF_TEXT);

$in_mobile  = $source == 'mobile_web';
if($in_mobile) {
	$_G['in_mobile'] = true;
	//加载手机模板页面需要的函数库
	_G('loader')->helper('function', 'mobile');
}

$auth = ms_oauth2::factory($type);
if (!$auth) {
	redirect('member_passport_type_unknow');
}

if ($op == 'login') {

	$callbackurl = str_replace('&amp;', '&', U("member/passport/op/callback/type/$type/source/$source", true, 'normal'));
	$aurl = $auth->getAuthorizeURL( $callbackurl );
	location( $aurl );

} elseif ($op == 'callback') {

	$callbackurl = str_replace('&amp;', '&', U("member/passport/op/callback/type/$type/source/$source", true, 'normal'));
	//获取第三方登录操作授权令牌
	$token = $auth->getAccessToken($callbackurl);
	if (!$token) {
		redirect('member_passport_token_error');
	}
	//保存token到session，方便全局使用
	$auth->setTokenSession($token);
	//获取用户基本数据
	$userinfo = $auth->getUserInfo($token);
	if (!$userinfo) {
		redirect('member_passport_getinfo_error');
	}

	$passport_type 	= $token->name;
	$passport_id 	= $token->id;

	$message = lang('member_passport_succeed');
	$passport_obj =& $_G['loader']->model('member:passport');

	if (!$passport_id) {
		redirect('member_passport_token_error');
	} elseif ($user->login->passport_login($token)) {
		//尝试检查绑定账号斌登录
		$url = $url = $in_mobile ? U('member/mobile') : U("member/index");

		//存在同步登录代码
		if($user->sync_code && ! $in_mobile) {
			redirect(lang('member_login_succeed').$user->sync_code, $url);
		}
		location($url);
	} else {
		//本地没有账号绑定当前第三方账号时，进入注册本地账号或绑定本地账号
		$url = U("member/passport/op/reg/type/$type/source/$source", true, 'normal');
		location($url);
	}
	
} elseif ($op == 'bind') {
	
	//账号绑定
	if (check_submit('dosubmit') || check_submit('dosbumit') || check_submit('onsubmit')) {
		//验证码
		if($MOD['seccode_login']) check_seccode($_POST['seccode']);

		$token_id 	= _post('passport_id', '', MF_TEXT);
		$passport_obj 	= $_G['loader']->model('member:passport');
		$passport 		= $passport_obj->get_data($auth->name, $passport_id);
		//判断是否已经被绑定过
		if($passport['uid'] > 0) {
			redirect('member_passport_bind_exists');
		} else {
			//从cookies里获取保存的token信息
			$token = $auth->getTokenFromSession();
			if ( ! $token || $token->id != $token_id) {
				redirect('对不起，账号绑定失败，第三方登录授权信息无法获取。');
			}

			$username   = _post('username', '', MF_TEXT);
			$password   = _post('password', '', MF_TEXT);
			$life       = _post('life', '0', MF_INT);

			//验证登录账号
			if ( ! $member = $user->login->check_login($username, $password)) {
				if($user->login->has_error()) redirect($user->login->error());
				redirect('member_login_lost');
			}

			//绑定账号
			$passport_obj->bind($member['uid'], $token);
			//登录
			$user->login->passport_login($token, $life);

			$url = $in_mobile ? U('member/mobile') : U('member/index');

			//同步登录时，不能直接跳转，因为需要再提示页面加载同步登录的代码来实现同步功能
			if( $user->sync_code && ! $in_mobile) {
				redirect(lang('member_passport_bind_login_succeed') . $user->sync_code, $url);
			}
			//无同步代码，则直接跳转
			location($url); 
		}

	} else {

		$token = $auth->getTokenFromSession();
		if(!$token) {
			redirect('member_passport_token_error');
		}
		//获取第三方登录用户数据
		$userinfo = $auth->getUserInfo($token);
		if(!$userinfo) {
			redirect('member_passport_lost');
		}

		$passport_type  = $token->name;
		$passport_id    = $token->id;
		$username       = $userinfo['username'];
		$passport       = $username && $passport_id;

		if(!$passport) redirect('member_passport_bind_invalid');

		if(strtoupper(_G('charset')) != 'UTF-8') {
			//$username = charset_convert($username, 'utf-8', _G('charset'));
		}

		$passport_obj = $_G['loader']->model('member:passport');
		//检测是否已经绑定过
		if($uid = $passport_obj->get_uid($token->name, $token->id)) {
			redirect('member_passport_bind');
		}

		$MP = $_G['loader']->model('member:profile');

		$typename   = lang('member_passport_type_'.$auth->name);
		$title      = lang('member_passport_login', array($typename, $username, $_G['sitename']));
		$subtitle   = lang('member_login_title');

		//绑定登录表单提交URL
		$form_action = U("member/passport/op/bind/source/$source/rand/{$_G['random']}");

		if($in_mobile) {
			require_once mobile_template('member_login');
		} else {
			require_once template('member_login');
		}
	}

} elseif($op == 'reg') {

	 //账号注册（并绑定） 
	$token = $auth->getTokenFromSession();
	if(!$token) {
		redirect('member_passport_token_error');
	}
	$userinfo = $auth->getUserInfo($token);
	if(!$userinfo) {
		redirect('member_passport_lost');
	}

	$passport_id    = $token->id;
	$passport_type  = $token->name;
	$username       = $userinfo['username'];
	$email       	= $userinfo['email'];
	$passport       = $username && $passport_id;

	if($passport) {
		$passport_obj =& $_G['loader']->model('member:passport');
		//查看是否已经注册绑定过
		if($user->login->passport_login($token)) {
			$url = $in_mobile ? U('member/mobile') : U("member/index");

			//存在同步登录代码
			if($user->sync_code && ! $in_mobile) {
				redirect(lang('member_login_succeed').$user->sync_code, $url);
			}
			location($url);
		}

		//强制跳过本地注册
		if($MOD['passport_reg_skip'] == 'force') {
			location(U("member/passport/op/auto_reg/type/$type/source/$source", true, 'normal'));
		}

		$typename   = lang('member_passport_type_'. $auth->name);
		$title      = lang('member_passport_reg', array($typename, $username, $typename));
	}

	//邀请注册获取
	if($inviter_uid = _cookie('inviter_uid', 0, MF_INT_KEY)) {
		$invite_model = $_G['loader']->model('member:invite');
		//邀请注册检查
		if($invite_model->check_enable($inviter_uid)) {
			$inviter = $IV->get_inviter();
		} else {
			$invite_message = $invite_model->error();
		}
	}

	$subtitle = lang('member_reg_title');

    if($in_mobile) {
        require_once mobile_template('member_reg');
    } else {
        require_once template('member_reg');
    }

} elseif($op == 'auto_reg') {

	//跳过本地注册
	if( ! S('member:passport_reg_skip')) {
		redirect('不能跳过本地注册，请返回。', U("member/passport/op/reg/type/$type/source/$source", true, 'normal'));
	}

	$token = $auth->getTokenFromSession();
	if( ! $token) {
		redirect('member_passport_token_error');
	}

	$userinfo = $auth->getUserInfo($token);
	if( ! $userinfo) {
		redirect('member_passport_lost');
	}

	$reg_model = $_G['loader']->model('member:register');
	//第三方账号在本地系统自动注册账号
	$uid = $reg_model->passport_add($token, $userinfo);

	if (!$uid) {
		if($reg_model->has_error()) {
			redirect($reg_model->error());
		}
		redirect('对不起，跳过本地注册失败！', U("member/passport/op/reg/type/$type/source/$source", true, 'normal'));
	}

	$url = $in_mobile ? U('member/mobile') : U("member/index");

	//存在同步登录代码
	if($user->sync_code && ! $in_mobile) {
		redirect(lang('member_reg_succeed').$user->sync_code, $url);
	}
	location($url);
	
} else {

	redirect('global_op_unkown');

}

/** end **/