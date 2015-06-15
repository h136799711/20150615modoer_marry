<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* oauth2登录协议封装
*/
class ms_oauth2 {

	public $name = '';

	protected $auth = null;

	public static function autoload($classname) 
	{
		if(substr($classname, 0, 7) != 'oauth2_') return false;
		$filename = strtolower(substr($classname, 7));
		$filepath = MUDDER_CORE.'lib'.DS.'oauth2'.DS.$filename.'.php';
		if(is_file($filepath)) 
		{
			require($filepath);
		}
		return class_exists($classname);
	}

	public static function factory($passport_name) 
	{
		if( ! $passport_name || strpos($passport_name, '.') !== false) return null;
		$filepath = MUDDER_CORE.'lib'.DS.'auth2'.DS.$passport_name.'.php';
		$classname = 'oauth2_'.strtolower($passport_name);
		return new $classname;
	}

	function __construct()
	{
	}

	function getAuthorizeURL($callback_url) 
	{
	}

	function getAccessToken($callbackurl)
	{
		$token = $this->auth->getAccessToken( $_REQUEST['code'] , $callbackurl);
        $access_token = _T($token['access_token']);
        $expires_in = $_G['timestamp'] + $token['expires_in'];
        $passport_id = $this->auth->getOpenid($access_token);
	}

	function refreshAccessToken()
	{
		return false;
	}
	
	function getUserInfo($token = '')
	{
	}

	public function getTokenFromSession()
	{
		$session = _G('session');
		$token_array = array();
		foreach ($session->fetch_all() as $key => $value)
		{
			if(substr($key, 0, 9) == 'passport_')
			{
				$token_array[substr($key,9)] = $value;
			}
		}
		if($token_array['name'] != $this->name) return;
		return self::_createToken($token_array);
	}

	function setTokenSession($token)
	{
		$session = _G('session');
		$token_array = get_object_vars($token);
		foreach ($token_array as $key => $value)
		{
			$keyname = 'passport_'.$key;
			$session->$keyname = $value;
		}
	}

	public function getTokenFromCookie()
	{
		$cookies = _G('cookie');
		$token_array = array();
		foreach ($cookies as $key => $value)
		{
			if(substr($key, 0, 9) == 'passport_')
			{
				$token_array[substr($key,9)] = $value;
			}
		}
		if($token_array['name'] != $this->name) return;
		return self::_createToken($token_array);
	}

	function setTokenCookie($token)
	{
		$token_array = get_object_vars($token);
		foreach ($token_array as $key => $value)
		{
		    set_cookie('passport_'.$key, $value, 3600);
		}
	}

	//必须存在的四个字段
	//name:登录接口名称
	//id:登录接口提供的登录会员的唯一ID
	//access_token:操作授权令牌标识
	//expires_in:操作令牌授权期限
	function _createToken($token_array)
	{
		$token = new stdClass;
		foreach ($token_array as $key => $value)
		{
			$token->$key = $value;
		}
		return $token;
	}

}

spl_autoload_register(array('ms_oauth2', 'autoload'));