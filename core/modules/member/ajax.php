<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

// 允许的操作行为
$op = _input('op', '', MF_TEXT);
$in_ajax = $_G['in_ajax'] = 1;

$_G['loader']->helper('validate');
$member_model = $_G['loader']->model(':member');

switch($op) {

	//获取会员基本信息
	case 'userinfo':
		$search = array('"',"\r\n","\r","\n","\n\r");
		$replace = array('\\"',"{LF}","{LF}","{LF}","{LF}");
		if($user->isLogin) {
			//task
			$Tsk =& $_G['loader']->model('member:task');
			$Tsk->mytask(0);

			echo '{ type:"login",username:' . '"' . str_replace($search, $replace, $user->username) . '",
			notice:"' . $_G['loader']->model('member:notice')->get_count().'",
			task:"' . $Tsk->task_done_count().'",
			newmsg:"' . $user->newmsg.'",
			point:"' . $user->point.'",
			group:"' . display('member:group',"groupid/$user->groupid").'" }';
		} elseif($_G['cookie']['activationauth'] && $_G['cookie']['username']) {
			echo '{ type:"activationauth",username:' . '"' . str_replace($search, $replace, $_G['cookie']['username']) . '" }';
		} else {
			echo '';
		}
		output();

		break;

	//会员名是否有效
	case 'check_username':
		if(!$username = _T($_POST['username'])) {
			echo lang('member_reg_ajax_name_empty'); exit;
		}
		if($_G['charset'] != 'utf-8') {
			$username = charset_convert($username, 'utf-8', $_G['charset']);
		}
		$member_model->check_username($username, true);
		if($member_model->check_username_exists($username)) {
			echo lang('member_reg_ajax_name_exists');
			exit;
		}
		echo lang('member_reg_ajax_name_normal'); exit;
		break;

	//检测邮箱是否有效
	case 'check_email':
		if(!$email = _T($_POST['email'])) {
			echo lang('member_reg_ajax_email_empty'); exit;
		}
		if(!validate::is_email($email)) {
			echo lang('member_reg_ajax_email_invalid'); exit;
		}
		if(!$MOD['existsemailreg'] && $member_model->check_email_exists($email)) {
			echo lang('member_reg_ajax_email_exists');exit;
		}
		echo lang('member_reg_ajax_email_normal'); exit;
		break;

	//检测手机号是否有效
	case 'check_mobile':
		$mobile = _post('mobile', null, MF_TEXT);
		if(!$mobile) {
			echo lang('member_reg_ajax_mobile_empty'); exit;
		}
		if(!validate::is_mobile($mobile)) {
			echo lang('member_reg_ajax_mobile_invalid'); exit;
		}
		if($member_model->check_mobile_exists($mobile)) {
			echo lang('member_reg_ajax_mobile_exists'); exit;
		}
		if($verify = $_G['loader']->model('member:mobile_verify')->set_uniq($user->session_id)->get_status()) {
			if($verify['status'] && $verify['mobile']==$mobile) {
				echo 'SUCCEED';exit;
			}
		}
		echo 'OK';exit;

	//发送手机验证码
    case 'send_verify':
        $mobile = _input('mobile', null, MF_TEXT);
        $MV = $_G['loader']->model('member:mobile_verify')->set_uniq($user->session_id);
        if($time = $MV->get_resend_time()) {
            echo $time; exit;
        }
        $succeed = $_G['loader']->model('member:mobile_verify')->set_uniq($user->session_id)->set_mobile($mobile)->send();
        if($succeed) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
        exit;

    //检测手机验证码是否有效
    case 'check_mobile_verify':
        $serial = _input('serial', null, MF_TEXT);
        $succeed = $_G['loader']->model('member:mobile_verify')->set_uniq($user->session_id)->set_serial($serial)->checking();
        if($succeed) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
        exit;

	default:

		redirect('global_op_unknown');

}
?>