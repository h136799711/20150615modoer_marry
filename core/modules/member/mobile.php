<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

//手机浏览运行标记
$_G['in_mobile'] = TRUE;
set_cookie('in_mobile', 'Y');
if($_GET['in_dlg']!='') $_G['in_dlg'] = 'Y';

//加载手机web函数库
$_G['loader']->helper('function', 'mobile');

// 允许的操作行为
$allowacs = array( 'login', 'reg', 'index', 'myset', 'message', 'notice', 'pm', 'point', 'pay');
// 不需要登录的操作
$guestacs = array( 'login', 'reg');
// 可返回地址
$_G['forward'] = get_forward(U('member/mobile', true));

$do = strtolower(trim(_T($_GET['do'])));
$op = strtolower(trim(_T($_GET['op'])));

$do = empty($do) || !in_array($do, $allowacs) ? 'index' : $do;
if(!$do) redirect('global_op_unkown');

//登录判断
if(!$user->isLogin && !in_array($do, $guestacs)) {
	$forward = get_forward(U('member/mobile', true));
	location(url('member/mobile/do/login'));
}

include MOD_ROOT . 'mobile' . DS . $do . '.php';
?>