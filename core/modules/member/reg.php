<?php
/**
* user register
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'reg');

$op = _get('op');

//注册类
$reg_obj = $_G['loader']->model('member:register');

//注册时邮箱验证
if($op == 'verify_mail') {
	//验证码
	$seccode = _get('seccode', null, MF_TEXT);
	if(!$seccode) redirect('member_verify_no_seccode');
	//验证
	$email_verify = $_G['loader']->model('member:email_verify');
	if($data = $email_verify->verify($seccode, 'member_activation', false)) {
		//激活用户
		if(!$reg_obj->activation_user($data['uid'])) {
			redirect($reg_obj->error());
		}
		redirect('member_verify_succeed', U('member/index'));
	} else {
		redirect($email_verify->error());
	}
}

//已登录
if($user->isLogin) redirect('member_reg_logined');

//关闭注册
if($MOD['closereg']) redirect('member_reg_closed');

//IP禁止注册
if($MOD['reg_banip'] && check_ipaccess($MOD['reg_banip'])) {
	redirect('member_reg_banip');
}

//反向登录跳转
if($user->passport['enable']) location($user->passport['reg_url']);

//注册后返回
$forward = $_GET['forward'] ? $_GET['forward'] : url('member/index','',true);
if(strposex($forward, 'logout') 
	|| strposex($forward, 'reg') 
	|| strposex($forward, 'forget') 
	|| strposex($forward, 'login') 
	|| strposex($forward, 'passport') 
	|| !strposex($forward, $_G['web']['domain']) 
	|| $_G['in_ajax']) {

	$forward = $_G['cfg']['siteurl'];
}
$forward = _T(rawurldecode(rawurldecode($forward)));

if(check_submit('dosubmit')) {

	// 验证码
	if($MOD['seccode_reg']) check_seccode($_POST['seccode']);

	$post_data = $_G['loader']->model(':member')->get_post($_POST);

	// 第三方账号在本地注册
	if($token_name	= _post('passport', '', MF_TEXT)) {

		$token_id	= _post('passport_id', '', MF_TEXT);

		// 加载第三方登录 Auth 类
		$auth = ms_oauth2::factory($token_name);
		if(!$auth) {
			redirect('member_passport_type_unknow');
		}

		// 从 dbsession 中获取保存着的 token
		$token = $auth->getTokenFromSession();
		if( ! $token || $token->id != $token_id) {
			redirect('对不起，账号绑定失败，第三方登录授权信息无法获取。');
		}

		//判断这个第三方账号是否已经绑定过
		$passport_obj 	= $_G['loader']->model('member:passport');
		$passport 		= $passport_obj->get_data($auth->name, $auth->id);
		//判断是否已经被绑定过
		if($passport['uid'] > 0) {
			redirect('member_passport_bind_exists');
		}

		// 不创建本地密码，自动随机填充
		if( ! S('member:passport_pw') && ! isset($post_data['password'])) {
			$post_data['password'] = $post_data['password2'] = random();
		}
	}

	//注册账号，并返回UID
	$uid = $reg_obj->add($post_data);
	if( !$uid) {
		if($reg_obj->has_error()) redirect($reg_obj->error());
        redirect('member_reg_lost');
	}

	//绑定刚注册的号
	if($passport_obj && $token) {
		$passport_obj->bind($uid, $token);
	}

	//注册成功反馈信息
	$msg = $reg_obj->email_verify ? lang('member_reg_succeed_verify', $user->email) : lang('member_reg_succeed');
	//加载同步登录代码
	$msg .= $user->sync_code;

	//返回上一页
	$url = _post('forward', '', MF_TEXT);

	//登录完成，并加载同步登录代码
	redirect($msg, $url ? $url : $forward);

} else  {

	//邀请注册
	if($inviter_uid = _cookie('inviter_uid', 0, MF_INT_KEY)) {
		$invite_model = $_G['loader']->model('member:invite');
		
		//邀请注册检查
		if($invite_model->check_enable($inviter_uid)) {

			//获取邀请者信息
			$inviter = $invite_model->get_inviter();
		} else {
			//邀请注册错误提示
			$invite_message = $invite_model->error();
		}
	}
//	dump($inviter);
	$subtitle = lang('member_reg_title');
	require_once template('member_reg');
}

/** end **/