<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_sms_smsgetpaid extends msm_sms {

    protected $name = 'SMS Get Paid 簡訊平台';
    protected $descrption = 'SMS Get Paid，中國、香港及台灣，兩岸三地皆可送達。
        访问：<a href="http://www.smsgetpaid.com/" target="_blank">www.smsgetpaid.com</a>；
        <b>仅适用于UTF-8版本</b>';

    private $username = '';
    private $key = '';
    private $customstr = '';
    private $gateurl = 'http://business.smsgetpaid.com/api_send.php';

    public function __construct($config) {
        parent::__construct($config);
        $this->set_account();
    }

	//发送短信息
    public function send($mobile, $message) {
		if(!$mobile) redirect('手机号码不能为空。');
		if(!$message) redirect('短信内容不能为空。');
		$params = $this->_createParam($mobile, $message);
		return $this->_send($params);
	}

    protected function create_from() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '帐号',
            'des' => '填写帐号名称',
            'content' => form_input('smsgetpaid_username', $this->config['smsgetpaid_username'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '密码',
            'des' => '填写帐号密码',
            'content' => form_input('smsgetpaid_password', $this->config['smsgetpaid_password'], 'txtbox2'),
        );
        return $elements;
    }

    protected function check_from() {
        if(_G('charset')!='utf-8') redirect('对不起，本接口只能用于UTF-8编码。'); 
        $post = array();
        $post['smsgetpaid_username'] = _post('smsgetpaid_username',null,MF_TEXT);
        $post['smsgetpaid_password'] = _post('smsgetpaid_password',null,MF_TEXT);
		if(!$post['smsgetpaid_username']) redirect('对不起，您未设置帐号。');
		if(!$post['smsgetpaid_password']) redirect('对不起，未设置密码。');
        return $post;
    }

	//设置短信帐号
	private function set_account() {
        $this->username = $this->config['smsgetpaid_username'];
        $this->key = $this->config['smsgetpaid_password'];
	}

	//生成短信接口的参数格式
    private function _createParam($mobile, $message) {
        $params = array (
            'username'		=> $this->username,
            'password'		=> $this->key,
            'method'        => 1, //1.立即發送,2.預約發送
            'phone'	        => $mobile,
            'sms_msg'	    => $message,
        );
        return $params;
    }

	//通过http协议的post短信息，并返回api的反馈信息(写入log文件，以便调试)
	private function _send($data) {
		if($data) foreach($data as $k => $v) {
			$encoded .= ($encoded ? "&" : "");
			$encoded .= rawurlencode($k)."=".rawurlencode($v);
		}
		$url = $this->gateurl . '?' . $encoded;
		if(function_exists('file_get_contents')){
			$results = file_get_contents($url);
		} else {
			$ch = curl_init();
			$timeout = 5;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$results = curl_exec($ch);
			curl_close($ch);
		}
        $this->errorUrl = $url;
		if($this->_return($results)) {
			return true;
		} else {
			$this->writeLog($this->errorCode, $this->errorMsg); //记录发送不成功的返回信息
			return false;
		}
	}

	//判断返回的短信息是否发送成功
    private function _return($value=null) {
    	static $errlist = array(
            '000'=>'成功',
            '001'=>'參數錯誤',
            '002'=>'預約時間參數錯誤',
            '003'=>'預約時間過期',
            '004'=>'訊息長度過長',
            '005'=>'帳號密碼錯誤',
            '006'=>'IP無法存取',
            '007'=>'收件者人數為0',
            '008'=>'收件人超過250人',
            '009'=>'點數(餘額)不足',
    	);
		$ret = json_decode(trim($value));
		if ($ret->error_code=='000') {
            $this->errorCode = 0;
            $this->errorMsg = '';
            return TRUE;
        } else {
            $this->errorCode = $ret->error_code;
            $this->errorMsg = '接口提示：'.$ret->error_msg;
            return false;
        }
    }

}
?>