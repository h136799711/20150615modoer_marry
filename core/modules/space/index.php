<?php
/**
* @author moufer<moufer@163.com>
* @pageage space
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'space_index');

//更新浏览量
if($user->uid != $space->uid)
{
	$space->space_obj->pageview($uid);
	//会员访问记录
	if ($user->isLogin)
	{
		$visit_obj = $_G['loader']->model("space:visit");
		$visit_obj->add($space, $user);
	}
}

//页面SEO
$_HEAD['title']         = $space->spacename.$_CFG['titlesplit'].$space->spacedescribe;
$_HEAD['description']   = $space->spacename.','.$space->spacedescribe;

//设置模板ID
$templateid = _get('templateid', 0, MF_INT_KEY);
if($templateid) {
	$show_error = true;
} else {
	$templateid = $space->space_styleid;
}

//载入模型的内容页模板
include space_template('index', (int)$templateid, false, $show_error);
?>