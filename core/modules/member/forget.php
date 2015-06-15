<?php
/**
* user forget
* @author moufer<moufer@163.com>
* @package modoer
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'login');

$forget = $_G['loader']->model('member:forget');

$op = _input('op', '', MF_TEXT);
switch ($op) {

    //修改密码
    case 'updatepw':

        if(check_submit('dosubmit')) {

            //提交新密码和忘记密码验证码凭证
            $forget->update_password(
            	_T($_POST['seccode']),
            	trim($_POST['newpassword']),
            	trim($_POST['newpassword2'])
            );
            redirect('member_getpassword_succeed', url('member/login'));

        } else {

            $seccode    = _get('seccode', null, MF_TEXT);

            //验证
            $model_verify = $_G['loader']->model('member:verify');
            $data = $model_verify->get_verify_data($seccode, 'forget_password');

            if(!$data || $data < 0)      redirect('对不起，验证码不正确或已失效。');

            $member = $_G['loader']->model(':member')->read($data['uid']);
            if(!$member) redirect('member_empty');

            require_once template('member_forget');
        }
        break;
	
	default:
		if($user->isLogin) redirect('member_login_logined');
		if(check_submit('dosubmit')) {
            //发送验证邮件
		    $forget->by_email(_T($_POST['username']), _T($_POST['email']));
		    redirect('member_forget_mail_succeed', url('member/login'));
		} else {
		    require_once template('member_forget');
		}
}
?>