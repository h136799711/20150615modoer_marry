<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$membercfg = $_G['loader']->variable('config','member');
if($membercfg['closereg']) redirect('member_reg_closed');

$auth = trim($_G['cookie']['activationauth']);
list($uid,$username) = explode("\t", authcode_ex($auth,'D'));

if(!$uid || !$username || ($username != $_G['cookie']['username'])) redirect('ucenter_activation_invalid');

//$result = uc_user_login($username , $password);
if(!$member = uc_get_user($username)) redirect('ucenter_activation_error');
if($member[0] != $uid) redirect('ucenter_activation_uid_invalid');

$post = array();
$post['uid'] 		= $member[0];
$post['username'] 	= $member[1];
$post['password'] 	= random(8);
$post['email'] 		= $member[2];

$reg_model = $_G['loader']->model('ucenter:register');
$uid = $reg_model->uc_add($post, true);

//新建账号失败
if(!$uid) {
	if($reg_model->has_error()) {
		redirect($reg_model->error());
	}
	redirect('账号激活失败！');
}

del_cookie(array('username','activationauth'));

redirect('ucenter_activation_succeed', url('member/index'));
?>