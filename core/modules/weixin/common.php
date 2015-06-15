<?php
define('MOD_FLAG', 'weixin');
define('MOD_ROOT', MUDDER_MODULE . MOD_FLAG . DS);

if(!$_G['mod'] = $_G['loader']->variable('config', MOD_FLAG)) {
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

if(_input('signature') && _input('timestamp')) {

    $_G['in_ajax'] = true;
    $mc_weixin_obj = new mc_weixin;
    $mc_weixin_obj->response();

} else if(!defined('IN_ADMIN')) {

    //手机浏览运行标记
    $_G['in_mobile'] = TRUE;
    set_cookie('in_mobile', 'Y');
    if($_GET['in_dlg']!='') $_G['in_dlg'] = 'Y';

    //加载手机web函数库
    $_G['loader']->helper('function', 'mobile');
    $_G['forward'] = get_forward(U('member/mobile', true));

    $acts = array('index','activate','bind');
    if(!in_array($_GET['act'], $acts)) $_GET['act'] = 'index';
    include MOD_ROOT . $_GET['act'] . '.php';

}
?>