<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* 手机短信发送验证码
*/
class verify_mobile extends verify
{

	//public $mobile 	= '';
	//public $message = '';

	protected $sms = null;
	
	function __construct()
	{
		parent::__construct();
		$this->sender = 'mobile';
		$this->_get_sms();
	}

	function __set($key, $value)
	{
		if($key == 'mobile') {
			$this->set_mobile($value);
		} else {
			parent::__set($key, $value);
		}
	}

	//发送验证码
	function send()
	{
        return parent::send();
	}

	//设置手机号
	function set_mobile($mobile)
	{
        $this->loader->helper('validate');
        if(!validate::is_mobile($mobile)) {
            $this->add_error('member_reg_ajax_mobile_invalid');
        }
        $this->mobile = $mobile;
        $this->sender_id = $this->mobile;

        $this->set_item['mobile'] = $this->mobile;
	}

    //发送手机验证码
    protected function _send()
    {
    	if(!$this->sms) {
    		$this->add_error('无法获取短信发送接口。');
    		return false;
    	}
		return $this->sms->send($this->mobile, $this->message);
    }

    //获取一个短信发送类
    protected function _get_sms()
    {
        $this->loader->model('sms:factory',null);
        $this->sms = msm_sms_factory::create();
    }

}