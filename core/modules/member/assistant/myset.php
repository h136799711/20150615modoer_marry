<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op', null, MF_TEXT);
switch($op) {

	case 'save':
		$profile = _post('profile', null, MF_TEXT);
		$MP =& $_G['loader']->model('member:profile');
		$MP->set_uid($user->uid);
		foreach ($profile as $key => $value) {
			$MP->$key = $value;
		}
		$MP->save();
		redirect('global_op_succeed');
		break;

	case 'send_verify_mail':

		if($user->groupid=='4') {
			$verify_model = $_G['loader']->model('member:email_verify');
			if( ! $verify_model->send_activation($user->fetch()))
			{
				redirect($verify_model->error());
			}
			redirect(lang('member_verify_send_mail',$user->email),url('member/index'));
		}
		else
		{
			redirect('member_verify_groupid_invalid');
		}
		break;

	case 'setalipay':
		$token = $_G['loader']->model('member:passport')->get_token($user->uid, 'taobao');
		include_once MUDDER_ROOT . 'api' . DS . 'taobaooauth.php';
		$client = new TaobaoClient($MOD['passport_taobao_appkey'], $MOD['passport_taobao_appsecret'], 
			$token['access_token']);
		if($me = $client->user_get('user_id,nick,alipay_account'))
		{
			if($me['alipay_account'] && $user->alipay != $me['alipay_account'])
			{
				//发送邮件提醒
				send_update_alipay_mail($me['alipay_account']);
				//发送手机短信息
				send_update_alipay_mail_sms($me['alipay_account']);
				//处理
				$_G['loader']->model('member:profile')
					->set_uid($user->uid)
					->save_alipay($me['alipay_account']);
			}
			echo $me['alipay_account'];
		} else {
			echo 'EMPTY';
		}
		exit;
		break;

	case 'changemobile':
		$MV =& $_G['loader']->model('member:mobile_verify')->set_uniq($user->session_id);
		//$MV->delete_time_limit();
		switch(_input('do')) {
		case 'mobile':
			$_G['loader']->helper('validate');
			$mobile = _post('mobile', null, MF_TEXT);
			if(!$mobile) {
				echo lang('member_reg_ajax_mobile_empty'); exit;
			}
			if(!validate::is_mobile($mobile)) {
				echo lang('member_reg_ajax_mobile_invalid'); exit;
			}
			if(_G('loader')->model(':member')->check_mobile_exists($mobile, $user->uid)) {
				echo lang('member_reg_ajax_mobile_exists'); exit;
			}
			echo 'OK';exit;
			break;

		case 'send':
			$mobile = _input('mobile',null,MF_TEXT);
			if($time = $MV->get_resend_time()) {
				echo $time; exit;
			}
			$succeed = $MV->set_mobile($mobile)->send();
			if($succeed) {
				echo 'OK';
			} else {
				echo 'ERROR';
			}
			exit;
			break;

		case 'check':
			$serial = _input('serial', null, MF_TEXT);
			$succeed = $MV->set_serial($serial)->checking();
			$verify = $MV->get_status();
			if($succeed) {
				$user->change_mobile($verify['mobile']);
				$MV->delete();
				echo 'OK';
			} else {
				echo 'ERROR';
			}
			exit;
			break;

		default:
			require_once template('changemobile','member',MOD_FLAG);
		}
		output();
		break;

	case 'changepw':
		$type = _input('type','login',MF_TEXT);
		if(!in_array($type, array('login','pay'))) {
			redirect('无效的密码修改请求。');
		}
		if($_POST['dosubmit']) {
			if($type=='login') {
				$user->change_password($_POST['old'], $_POST['new'], $_POST['new2']);
			} elseif($type=='pay') {
				$user->change_pay_password($_POST['old'], $_POST['new'], $_POST['new2']);
			}
			redirect('global_op_succeed');
		} else {
			if($type=='login') {
				$oldpw=$user->password;
			} elseif($type=='pay'){
				$oldpw=$user->paypw;
			}
			require_once template('changepw','member',MOD_FLAG);
			output();
		}
		break;

	case 'change_password':
		if($error = $user->change_password($_POST['old'], $_POST['new'], $_POST['new2'], 1)) {
			echo '<script type="text/javascript">alert("'.$error.'");</script>';
		} else {
			echo '<script type="text/javascript">alert("'.lang('global_op_succeed').'");window.parent.document.forms["changepasswordfrm"].hide.click();</script>';
		}
		output();
		break;

	case 'forget_paypw':

		if($_POST['dosubmit']) {

			$model_verify = _G('loader')->model('member:verify');
			$data = $model_verify->get_verify_data(_T($_POST['seccode']), 'forget_paypw');

			if( ! $data || $data < 0) redirect('对不起，验证码不正确或已失效。');

			//验证码正确，修改密码
			$user->change_pay_password('', trim($_POST['new']), trim($_POST['new2']), true);

			//删除这个操作记录
			$model_verify->delete($data['id']);

			redirect('global_op_succeed');

		} else {

			if(!$user->paypw) {
				redirect('支付密码为空，请点击[更改]创建支付密码。');
			} else {
				require_once template('forgetpw','member',MOD_FLAG);
				output();
			}
		}

		break;

	case 'change_email':

		if(_input('do') == 'check_email') {
			$exists = _G('loader')->model(':member')->check_email_exists(_T($_POST['email']));
			if(!$exists) {
				echo 'ok';
				output();
			} else {
				redirect(strip_tags(lang('member_reg_ajax_email_exists')));
			}
		}

		if($_POST['dosubmit']) {

			$model_verify = _G('loader')->model('member:verify');

			//如果之前有email帐号，需要进行操作验证）
			if($user->email) {
				//操作验证吗
				$op_data = $model_verify->get_verify_data(_T($_POST['op_seccode']), 'change_email');
				if( ! $op_data || $op_data < 0) redirect('对不起，操作验证码不正确或已失效。');
			}
			//新邮箱验证码
			$new_data = $model_verify->get_verify_data(_T($_POST['new_seccode']), 'check_new_email');
			if( ! $new_data || $new_data < 0) redirect('对不起，新邮箱验证码不正确或已失效。');

			//检测验证码是否是从验证邮箱发出
			$new_email = _post('new_email','',MF_TEXT);
			if($new_data['sender_id'] != $new_email) {
				redirect('对不起，您提供的验证码不是当前设置的邮箱发送。');
			}

			//验证码正确，修改密码
			$result = $user->change_email(_T($_POST['new_email']));
			if(!$result) redirect($_POST['new_email']);

			//删除验证码
			if($op_data) $model_verify->delete($op_data['id']);
			$model_verify->delete($new_data['id']);

			redirect('global_op_succeed');

		} else {

			require_once template('change_email','member',MOD_FLAG);
			output();	

		}

		
		break;

	case 'seccode_send':

		$type   = _post('type', '', MF_TEXT);
		$action = _post('action', '', MF_TEXT);

		if($type == 'mobile') {
			if(!check_module('sms')) redirect('对不起，网站未打开短信发送功能。');
			if(!$user->mobile) redirect('对不起，您没有设置手机号。');
		} elseif($type == 'email') {
			//
		} else {
			redirect('无效的验证码发送方式。');
		}

		if(!$action) redirect('对不起，您未指定验证码发送类型。');

		//加载验证码类
		_G('loader')->helper('verify', 'member');
		$verify = verify::factory($type);
		if(!$verify) redirect('对不起，发送程序错误。');

		//赋值
		$verify->action_flag    = $action;
		$verify->expriy_date    = 1800; //有效期(秒)
		$verify->uid            = $user->uid;
		if($type == 'mobile') {
			$verify->mobile = $user->mobile;
		} else {
			if($email = _post('email','')) {
				_G('loader')->helper('validate');
				if(!validate::is_email($email)) {
					redirect('指定发送的email帐号格式不正确。');
				}
			}
			$verify->email = $email ? $email : $user->email;
			if(!$verify->email) redirect('对不起，您没有设置邮箱号。');
		}

		//发送验证码
		if(!$verify->send()) {
			redirect($verify->has_error()?$verify->error():'验证码发送失败。');
		} else {
			echo 'ok';
			exit;
		}

		break;

	default:

		$PT =& $_G['loader']->model('member:passport');
		$pstoken = $_G['loader']->model('member:passport')->get_token_status($user->uid);

		$smscfg = $modcfg = _G('loader')->variable('config','sms');
		$usdmobile = $smscfg['use_api'];
		
}

