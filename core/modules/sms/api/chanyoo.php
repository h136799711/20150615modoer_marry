<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_sms_chanyoo extends msm_sms {

    protected $name = '畅友网络';
    protected $descrption = '畅友网络：<a href="http://www.chanyoo.cn/" target="_blank">chanyoo.cn</a>';

    private $username = '';
    private $key = '';
    private $customstr = '';
    private $gateurl = 'http://www.redsms.cn/api.php';

    public function __construct($config) {
        parent::__construct($config);
        $this->set_account();
        $this->set_account();
        if(strtolower($this->global['charset']) == 'utf-8') {
        	$this->gateurl = 'http://api.chanyoo.cn/utf8/interface/send_sms.aspx';
        } else {
        	$this->gateurl = 'http://api.chanyoo.cn/gbk/interface/send_sms.aspx';
        }
    }

    //发送短信息
    public function send($mobile, $message) {
        if(!$mobile) redirect('手机不能为空');
        if(!$message) redirect('发送内容不能为空');
        $maxlen = 66;
        $len = strlen_ex($message);
        if($len > $maxlen) {
            $cil = ceil($len / $maxlen);
            $messages = array();
            for ($i=0; $i < $cil; $i++) {
                $x = $i * $maxlen;
                $content = csubstr($message, $x, $maxlen);
                $params = $this->_createParam($mobile, $content[0]);
                $result = $this->_send($params);
                if(!$result) return $result;
            }
            return $result;
        } else {
            $params = $this->_createParam($mobile, $message);
            return $this->_send($params);
        }
    }

    protected function create_from() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] =
            array(
            'title' => '用户名',
            'des' => '短信发送帐号名称。',
            'content' => form_input('chanyoo_username', $this->config['chanyoo_username'], 'txtbox2'),
        );
        $elements[] =
            array(
            'title' => '密码',
            'des' => '短信发送帐号密码。',
            'content' => form_input('chanyoo_password', $this->config['chanyoo_password'], 'txtbox2'),
        );
        return $elements;
    }

    protected function check_from() {
        $post = array();
        $post['chanyoo_username'] = _post('chanyoo_username',null,MF_TEXT);
        $post['chanyoo_password'] = _post('chanyoo_password',null,MF_TEXT);
        if(!$post['chanyoo_username']) redirect('用户名为空！');
        if(!$post['chanyoo_password']) redirect('密码为空！');
        return $post;
    }

    //设置短信帐号
    private function set_account() {
        $this->username = $this->config['chanyoo_username'];
        $this->key = $this->config['chanyoo_password'];
    }

    //生成短信接口的参数格式
    private function _createParam($mobile, $message) {
        $params = array (
            'username'  => $this->username,
            'password'  => $this->key,
            'receiver'  => $mobile,
            'content'   => $message
        );
        return $params;
    }

    //通过http协议的post短信息，并返回api的反馈信息(写入log文件，以便调试)
    private function _send($data) {
        $fields_string = '';
        foreach($data as $key => $value){
            $fields_string .= "{$key}={$value}&";
        }
        $fields_string = rtrim($fields_string,'&');

        $ch = curl_init ($this->gateurl . "?");
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, false);     
        $results = curl_exec ($ch);
        curl_close ($ch);

        $this->errorUrl = $this->gateurl . '?' . rawurlencode($fields_string);
        if($this->_return($results)) {
            return true;
        } else {
            $this->writeLog($this->errorCode, $this->errorMsg); //记录发送不成功的返回信息
            return false;
        }
    }

    //判断返回的短信息是否发送成功//一纬返回xml数据
    private function _return($value=null) {
        $xml = simplexml_load_string($value);
        if ($xml->result >= 0) {
            return TRUE;
        } else {
            $this->errorCode = $xml->result;
            $this->errorMsg = $xml->message;
            return FALSE;
        }
    }
    
}
?>