<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

define('MOD_FLAG', 'group');
define('MOD_ROOT', MUDDER_MODULE . MOD_FLAG . DS);

if(!$_CITY) $_CITY = get_default_city();
if(!$_G['mod'] = $_G['loader']->variable('config', MOD_FLAG)) {
    if(isset($_G['modules'][MOD_FLAG])) {
        $C =& $_G['loader']->model('config');
        $C->write_cache(MOD_FLAG);
        include MOD_ROOT . 'inc' . DS . 'cache.php';
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
    $acts = array('topic','list','member','index','item','ajax','mobile');
    if(!in_array($_GET['act'], $acts)) {
        if(is_numeric($_GET['act']) && $_GET['act'] > 0) {
            $_GET['gid'] = $_GET['act'];
            $_GET['act'] = 'group';
        } else {
            $_GET['act'] = 'index';
        }
    }

    $SEO = new ms_seo('group');

    include MOD_ROOT . $_GET['act'] . '.php';
}
?>