function send_update_alipay_mail($alipay) {
	global $user,$_G;

	$subject = _G('cfg','sitename') . ':您的支付宝帐号已更新!';
	$message = "您的帐号 {$user->username} 在 ".date('Y-m-d H:i:s', $_G['timestamp'])." 更新了支付宝帐号为 {$alipay}，如果您没有进行过操作，请及时登录网站联系管理员，以免产生损失。";
	$message = wordwrap($message, 75, "\r\n") . "\r\n";

	$cfg =& _G('cfg');
	if($cfg['mail_use_stmp']) {
		$cfg['mail_stmp_port'] = $cfg['mail_stmp_port'] > 0 ? $cfg['mail_stmp_port'] : 25;
		$auth = ($cfg['mail_stmp_username'] && $cfg['mail_stmp_password']) ? TRUE : FALSE;
		$_G['loader']->lib('mail',null,false);
		$MAIL = new ms_mail($cfg['mail_stmp'], $cfg['mail_stmp_port'], $auth, $cfg['mail_stmp_username'], $cfg['mail_stmp_password']);
		$MAIL->debug = $cfg['mail_debug'];
		$result = @$MAIL->sendmail($user->email, $cfg['mail_stmp_email'], $subject, $message, 'TXT');
		unset($MAIL);
	} else {
		$header = "Content-Type:text/plain; charset="._G('charset')."\r\n";
		$header .= "From: $from<".$from.">\r\n";
		$header .= "Subject: =?".strtoupper(_G('charset')).'?B?'.base64_encode($subject)."?=\r\n";
		$result = @mail($user->email, $subject, $message);
	}
}

function send_update_alipay_mail_sms($alipay) {
	global $user,$_G;

	if(!$user->mobile) return;

	$message = "您的帐号 {$user->username} 在 ".date('m-d H:i', $_G['timestamp'])." 更新了支付宝帐号 {$alipay} 【"._G('cfg','sitename')."】";

	$_G['loader']->model('sms:factory', null);
	$sms = msm_sms_factory::create();
	$sms->send($user->mobile, $message);
}
?>