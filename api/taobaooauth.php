<?php
class TaobaoOAuth {

	private $authorize_url = 'https://oauth.taobao.com/authorize';
	private $token_url = 'https://oauth.taobao.com/token';
	private $appkey = '';
	private $appsecret = '';
	
	function __construct($appkey, $appsecret) {
		$this->appkey = $appkey;
		$this->appsecret = $appsecret;
	}

	public function getAuthorizeURL($callbackurl, $state='CSRF') {
		$url = $this->authorize_url . '?response_type=code&client_id='.
			$this->appkey . '&redirect_uri=' . urlencode($callbackurl) . '&state=' . $state;
		return $url;
	}

	public function getAccessToken($code, $callbackurl, $state='CSRF') {
        if(!$code) {
			echo '<h3>error:',_T($_REQUEST['error']),'</h3>';
            echo '<p>description:',_T($_REQUEST['error_description']),'</p>';
			exit;
        }
		$params = array();
		$params['grant_type'] = 'authorization_code';
		$params['code'] = $code;
		$params['client_id'] = $this->appkey;
		$params['client_secret'] = $this->appsecret;
		$params['state'] = $state;
		$params['redirect_uri'] = urlencode($callbackurl);
		$response = $this->do_post($this->token_url, $params);
        $token = array();
        if($response) {
            $token = json_decode($response);
            if (isset($token->error)) {
                echo "<h3>error:</h3>" . $token->error;
                echo "<h3>msg  :</h3>" . $token->error_description;
                exit;
            }
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

class TaobaoClient {

	private $sdk = null;

	private $appkey = '';
	private $appsecret = '';
	private $token = '';
	
	function __construct($appkey, $appsecret, $token) {
		$this->appkey = $appkey;
		$this->appsecret = $appsecret;
		$this->token = $token;
		$this->sdk = _G('loader')->lib('taobao');
	}

	private function init_key() {
		$this->sdk->set_appkey($this->appkey , $this->appsecret , $this->token );
	}

	public function user_get($fields='user_id,nick,email,avatar') {
		$this->init_key();
        $Data = $this->sdk->set_method('taobao.user.buyer.get')
            ->set_param('fields', $fields)
            ->get_data();
        return $Data['user'];
	}
}

/** end */