<?php
define('MOD_FLAG', 'product');
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
$MOD['cash_rate'] = $MOD['cash_rate'] > 0 ? $MOD['cash_rate'] : 10;

if(!defined('IN_ADMIN')) {
    $acts = array('ajax','index','list','member','detail','cart','search','shop','pay_notify','mobile');
    if(!in_array($_GET['act'], $acts)) $_GET['act'] = 'index';

    include MOD_ROOT . $_GET['act'] . '.php';
}
?>