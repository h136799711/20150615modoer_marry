<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* 新浪微博auth二次封装
*/
class oauth2_weibo extends ms_oauth2
{
    public $name='weibo';

	function __construct()
	{
		parent::__construct();
		if(!class_exists('SaeTOAuthV2')) {
			require(MUDDER_ROOT.'api'.DS.'saetv2.ex.class.php');
		}
		$this->auth = new SaeTOAuthV2(
			S('member:passport_weibo_appkey'),
			S('member:passport_weibo_appsecret')
		);
	}


	function getAuthorizeURL($callback_url)
	{
		return $this->auth->getAuthorizeURL($callback_url);
	}

	function getAccessToken($callback_url)
	{
        $keys = array();
        $keys['code'] = $_REQUEST['code'];
        $keys['redirect_uri'] = $callback_url;
        try {
            $token = $this->auth->getAccessToken( 'code', $keys );
			$newtoken = array(
				'name' 			=> $this->name, 
				'id'			=> $token['uid'],
				'access_token' 	=> $token['access_token'], 
				'expires_in' 	=> _G('timestamp') + $token['remind_in'],  //授权剩余时间
			);
			return $this->_createToken($newtoken);

        } catch (OAuthException $e) {
        	return;
        }
	}

	function getUserInfo($token)
	{
        $c = new SaeTClientV2(
        	S('passport_weibo_appkey'),
        	S('passport_weibo_appsecret'),
            $token->access_token
		);
        //$ms  = $c->home_timeline(); // done
        $uid_get = $c->get_uid();
        $uid = $uid_get['uid'];
        $me = $c->show_user_by_id($uid);//userinfo
        if(!$me) return;

	    if(strtoupper(_G('charset')) != 'UTF-8') {
	        foreach($me as $k => $v) {
	            if(is_string($v)) $me[$k] = charset_convert($v,'utf-8',_G('charset'));
	        }
	    }

	    $userinfo = array();
	    $userinfo['username'] = $me['screen_name'];
	    $userinfo['passport_id'] = $me['id'];

	    return $userinfo;
	}

}