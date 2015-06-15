<?php
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'reg');

//已登录
if($user->isLogin) redirect('member_reg_logined');

//关闭注册
if($MOD['closereg']) redirect('member_reg_closed');

//IP禁止注册
if($MOD['reg_banip'] && check_ipaccess($MOD['reg_banip'])) {
	redirect('member_reg_banip');
}

//注册后返回
$forward = $_GET['forward'] ? $_GET['forward'] : U('mobile/index', true);
if(strposex($forward, 'logout') 
	|| strposex($forward, 'reg') 
	|| strposex($forward, 'forget') 
	|| strposex($forward, 'login') 
	|| strposex($forward, 'passport') 
	|| !strposex($forward, $_G['web']['domain']) 
	|| $_G['in_ajax']) {

	$forward = U('mobile/index');
}
$forward = _T(rawurldecode(rawurldecode($forward)));

//注册类
$reg_obj = $_G['loader']->model('member:register');

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

		// 从 dbsession 获取保存的 token
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
		if($reg_obj->has_error()) redirect($reg_obj->get_message());
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

	$header_title = lang('member_reg_title');
	require_once mobile_template('member_reg');
}