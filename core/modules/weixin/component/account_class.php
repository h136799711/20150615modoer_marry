<?php
/**
* 微信公众平台账号信息
*/
class mc_weixin_account {

	public $account = '';
	public $token = '';
	public $appid = '';
	public $appsecret = '';

	public $access_token = '';

	private static $instant = null;

	public static function instant()
	{
		if(!self::$instant)  {
			self::$instant = new self();
		}
		return self::$instant;
	}

	function __construct()
	{
		$this->account 		= trim(S('weixin:account'));
		$this->token 		= trim(S('weixin:token'));
		$this->appid 		= trim(S('weixin:appid'));
		$this->appsecret	= trim(S('weixin:appsecret'));
	}

	//获取高级接口需要的access_token信息
	public function getAccessToken() 
	{
		$config_obj = _G('loader')->model('config');
		$data = $config_obj->read('access_token', 'weixin');
		if($data) list($access_token, $expires_time) = explode("\t",$data['value']);
		//判断是否失效
		if(! $data || $expires_time > 0 && $dexpires_time < _G('timestamp')) {
			$this->_getAccessToken();
		} elseif($data) {
			$this->access_token = $access_token;
		}
	}

	//从微信服务器获取access_token
	private function _getAccessToken()
	{
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
		$data = http_get($url);
		if($data) $json = json_decode($data, true);
		if($json['access_token']) {
			$this->access_token = $json['access_token'];

			//保存到数据库
			$data = $this->access_token."\t".(_G('timestamp') + $json['expires_in'] - 100);
			$post = array(
				'access_token' => $data,
			);
			$config_obj = _G('loader')->model('config');
			$config_obj->save($post, 'weixin');
		} else {
			log_write('weixin', $url."\n".$data);
		}
	}

}