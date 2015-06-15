<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* JD登录二次封装
*/
class oauth2_jd extends ms_oauth2
{
	public $name = 'jd';
	
	private $openid = '';

	function __construct()
	{
		parent::__construct();
		if(!class_exists('JD_OAuthV2')) {
			require(MUDDER_ROOT.'api'.DS.'jd_oauth2.php');
		}
		$this->auth = new QQOAuthV2(
			S('member:passport_jd_appid'),
			S('member:passport_jd_appkey')
		);
	}

	function getAuthorizeURL($callback_url)
	{
		return $this->auth->getAuthorizeURL($callback_url);
	}

	function getAccessToken($callback_url)
	{
		$token = $this->auth->getAccessToken( $_REQUEST['code'] , $callback_url);
		if(!$token) return;
        $access_token = _T($token->access_token);
        $expires_in = _G('timestamp') + $token->expires_in;

		$newtoken = array(
			'name' 			=> $this->name, 
			'id'			=> $token->uid,
			'access_token' 	=> $access_token, 
			'expires_in' 	=> $expires_in,		//授权剩余时间
		);
		return $this->_createToken($newtoken);
	}

	function getUserInfo($token)
	{
	    $userinfo = array();
	    $userinfo['username'] = $token->user_nick;
	    $userinfo['passport_id'] = $token->uid;

	    if(strtoupper(_G('charset')) != 'UTF-8') {
	        foreach($userinfo as $k => $v) {
	            if(is_string($v)) $userinfo[$k] = charset_convert($v,'utf-8',_G('charset'));
	        }
	    }

	    return $userinfo;
	}
}