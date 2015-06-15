<?php
class JD_OAuthV2 {

	private $authorize_url = 'https://auth.360buy.com/oauth/authorize';
	private $token_url = 'https://auth.360buy.com/oauth/token';
	private $appid = '';
	private $appkey = '';
	
	function __construct($appid, $appkey)
	{
		$this->appid = $appid;
		$this->appkey = $appkey;
	}

	public function getAuthorizeURL($callbackurl, $state='MODOER', $scope='read')
	{
		$view = _G('in_mobile') ? 'wap' : '';
		$url = $this->authorize_url . '?'
			. 'response_type=code'
			. '&client_id=' . $this->appid
			. '&redirect_uri=' . urlencode($callbackurl)
			. '&state=' . $state
			. '&scope=' . $scope
			. '&view=' . $view;
		return $url;
	}

	public function getAccessToken($code, $callbackurl)
	{
		$token = array();
		$token_url = $this->token_url . '?' 
			. 'grant_type=authorization_code'
			. '&code='.$code
			. '&client_id='.$this->appid
			. '&client_secret='.$this->appkey
			. '&state=MODOER'
			. '&redirect_uri='.urlencode($callbackurl);

		$response = $this->do_get($token_url);
		if($response) {
			$token = json_decode($response);
			if($token) {
				if(!$token->code != '') {
	                echo "<h3>error:</h3>" . $token->error;
	                exit;
				}
			}
		}
		return $token;
	}

	private function do_post($url, $params)
	{
		$fields_string = '';
		foreach($params as $key => $value){
			$fields_string .= "{$key}={$value}&";
		}
		$fields_string  = rtrim($fields_string,'&');
		//$xurl = $url . '?' . $fields_string;
		if(!function_exists('curl_exec')) {
			echo '<h3>PHP function (curl_exec) does not exist.</h3>'; exit;
		}
		$ch = curl_init ($url."?");
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);     
		$result = curl_exec ($ch);
		curl_close ($ch);  
		return $result;
	}

	private function do_get($url)
	{
	    if(function_exists('curl_exec')) {
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_URL, $url);
	        $result =  curl_exec($ch);
	        $curl_error = curl_error($ch);
	        curl_close($ch);
	        if($curl_error) {
	            echo "<h3>$curl_error</h3>";
	            echo "<h5>$url</h5>";
	            exit();
	        }
	        return $result;
	    } else {
	        echo '<h3>PHP function (curl_exec) does not exist.</h3>';
	        echo "<h5>$url</h5>";
	        exit();
	    }
	}
}

class QQClientV2 {

	private $sdk = null;

	private $appid = '';
	private $appkey = '';
	private $token = '';
	private $openid = '';
	
	function __construct($appid, $appkey, $token, $openid='') {
		$this->appid = $appid;
		$this->appkey = $appkey;
		$this->token = $token;
		if($openid == '') {
			$o = QQOAuthV2($appid, $appkey);
			$this->openid = $o->getOpenid($token);
		} else {
			$this->openid = $openid;
		}
	}

	function get_user_info() {
	    $get_user_info = "https://graph.qq.com/user/get_user_info?"
	        . "access_token=" . $this->token
	        . "&oauth_consumer_key=" . $this->appid
	        . "&openid=" . $this->openid
	        . "&format=json";
	    $info = $this->do_get($get_user_info);
	    $arr = json_decode($info, true);
	    return $arr;
	}

	function add_share($title, $url, $comment='', $summary='', $images='') {
	    $add_share = "https://graph.qq.com/share/add_share?"
	        . "access_token=" . $this->token
	        . "&oauth_consumer_key=" . $this->appid
	        . "&openid=" . $this->openid
	        . "&format=json"
	        . "&title=" . urlencode($title)
	        . "&url=" . urlencode($url);
	    if($comment) $add_share .= "&comment=" . urlencode($comment);
	    if($summary) $add_share .= "&summary=" . urlencode($summary);
	    if($images) $add_share .= "&images=" . urlencode($images);
	    $ret = $this->do_get($url);
	    $arr = json_decode($ret, true);
	    return $arr;
	}

	function add_t($content) {
		$url = "https://graph.qq.com/t/add_t";
		$params['access_token'] = $this->token;
		$params['oauth_consumer_key'] = $this->appid;
		$params['openid'] = $this->openid;
		$params['format'] = 'json';
		$params['content'] = urlencode($content);
	    $ret = $this->do_post($url,$params);
	    $arr = json_decode($ret, true);
	    return $arr;
	}

	private function do_post($url, $params) {
		$fields_string = '';
		foreach($params as $key=>$value){
			$fields_string .="{$key}={$value}&";
		}
		rtrim($fields_string,'&');
		$xurl = $url . '?' . $fields_string;
		if(!function_exists('curl_exec')) {
			echo '<h3>PHP function (curl_exec) does not exist.</h3>'; exit;
		}
		$ch = curl_init ($url."?");
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);     
		$result = curl_exec($ch);
		curl_close ($ch);  
		return $result;
	}

	private function do_get($url) {
        $url = str_replace('&amp;', '&', $url);
	    if(function_exists('curl_exec')) {
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_URL, $url);
	        $result =  curl_exec($ch);
	        curl_close($ch);
	        return $result;
	    } elseif (ini_get("allow_url_fopen") == "1") {
	        $result = file_get_contents($url);
	        if($result) return $result;
	         echo "<h3>file_get_contents:failed to open stream!</h3>";
	         exit();
	    } else {
	        echo '<h3>PHP function (curl_exec) does not exist.</h3>';
	        exit();
	    }
	}
}