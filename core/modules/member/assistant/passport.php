<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op 			= _input('op', null, MF_TEXT);
$psname 		= _input('psname', null, MF_TEXT);

$callbackurl 	= str_replace('&amp;', '&', U("member/index/ac/passport/op/__OP__/psname/__PSNAME__", true, 'normal'));
$passportlist 	= $MOD['passport_list'] ? explode(',', $MOD['passport_list']) : '';

if($psname)
{
	$auth 	= ms_oauth2::factory($psname);
	if (!$auth)
	{
		redirect('member_passport_type_unknow');
	}
}

// 第三方登录绑定授权管理类
$PT = $_G['loader']->model('member:passport');

switch ($op) 
{
	//跳转进入获取token的url
	case 'get_token':

		$psname	= _get('psname', null, MF_TEXT);
		$nop 	= _get('nop', 'bind', MF_TEXT);

		$callbackurl = str_replace(array('__OP__','__PSNAME__'), array($nop, $psname), $callbackurl);
		_G('session')->callbackurl = $callbackurl;
		$aurl = $auth->getAuthorizeURL( $callbackurl );
		location( $aurl );

		break;

	// 绑定
	case 'bind':
	// 获取或更新token
	case 'token':

		$callbackurl = str_replace('__PSNAME__', $psname, $callbackurl);

		//获取第三方登录操作授权令牌
		$token = $auth->getAccessToken($callbackurl);
		if (!$token)
		{
			redirect('member_passport_token_error');
		}

		if ($op == 'bind')
		{
			//绑定数据不存在
			if($PT->bind_exists($token->name, $token->id)) redirect('member_passport_bind_exists');
		}

		//进行绑定操作和更新token
		$id = $PT->bind($user->uid, $token);

		//是否有指定了返回页面
		$url = $_G['session']->token_forword;
		$_G['session']->token_forword=null;

		redirect('global_op_succeed', $url?$url:url('member/index/ac/passport'));
		break;

	// 解除绑定
	case 'unbind':

		$psname = _input('psname', null, MF_TEXT);
		$PT->unbind($psname, $user->uid);

		redirect('global_op_succeed', url('member/index/ac/passport'));
		break;

	default:

		$passport = $PT->get_list($user->uid);
		$tplname = 'passport';

		break;
}
?>