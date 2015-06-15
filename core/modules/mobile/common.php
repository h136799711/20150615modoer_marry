<?php
define('MOD_FLAG', 'mobile');
define('MOD_ROOT', MUDDER_MODULE . MOD_FLAG . DS);

if(!$_G['mod'] = $_G['loader']->variable('config',MOD_FLAG)) {
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

if($_GET['in_dlg']!='') {
    $_G['in_dlg'] = 'Y';
}
set_cookie('in_mobile', 'Y');

$_G['loader']->helper('function', MOD_FLAG);
if(!defined('IN_ADMIN')) {
    $acts = array('index','citys');
    if(!in_array($_GET['act'], $acts)) $_GET['act'] = 'index';
    include MOD_ROOT . $_GET['act'] . '.php';
}
?>