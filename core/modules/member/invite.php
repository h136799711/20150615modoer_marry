<?php
/**
* 注册推广
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');


$uid = _get('uid', 0, MF_INT_KEY);

//已登录
if($user->isLogin) location(url('member/index'));

//功能未开启
if( ! S('member:invite_reg'))
{
	redirect('对不起，管理员未开启邀请注册功能。', url('member/reg'));
}

//放到cookie
set_cookie('inviter_uid', $uid);

//进入注册页面
location(url("member/reg"));

/* end */