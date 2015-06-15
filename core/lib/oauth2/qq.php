<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* QQ登录二次封装
*/
class oauth2_qq extends ms_oauth2
{
	public $name = 'qq';
	
	private $openid = '';

	function __construct()
	{
		parent::__construct();
		if(!class_exists('QQOAuthV2')) {
			require(MUDDER_ROOT.'api'.DS.'qqoauth2.php');
		}
		$this->auth = new QQOAuthV2(
			S('member:passport_qq_appid'),
			S('member:passport_qq_appkey')
		);
	}

	function getAuthorizeURL($callback_url)
	{
		return $this->auth->getAuthorizeURL($callback_url);
	}

	function getAccessToken($callback_url)
	{
		$token = $this->auth->getAccessToken( $_REQUEST['code'] , $callback_url);
		if(!$token || !$token['access_token']) return;
        $access_token = _T($token['access_token']);
        $expires_in = _G('timestamp') + $token['expires_in'];
        $this->openid = $this->auth->getOpenid($access_token);

		$newtoken = array(
			'name' 			=> $this->name, 
			'id'			=> $this->openid,
			'access_token' 	=> $access_token, 
			'expires_in' 	=> $expires_in,		//授权剩余时间
		);
		return $this->_createToken($newtoken);
	}

	function getUserInfo($token)
	{
	    if(!$this->openid) {
	    	$this->openid = $token->id;
	    }
	    $c = new QQClientV2(
	    	S('member:passport_qq_appid'),
	    	S('member:passport_qq_appkey'), 
	        $token->access_token, 
	        $this->openid
	    );
	    $me = $c->get_user_info();
	    if(strtoupper(_G('charset')) != 'UTF-8') {
	        foreach($me as $k => $v) {
	            if(is_string($v)) $me[$k] = charset_convert($v,'utf-8',_G('charset'));
	        }
	    }
	    $userinfo = array();
	    $userinfo['username'] = $me['nickname'];
	    $userinfo['passport_id'] = $this->openid;

	    return $userinfo;
	}
}