<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* 淘宝auth二次封装
*/
class oauth2_taobao extends ms_oauth2
{
    public $name = 'taobao';
	
	function __construct()
	{
		parent::__construct();
		if(!class_exists('TaobaoOAuth')) {
			require(MUDDER_ROOT.'api'.DS.'taobaooauth.php');
		}
		$this->auth = new TaobaoOAuth(
			S('member:passport_taobao_appkey'),
			S('member:passport_taobao_appsecret')
		);
	}

	function getAuthorizeURL($callback_url)
	{
		return $this->auth->getAuthorizeURL($callback_url);
	}

	function getAccessToken($callback_url)
	{
		$token = $this->auth->getAccessToken( $_REQUEST['code'] , $callback_url);
		if(!$token||!$token->access_token) return;

		$access_token = _T($token->access_token);
		$expires_in = _G('timestamp') + $token->expires_in;

        if(strtoupper(_G('charset') != 'UTF-8')) {
			$this->taobao_user_nick = charset_convert($this->taobao_user_nick,'utf-8',$_G['charset']);
        }

		$newtoken = array(
			'name' 			=> $this->name, 
			'id'			=> $token->taobao_user_id, //唯一ID
			'access_token' 	=> $access_token, 
			'expires_in' 	=> $expires_in,
			'user_nick'		=> $token->taobao_user_nick, //用户昵称
		);
		return $this->_createToken($newtoken);
	}

	function getUserInfo($token)
	{
		$c = new TaobaoClient(
			S('member:passport_taobao_appkey'),
			S('member:passport_taobao_appsecret'), 
            $token->access_token
		);
		$userinfo = array();
        if($me = $c->user_get()) {
            if(strtoupper($_G['charset']) != 'UTF-8') {
				foreach($me as $k => $v) {
                    if(is_string($v)) $me[$k] = charset_convert($v,'utf-8',$_G['charset']);
                }
            }

		    $userinfo['username'] = $me['nick'];
		    $userinfo['passport_id'] = $token->id;

        } else {
        	if($token->id && $token->user_nick) {

			    $userinfo['username'] = $token->user_nick;
			    $userinfo['passport_id'] = $token->id;
        		
        	} else {

        		return;
        		
        	}
        }
	    return $userinfo;
	}
}