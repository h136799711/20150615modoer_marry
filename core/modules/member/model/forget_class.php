<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_forget extends ms_base {

    private $expiry_day = 3; //验证码有效期天数

    private $member       = '';    //忘记用户数据

    function __construct() {
        parent::__construct();
    }

    //通过发送邮件找回密码
    function by_email($username, $email) {

        //验证用户名和邮箱
        $this->loader->helper('validate');
        if(!$username) redirect('member_post_empty_name');
        if(!$email||!validate::is_email($email)) redirect('member_post_empty_email');

        $M = $this->loader->model(':member');

        //查询是否存在这个用户
        $member = $M->read($username, MEMBER_READ_USERNAME);
        if(!$member|| $member['email'] != $email) redirect('member_forget_invalid');

        $this->member = $member;

        //生成验证码，发送邮箱
        $result = $this->_send_mail();
        if(!$result) redirect('global_send_mail_error');
    }

    //通过发送手机验证码
    function by_mobile($username, $mobile) {

    }
    
    //找回密码 - 更新密码操作
    function update_password($seccode, $pw1, $pw2) {

        if(empty($pw1)) redirect('member_post_empty_pw');
        if($pw1 != $pw2) redirect('member_post_empty_eq_pw');

        $model_member = $this->loader->model(':member');

        //检测密码是否规范
        if($error = $model_member->check_password_format($pw1, TRUE)) {
            redirect($error);
        }

        $model_verify = $this->loader->model('member:verify');
        $data = $model_verify->get_verify_data($seccode, 'forget_password');

        if( ! $data || $data < 0)      redirect('对不起，验证码不正确或已失效。');

        $member = $model_member->read($data['uid']);
        if(!$member) redirect('member_empty');

        //更新密码
        $model_member->modify_password($member['uid'], $pw1);

        //删除这个操作记录
        $model_verify->delete($data['id']);
    }

    //发送邮件
    private function _send_mail() {

        $this->loader->helper('verify', 'member');
        $verify = verify::factory('email');

        $expiry_day = $this->expiry_day; //有效期 N 天
        $url = str_replace('&amp;','&',U("member/forget/op/updatepw/seccode/$verify->verify_code", TRUE)); //验证和修改密码地址

        //替换占位标签并生成邮件内容
        $search = array('{sitename}', '{siteurl}', '{username}', '{nowtime}', '{endtime}', '{forgeturl}', '{endday}');
        $replace = array(
            S('sitename'),
            S('siteurl'),
            $this->member['username'],
            date('Y-m-d H:i:s', $this->timestamp),
            date('Y-m-d H:i:s', ($this->timestamp + $this->expiry_day * 24 * 3600)),
            $url,
            $this->expiry_day
        );
        //替换
        $subject = str_replace($search, $replace, lang('member_forget_mail_subject'));
        $message = str_replace($search, $replace, lang('member_forget_mail_message'));
        $message = wordwrap($message, 75, "\r\n") . "\r\n";

        //赋值
        $verify->action_flag    = 'forget_password';
        $verify->expriy_date    = $expiry_day * 24 * 3600; //有效期(秒)
        $verify->email          = $this->member['email'];
        $verify->uid            = $this->member['uid'];
        $verify->subject        = $subject;
        $verify->message        = $message;

        //发送验证码
        $verify->send();
        if($verify->has_error()) {
            redirect($verify->error);
        }

        return true;
    }

}
?>