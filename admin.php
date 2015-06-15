<?php
define('IN_ADMIN', TRUE);
define('SCRIPTNAV', 'admincp');
define('IN_OKNJUYGF', '20141229');
require dirname(__FILE__).'/core/init.php';
if(!$_G['modoer']['version'] || !$_G['modoer']['build'] || !$_G['modoer']['releaseid']) 
{
	show_error('Version File Error.');
	exit(0);
}
$_G['loader']->helper('admincp');
define('MUDDER_ADMIN', MUDDER_CORE . 'admin' . DS);
$_G['loader']->model('admin',FALSE);
$_G['admin'] = $_G['loader']->model('cpuser');
$admin =& $_G['admin'];
if(_get('logout')) 
{
	$admin->logout();
	redirect('admincp_logout_wait', SELF);
}
if(empty($admin->access)) 
{
	if(!$_POST['loginsubmit']) 
	{
		include MUDDER_ADMIN.'cplogin.inc.php';
		exit;
	}
	else 
	{
		if($admin->login()) 
		{
			redirect('admincp_login_wait', SELF);
		}
		else 
		{
			redirect($admin->error());
		}
	}
}
elseif($admin->access == '1') 
{
	if(!_post('admin_pw') || (md5(_post('admin_pw')) != $admin->password)) 
	{
		include MUDDER_ADMIN.'cplogin.inc.php';
		exit;
	}
	else 
	{
		if($admin->login()) 
		{
			redirect('admincp_login_wait', SELF);
		}
		else 
		{
			redirect($admin->error());
		}
	}
}
elseif($admin->access == '2') 
{
	redirect('admincp_login_op_without', SELF.'?logout=yes');
}
elseif($admin->access == '3') 
{
	redirect('admincp_cpuser_colsed', SELF.'?logout=yes');
}
elseif($admin->access == '4') 
{
	redirect(lang('admincp_cpuser_city_access',$_CITY['name']), SELF.'?logout=yes');
}
if(empty($admin->adminid) || $admin->adminid < 0 || !$admin->isLogin) 
{
	redirect('admincp_not_login', SELF);
}
$module = _input('module');
$act = _input('act');
$in_ajax = _input('in_ajax');
include MUDDER_ADMIN . 'cpframe.inc.php';
