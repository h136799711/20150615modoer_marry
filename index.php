<?php
/**
* ===========================================
* Project: Modoer(Mudder)
* Version: 3.4
* Time: 2007-7-17 @ Create
* Copyright (c) 2007 - 2012 Moufer Studio
* Website: http://www.modoer.com
* Developer: Moufer
* E-mail: moufer@163.com
* ===========================================
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/

if(!is_file(dirname(__FILE__).'/data/install.lock'))
{
	exit('<a href="install.php">Unsure whether the system of Modoer has been installed or not.</a><br /><br />If it has already been installed,under the folder of ./data , please create a new empty file named as "install.lock".');
}
//application init
require dirname(__FILE__).'/core/init.php';

if($_CFG['index_module'] && $_CITY['aid'] > 0) 
{
	if(($_GET['m']=='index' && $_GET['act']=='index')||(!$_GET['m'] && !$_GET['act']))
	{
		unset($_GET['m'], $_GET['act']);
	}
}
if($_CFG['index_module'] && $_CITY['aid'] > 0 && !isset($_GET['m']) && !isset($_GET['act']))
{
	$m = _get('m', null, '_T');
	if(!$m || !preg_match("/^[a-z]+$/", $m))
	{
		$m = $_CFG['index_module'];
		if(strposex($m, '/')) list($m,$_GET['act']) = explode('/', $m);
	}
	else
	{
		$m = 'index';
	}
}
else
{
	$m = _get('m', 'index', '_T');
}

if($m && $m != 'index')
{
	if($_GET['unkown_sldomain'] && !$_G['in_ajax'] && $_GET['name'] != $_GET['unkown_sldomain'])
	{
		$url = $_GET['Rewrite'] ? ($_CFG['siteurl'] . $_GET['Rewrite']) : ($_GET['m']?url("city:0/$_GET[m]",'',true):$_GET['siteurl']);
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url");
	}

	if(check_module($m))
	{
		$f = $m . DS . 'common.php';
		if(!is_file(MUDDER_MODULE . $f)) show_error(lang('global_file_not_exist', ('./core/modules/' . $f)));
		include MUDDER_MODULE . $f;
	}
	else
	{
		http_404();
	}
}
else
{
	if($_GET['unkown_sldomain'] && !$_G['in_ajax']) http_404();
	if(!$_CITY && (!$_GET['act']||$_GET['act']=='index'))
	{
		//First visit
		if(!$_S_CITY = get_single_city())
		{
			// if($d_city = get_default_city())
			// {
			// 	if($d_city['aid']>0) {
			// 		location(display('modoer:cityurl',"city_id/$d_city[aid]"));
			//		exit;
			// 	}
			// }
			// 手机浏览器首次进入
			if(is_mobile() && check_module('mobile') && S('mobile:auto_enter'))
			{
				header("Location:" . U("mobile/citys", true, 'normal'));
				exit;
			}
			include MUDDER_CORE  . 'modules' . DS . 'modoer' . DS . 'city.php';
			exit;
			//location('index.php?act=city');
		}
		init_city($_S_CITY['aid']);
		$_CITY = $_S_CITY;
		unset($_S_CITY);
	}
	//如果页面当前分站域名或目录，则跳转到正确的域名或目录
	if(S('city_sldomain') && empty($_GET['city_domain']) && !$_GET['act'])
	{
		location(get_city_domain($_CITY['aid']));
	}

	$_GET['m'] = $m = 'index';
	$acts = array('ajax','map','seccode','js','search','announcement','city','upload','tool');
	
	if(isset($_GET['act']) && in_array($_GET['act'], $acts))
	{
		include MUDDER_CORE  . 'modules' . DS . 'modoer' . DS . $_GET['act'] . '.php';
		exit;
	} elseif(!$_GET['act'] || $_GET['act'] == 'index') {
		//登录首页，手机模块跳转
		if(is_mobile() && check_module('mobile') && S('mobile:auto_enter') && _cookie('auto_mobile')!='N')
		{
			header("Location:" . url("mobile/index",'',true,true));
			exit;
		}
		//page name
		define('SCRIPTNAV', 'index');
		//load template
		include template('modoer_index');
	}
	else
	{
		http_404();
	}
}

/** end **/