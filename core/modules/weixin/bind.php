<?php
!defined('IN_MUDDER') && exit('Access Denied');

//绑定参数验证
$bind_obj = new mc_weixin_bind();
if(!$bind_obj->check_sign()) {
	redirect($bind_obj->error());
}

//检测是否已经绑定
$passport = $bind_obj->passport_obj->get_data('wechat', $bind_obj->openid);
if($passport) {
	redirect('您的微信帐号已绑定，无法二次绑定。');
}

//提交绑定
if(check_submit('dosubmit')) {

	//开始绑定
	if($bind_obj->bind()) {
		redirect('绑定成功！', U('mobile/index'));
	} else {
		if($bind_obj->has_error()) {
			redirect($bind_obj->error());
		} else {
			redirect('绑定失败！');
		}
	}

} else {

	$_HEAD['title'] = $header_title = "绑定微信帐号";
	include mobile_template('weixin_bind');

}