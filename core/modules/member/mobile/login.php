<?php
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'login');

//IP禁止登录
if($MOD['login_banip'] && check_ipaccess($MOD['login_banip'])) {
	redirect('member_login_banip');
}

//登录后返回
$forward = $_GET['forward'] ? _T(base64_decode(rawurldecode($_GET['forward']))) : get_forward(U('mobile/index'));
if(strposex($forward, 'logout') 
	|| strposex($forward, 'reg') 
	|| strposex($forward, 'forget') 
	|| strposex($forward, 'login') 
	|| !strposex($forward, $_G['web']['domain']) 
	|| $_G['in_ajax']) {
	$forward = U('mobile/index');
}

$op = _input('op', null);

if($op == 'logout') {

	//登出
	$user->logout();
	location(url('mobile/index'));

} elseif($op == 'fastlogin') {

    //登出已登录帐号
    if($user->isLogin) $user->logout();
	//快速登录
	if( ! $user->login->fast_login() ) {
		redirect($user->login->error());
	}
	location(U('member/mobile'));
    exit;

} else {

	if($user->isLogin) {
		location(url('mobile/index'));
	}

	if($_POST) {

		$username   = _post('username', '', MF_TEXT);
		$password   = _post('password', '', MF_TEXT);
		$life       = _post('life', '0', MF_INT);

		//登录失败时
		if( ! $user->login($username, $password, $life)) {
			$msg = $user->has_error() ? $user->error() : 'member_login_lost';
			redirect($msg);
		}

		//登录后返回页面
		$url = _post('forward', '', MF_TEXT);
		//无同步代码，则直接跳转
		if( ! $user->sync_code) location($url);

		//同步登录时，不能直接跳转，因为需要再提示页面加载同步登录的代码来实现同步功能
		redirect(lang('member_login_succeed') . $user->sync_code, $url ? $url : $forward);

	} else {

		//登录表单提交URL
		$form_action = U("member/mobile/do/login/rand/{$_G['random']}");

		$header_title = '会员登录';
		include mobile_template('member_login');
	}

}