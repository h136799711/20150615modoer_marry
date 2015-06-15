<?php
class TXweiboOAuthV2 {

	private $authorize_url = 'https://open.t.qq.com/cgi-bin/oauth2/authorize';
	private $token_url = 'https://open.t.qq.com/cgi-bin/oauth2/access_token';
	private $appid = '';
	private $appkey = '';
	
	function __construct($appid, $appkey) {
		$this->appid = $appid;
		$this->appkey = $appkey;
	}

	public function getAuthorizeURL($callbackurl) {
		$url = $this->authorize_url . '?'
			. 'response_type=code'
			. '&client_id=' . $this->appid
			. '&redirect_uri=' . urlencode($callbackurl);
		return $url;
	}

	public function getAccessToken($code, $callbackurl) {
		$token_url = $this->token_url . '?' 
			. 'grant_type=authorization_code'
			. '&code='.$code
			. '&client_id='.$this->appid
			. '&client_secret='.$this->appkey
			. '&state=CSRF'
			. '&redirect_uri='.urlencode($callbackurl);
		$response = $this->do_get($token_url);
        $token = array();
        if($response) {
            parse_str($response, $token);
        }
		return $token;
	}

	private function do_post($url, $params) {
		$fields_string = '';
		foreach($params as $key => $value){
			$fields_string .= "{$key}={$value}&";
		}
		$fields_string  = rtrim($fields_string,'&');
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
		$result = curl_exec ($ch);
		curl_close ($ch);  
		return $result;
	}

	private function do_get($url) {
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
	    } elseif (ini_get("allow_url_fopen") == "1") {
	        $result = file_get_contents($url);
	        if($result) return $result;
	         echo "<h3>file_get_contents:failed to open stream!</h3>";
	         echo "<h5>$url</h5>";
	         exit();
	    } else {
	        echo '<h3>PHP function (curl_exec) does not exist.</h3>';
	        echo "<h5>$url</h5>";
	        exit();
	    }
	}
}

class TXweiboClientV2 {

	private $sdk = null;

	private $appid = '';
	private $appkey = '';
	private $token = '';
	private $openid = '';
	
	function __construct($appid, $appkey, $token, $openid='') {
		$this->appid = $appid;
		$this->appkey = $appkey;
		$this->token = $token;
		$this->openid = $openid;
	}

	function get_user_info() {
		$post=array();
		$post['format']='json';
		$post['oauth_consumer_key']=$this->appid;
		$post['access_token']=$this->token;
		$post['openid']=$this->openid;
		$post['clientip']=_G('ip');
		$post['oauth_version']='2.a';
		$post['scope']='all';
	    $get_user_info = "https://open.t.qq.com/api/user/info?"
	    	. "format=json"
	    	. "&oauth_consumer_key=" . $this->appid
	        . "&access_token=" . $this->token
	        . "&openid=" . $this->openid
	        . "&clientip=" . $_SERVER["SERVER_ADDR"]
	        . "&oauth_version=2.a"
	        . "&scope=all";
	    //$info = $this->do_get($get_user_info);
	    $info = $this->do_post('https://open.t.qq.com/api/user/info',$post);

	    $arr = json_decode($info, true);
	    if($arr['ret']=='0') $arr=$arr['data'];
	    return $arr;
	}

	function add_t($content) {
		$url = "https://open.t.qq.com/api/t/add";
		$params['access_token'] = $this->token;
		$params['oauth_consumer_key'] = $this->appid;
		$params['openid'] = $this->openid;
		$params['format'] = 'json';
		$params['content'] = urlencode($content);
		$params['clientip'] = _G('ip');
		$params['oauth_version'] = '2.a';
		$params['scope'] = 'all';
	    $ret = $this->do_post($url, $params);
	    $result = array();
	    $result = json_decode($info, true);
	    if($result['ret'] == '0') {
	    	$arr = $arr['data'];
	    } else {
            //error log
            $result['user'] = $this->global['user']->uid . "\t" . $this->global['user']->username;
            log_write('tx_weibo', $result);
            return false;
        }
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