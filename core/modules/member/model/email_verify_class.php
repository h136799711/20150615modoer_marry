<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_email_verify extends ms_base {

    protected $uid = 0;
    protected $email = '';
    protected $action_flag = 'mail_auth';

    protected $subject = '';
    protected $message = '';

    //发送账号激活邮件
    function send_activation($user)
    {
        $this->email        = $user['email'];
        $this->uid          = $user['uid'];
        $this->action_flag  = 'member_activation';

        //验证码发送器
        $this->loader->helper('verify','member');
        $verify = verify::factory('email');

        //生成短信息内容
        $verifyurl = str_replace('&amp;', '&', U("member/reg/op/verify_mail/seccode/{$verify->verify_code}", TRUE));
        $search = array('{sitename}','{siteurl}','{username}','{nowtime}','{verifyurl}','{email}');
        $replace = array(
            S('sitename'),
            S('siteurl'), 
            $user['username'],
            date('Y-m-d H:i:s', _G('timestamp')),
            $verifyurl,
            $this->email,
        );
        $this->subject        = str_replace($search, $replace, lang('member_verify_mail_subject'));
        $this->message        = wordwrap(str_replace($search, $replace, lang('member_verify_mail_message')), 75, "\r\n")."\r\n";

        //发送
        return $this->_send($verify);
    }

    //邮箱认证后，更新账号会员组（认证成功后，删除验证码记录）
    function verify($code, $action_flag = '', $alert_delete = TRUE)
    {
        if(!$code)
        {
            $this->add_error('member_verify_param_empty');
            return;
        }

        $model = $this->loader->model('member:verify');
        $data = $model->get_verify_data($code, $action_flag);

        if(!$data) {
            $this->add_error('对不起，验证码不正确。');
            return;
        }
        if($data < 0) {
            $this->add_error('对不起，验证码已过期。');
            return;
        }

        //删除这个操作记录
        if($alert_delete)
        {
            $model->delete($data['id']);
        }

        return $data;
    }

    function _send($verify)
    {
        if(!$this->email)
        {
            $this->add_error('验证邮箱账号为空。');
            return false;
        }

        //配置
        $verify->email          = $this->email;
        $verify->uid            = $this->uid;
        $verify->action_flag    = $this->action_flag;
        $verify->subject        = $this->subject;
        $verify->message        = $this->message;

        //发送邮件
        $result = $verify->send();

        //发送出错
        if($verify->has_error())
        {
            $this->add_error($verify);
            return false;
        }
        else 
        {
            return true;
        }
    }

}

/* end */