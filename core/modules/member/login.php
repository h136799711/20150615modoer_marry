<?php
/**
* user login
* @author moufer<moufer@163.com>
* @package modoer
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'login');

//IP禁止登录
if($MOD['login_banip'] && check_ipaccess($MOD['login_banip']))
{
	redirect('member_login_banip');
}

//登录后返回
$forward = $_GET['forward'] ? _T(base64_decode(rawurldecode($_GET['forward']))) : get_forward(url('member/index'));
if(strposex($forward, 'logout') 
	|| strposex($forward, 'reg') 
	|| strposex($forward, 'forget') 
	|| strposex($forward, 'login') 
	|| !strposex($forward, $_G['web']['domain']) 
	|| $_G['in_ajax']) 
{
	$forward = U('member');
}

$op = _get('op');

//旧版兼容跳转
if($op == 'forget') location(U('member/forget'));
if($op == 'passport_login') location(U("member/passport/op/login/type/{$_GET['type']}"));

if($op == 'logout') { //登出

	//未登录，跳转到首页
	//if( ! $user->isLogin) location(U('index'));

	//登出并返回同步登出代码
	$user->logout();

	//无同步代码，则直接跳转
	if( ! $user->sync_code) location($forward);

	//跳转到首页
	redirect(lang('global_op_succeed').$user->sync_code, U('index')); //$forward

} elseif($op == 'ajax_login') { //AJAX无刷新登录

	if($user->isLogin) redirect('member_login_logined');
	//反向登录，跳转到登录网站的网址
	if($user->passport['enable']) redirect('', $user->passport['login_url']);

	require_once template('member_login_ajax');

} else {

	//已登录
	if($user->isLogin) redirect('member_login_logined');

	//反向登录，跳转到登录网站
	if($user->passport['enable']) location($user->passport['login_url']);

	if(check_submit('onsubmit') || check_submit('dosubmit')) {
		//登录验证码
		if($MOD['seccode_login']) check_seccode($_POST['seccode']);

		$username   = _post('username', '', MF_TEXT);
		$password   = _post('password', '', MF_TEXT);
		$life       = _post('life', '0', MF_INT);

		//登录失败时
		if( ! $user->login($username, $password, $life)) {
			$msg = $user->has_error() ? $user->error() : 'member_login_lost';
			redirect($msg);
		}

		//ajax登录
		if($_G['in_ajax']) {
			//登录成功，并加载同步登录代码
			$sync = '';
			if($user->sync_code) {
				$sync = $user->sync_code.'<script type="text/javascript">alert("'.lang('member_login_succeed').'")</script>';
			}
			echo fetch_iframe($sync);
			output();
		}

		//登录后返回页面
		$url = _post('forward', '', MF_TEXT);
		$url = $url ? $url : $forward;
		//无同步代码，则直接跳转
		if( ! $user->sync_code) {
			location($url);
		}
		//同步登录时，不能直接跳转，因为需要再提示页面加载同步登录的代码来实现同步功能
		redirect(lang('member_login_succeed').$user->sync_code, $url ? $url : $forward);

	} else {

		//登录表单提交URL
		$form_action = U("member/login/op/login/rand/{$_G['random']}");

		$subtitle = lang('member_login_title');
		require_once template('member_login');
	}
}

/** end **/