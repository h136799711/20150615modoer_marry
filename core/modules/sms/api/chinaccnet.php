<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_sms_chinaccnet extends msm_sms {

    protected $name = '中电云集数据中心';
    protected $descrption = '中电云集数据中心--企业短信平台。官方网站：<a href="http://www.chinaccnet.com/" target="_blank">www.chinaccnet.com</a>';

    private $username = '';
    private $key = '';
    private $customstr = '';
    private $gateurl = 'http://www.chinaccnet.com/api.php';

    private $only_charset = 'gb2312';

    public function __construct($config) {
        parent::__construct($config);
        $this->set_account();
    }

    //发送短信息
    public function send($mobile, $message) {
        $params = array();
        if(!$mobile) redirect('手机号码不能为空。');
        if(!$message) redirect('短信内容不能为空。');
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
            'title' => '用户帐号',
            'des' => '填写帐号名称；注册地址:http://www.chinaccnet.com/duanxin.php',
            'content' => form_input('chinaccnet_uid', $this->config['chinaccnet_uid'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '接口密钥',
            'des' => '如果接口账号密码变了，密钥会做相应改变，需要重新设置。',
            'content' => form_input('chinaccnet_pwd', $this->config['chinaccnet_pwd'], 'txtbox2'),
        );
        return $elements;
    }

    protected function check_from() {
        $post = array();
        $post['chinaccnet_uid'] = _post('chinaccnet_uid',null,MF_TEXT);
        $post['chinaccnet_pwd'] = _post('chinaccnet_pwd',null,MF_TEXT);
        if(!$post['chinaccnet_uid']) redirect('对不起，您未设置用户名。');
        if(!$post['chinaccnet_pwd']) redirect('对不起，未设置密码。');
        return $post;
    }

    //设置短信帐号
    private function set_account() {
        $this->username = $this->config['chinaccnet_uid'];
        $this->key = $this->config['chinaccnet_pwd'];
    }

    //生成短信接口的参数格式
    private function _createParam($mobile, $message) {
        //文字编码转换
        if($this->only_charset!='' && $this->only_charset != strtolower($this->global['charset'])) {
            $message = charset_convert($message, $this->global['charset'], $this->only_charset);
        }
        $params = array (
            'uid'       => $this->username,
            'sign'      => $this->_sign(),
            'mob'       => $mobile,
            'content'   => $message,
        );
        return $params;
    }

    private function _sign() {
        return $this->key;
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
            'state=-01' =>'当前账号余额不足',
            'state=-02' =>'当前用户ID错误',
            'state=-03' =>'当前密码错误',
            'state=-04' =>'短信内容含有非法关键词',
            'state=-05' =>'手机号码格式不对',
            'state=-06' =>'您的IP地址未指定',
            'state=-10' =>'手机号码数量超长',
            'state=-11'   =>'短信内容超长(70个字符)',
            'state=-12' =>'其它错误',
        );
        $value = trim($value);
        if (substr($value,0,9)=='state=000') {
            $this->errorCode = 0;
            $this->errorMsg = '';
            return TRUE;
        } else {
            $this->errorCode = $value;
            $this->errorMsg = '接口提示：' . $errlist[$value];
            return false;
        }
    }

}
?>