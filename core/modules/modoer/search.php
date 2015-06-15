<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$module_flag = _GET('module_flag', '', MF_TEXT);
if(!check_module($module_flag)) redirect('global_search_module_empty');

$search_hook = 'modules' . DS . $module_flag . DS . 'inc' . DS . 'search_hook.php';
if(!file_exists(MUDDER_CORE . $search_hook)) redirect(lang('global_file_not_exist', $search_hook));

//中文参数在使用UTF-8格式URL在GBK编码的系统下（同时使用了URL重写）需要进行进行参数转码
if($_G['cfg']['utf8url'] && $_G['charset'] != 'utf-8' && $_G['url']->is_rewrite() && $_GET['keyword']) {
	$_GET['keyword'] = charset_convert($_GET['keyword'], 'utf-8', $_G['charset']);
}

//处理关键字
$_GET['keyword'] = _T(preg_replace('/\s+|\r\n|\n|\r/i', ' ', $_GET['keyword']));

//if(!$_GET['keyword']) redirect('抱歉！没有找到匹配的相关信息，您可以尝试换个关键词搜索一下。');

//加载各自模块的相关搜索业务文件
include MUDDER_CORE . $search_hook;
?>