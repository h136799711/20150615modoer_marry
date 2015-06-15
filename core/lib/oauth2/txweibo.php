<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* 
*/
class oauth2_txweibo extends ms_oauth2
{
	public $name = 'txweibo';
	
	private $openid = '';

	function __construct()
	{
		parent::__construct();
		if(!class_exists('TXweiboOAuthV2')) {
			require(MUDDER_ROOT.'api'.DS.'txweibooauth2.php');
		}
		$this->auth = new TXweiboOAuthV2(
			S('member:passport_txweibo_appid'),
			S('member:passport_txweibo_appkey')
		);
	}

	function getAuthorizeURL($callback_url)
	{
		return $this->auth->getAuthorizeURL($callback_url);
	}

	function getAccessToken($callback_url)
	{
		$token = $this->auth->getAccessToken( $_REQUEST['code'] , $callback_url);
        if(!$token || $token['errorCode']) return;
        $access_token = _T($token['access_token']);
        $expires_in = _G('timestamp') + $token['expires_in'];
        $this->openid = $_REQUEST['openid'] && $access_token ? _T($_REQUEST['openid']) : '';

		$newtoken = array(
			'name' 			=> $this->name, 
			'id'			=> $this->openid,
			'access_token' 	=> $access_token, 
			'expires_in' 	=> $expires_in,
		);
		return $this->_createToken($newtoken);
	}

	function getUserInfo($token)
	{
		if(!$this->openid) {
			$this->openid = $token->id;
		}
	    $c = new TXweiboClientV2(
	    	S('member:passport_txweibo_appid'),
	    	S('member:passport_txweibo_appkey'), 
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
	    $userinfo['username'] = $me['nick'];
	    $userinfo['passport_id'] = $me['openid'];
        $userinfo['face'] = $me['head'];
        $userinfo['email'] = $me['email'];

	    return $userinfo;
	}
}