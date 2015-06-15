<?php
define('MOD_FLAG', 'article');
define('MOD_ROOT', MUDDER_MODULE . MOD_FLAG . DS);

if(!$_CITY) $_CITY = get_default_city();
if(!$_G['mod'] = $_G['loader']->variable('config','article')) {
		if(isset($_G['modules'][MOD_FLAG])) {
				$C =& $_G['loader']->model('config');
				$C->write_cache(MOD_FLAG);
				@include MOD_ROOT . 'inc' . DS . 'cache.php';
				show_error('global_cache_module_succeed');
		}
		if(empty($_G['mod'])) {
				redirect('global_module_not_install');
		}
}
if(!$_G['modules'][MOD_FLAG]) redirect('global_module_disable');
$_G['mod'] = array_merge($_G['mod'], $_G['modules'][MOD_FLAG]);
$MOD =& $_G['mod'];

if(!defined('IN_ADMIN')) {
	$acts = array('index','list','item','ajax','detail','member','rss','mobile');
	if(!in_array($_GET['act'], $acts)) $_GET['act'] = 'index';

	$SEO = new ms_seo('article');

	include MOD_ROOT . $_GET['act'] . '.php';
}
?>