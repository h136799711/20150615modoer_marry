<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_sms_yiwei extends msm_sms {

    protected $name = '一纬信息技术';
    protected $descrption = '一纬信息技术：<a href="http://www.redsms.cn/" target="_blank">www.redsms.cn</a>';

    private $username = '';
    private $key = '';
    private $customstr = '';
    private $gateurl = 'http://www.redsms.cn/api.php';

    public function __construct($config) {
        parent::__construct($config);
        $this->set_account();
    }

    //发送短信息
    public function send($mobile, $message) {
        if(!$mobile) redirect('手机不能为空');
        if(!$message) redirect('发送内容不能为空');
        $params = $this->_createParam($mobile, $message);
        return $this->_send($params);
    }

    protected function create_from() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] =
            array(
            'title' => '用户名',
            'des' => '一纬信息技术',
            'content' => form_input('yiwei_username', $this->config['yiwei_username'], 'txtbox2'),
        );
        $elements[] =
            array(
            'title' => '密码',
            'des' => '一纬信息技术',
            'content' => form_input('yiwei_md5key', $this->config['yiwei_md5key'], 'txtbox2'),
        );
        return $elements;
    }

    protected function check_from() {
        $post = array();
        $post['yiwei_username'] = _post('yiwei_username',null,MF_TEXT);
        $post['yiwei_md5key'] = _post('yiwei_md5key',null,MF_TEXT);
        if(!$post['yiwei_username']) redirect('用户名为空！');
        if(!$post['yiwei_md5key']) redirect('密码为空！');
        return $post;
    }

    //设置短信帐号
    private function set_account() {
        $this->username = $this->config['yiwei_username'];
        $this->key = $this->config['yiwei_md5key'];
        if(strtolower($this->global['charset']) != 'utf-8') {
            $this->loader->lib('chinese', NULL, FALSE);
            $CHS = new ms_chinese($this->global['charset'], 'utf-8');
            $this->username = $CHS->Convert($this->username);
        }
    }

    //生成短信接口的参数格式
    private function _createParam($mobile, $message) {

        if(strtolower($this->global['charset']) != 'utf-8') {
            $this->loader->lib('chinese', NULL, FALSE);
            $CHS = new ms_chinese($this->global['charset'], 'utf-8');
            $message = $CHS->Convert($message);
        }
        $params = array (
            'username'  => $this->username,
            'password'  => $this->key,
            'method'    =>'sendsms',
            'mobile'    => $mobile,
            'msg'       => $message
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
       static $errlist = array(
           '-1'     =>'无返回信息',
            '0'     =>'发送成功',
            '1'     =>'缺少登录信息',
            '2'     =>'登录信息错误',
            '3'     =>'帐号已过期',
            '4'     =>'未知方法',
            '5'     =>'手机号码、信息内容为空',
            '6'     =>'额度已用完',
            '7'     =>'发送条数超出，请等待管理员审核',
            '8'     =>'新密码为空');
       $arrxml=$this->xmltoarr($value);
       if ($arrxml['error']==0) {
            $this->errorCode =$arrxml['error'];
            $this->errorMsg = $errlist[$arrxml['error']];
            return TRUE;
        } else {
            $this->errorCode = $arrxml['error'];
            $this->errorMsg = '接口提示：' . $errlist[$arrxml['error']];
            return false;
        }
    }
    //处理xml数据
     function xmltoarr($getmes) {
         $getmes = stripslashes($getmes);
         if(!$getmes) return array('error'=>'-1');
         $tag = array('error','message','sid');
         $arrxml = array();
         $obj = new DOMDocument('1.0');
         $obj->loadXML($getmes);
         for($i=0;$i<15;$i++){
            $arrxml[$tag[$i]] = $obj->getElementsByTagName($tag[$i])->item(0)->nodeValue;
         }
         return $arrxml;
     }
}
?>