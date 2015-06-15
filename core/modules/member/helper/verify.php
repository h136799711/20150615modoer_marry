<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class verify extends ms_base {

    public $hash       = '';
    public $verify_code= '';
    public $sender     = '';
    public $extra_item = array();       //行为附带自定义内容

    public $expriy_date = 180;          //验证码有效期
    public $action_flag = 'default';    //发送行为标识
    public $uniq        = '';           //临时会话ID
    public $uid         = 0;            //发送行为创建者UID

    public $message     = '';           //发送内容

    public $sender_id  = '';            //发送媒介账号（邮箱号，手机号等由子类复制）
    public $send_interval = 600;        //2次发送时间间隔(秒)

    function __construct() {
        parent::__construct();
        $this->verify_code = $this->_create_verify_code();
    }

    function __set($key, $value) {
        if($key == 'message') {
            $this->set_message($value);
        } else {
            $this->key = $value;
        }
    }

    static function factory($type) {
        if(!$type) return;
        $classname = 'verify_' . strtolower($type);
        if(!class_exists($classname)) {
            $file = MUDDER_CORE . 'modules' . DS . 'member' . DS . 'helper' . DS . 'verify' . DS . $type . '.php';
            if(!is_file($file)) return;
            require($file);
            if(!class_exists($classname)) return;
        }
        return new $classname;
    }

    //执行发送操作
    function send() {
        if($this->has_error()) return false;

        if(empty($this->message)) {
            $this->set_message(lang('member_verify_code_message'));
        }

        $this->hash = $this->_create_hash($this->sender_id);

        //检测发送间隔
        if($this->check_send_interval($this->hash)) {
            //发送
            $succeed = $this->_send();
            if($succeed) {
                return $this->_successfully();
            } else {
                $this->add_error('邮件发送失败！');
                return false;
            }
        } else {
            $this->add_error('2次发送时间间隔不能少于 '. $this->send_interval .' 秒');
            return false;
        }
    }

    //设置发送内容
    function set_message($message) {
        $this->message = str_replace(
            array('{verify_code}', '{sitename}'),
            array($this->verify_code, S('sitename')),
            $message
        );
        $this->set_item['message'] = $this->message;
    }

    //设置附加内容
    public function set_item($key, $value) {
        $this->extra_item[$key] = $value;
    }

    //判断发送间隔
    function check_send_interval() {
        $interval = $this->loader->model('member:verify')->get_send_interval($this->hash);
        if(is_numeric($interval) &&  $interval > $this->send_interval) {
            return false;
        }
        return true;
    }

    //发送短信息逻辑代码，由子类实现
    protected function _send() {}

    //验证码发送成功后，后续操作
    protected function _successfully() {
        $id = $this->loader->model('member:verify')->create($this);
        return $id;
    }

    //生成序列
    protected function _create_hash($sender) {
        return md5($sender . $this->action_flag . $this->uid);
    }

    //生成验证码
    protected function _create_verify_code() {
        return mt_rand(100000,999999);
    }

}
?>