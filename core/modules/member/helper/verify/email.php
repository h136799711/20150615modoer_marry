<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

/**
* 邮件发送验证码
*/
class verify_email extends verify
{

	//public $email 	= '';
	//public $subject	= '';
	//public $message = '';
	
	function __construct()
	{
		parent::__construct();
		$this->sender = 'email';
	}

	function __set($key, $value)
    {
		if($key == 'email') {
			$this->set_email($value);
		} elseif($key == 'subject') {
			$this->set_subject($value);
		} else {
			parent::__set($key, $value);
		}
	}

	//发送验证码
	function send()
    {
        if(empty($this->subject)) {
            $this->set_subject(lang('member_verify_code_subject'));
        }
        return parent::send();
	}

	//设置邮箱号
	function set_email($email)
    {
        $this->loader->helper('validate');
        if(!validate::is_email($email)) {
            $this->add_error('member_post_empty_mail');
        }

        $this->email = $email;
        $this->sender_id = $this->email;

        $this->set_item['email'] = $this->email;
	}

	//设置邮件标题
	function set_subject($subject)
    {
        $this->subject = str_replace(
            array('{verify_code}', '{sitename}'),
            array($this->verify_code, S('sitename')),
            $subject
        );
        $this->set_item['subject'] = $this->subject;
	}

    //发送手机验证码
    protected function _send() 
    {
        if(S('mail_use_stmp')) {
            //stmp配置
            $port = S('mail_stmp_port') > 0 ? S('mail_stmp_port') : 25;
            $auth = S('mail_stmp_username') && S('mail_stmp_password') ? TRUE : FALSE;
            //stmp类
            $MAIL = new ms_mail(S('mail_stmp'), $port, $auth, S('mail_stmp_username'), S('mail_stmp_password'));
            $MAIL->debug = S('mail_debug'); //在页面上显示DEBUG发送信息
            $result = $MAIL->sendmail($this->email, S('mail_stmp_email'), $this->subject, $this->message, 'TXT');
        } else {
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/plain;charset=" . _G('charset') . "\r\n";
            $result = @mail($this->email, $this->subject, $this->message, $headers);
        }
        return $result;
    }

}