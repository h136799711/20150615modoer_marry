<?php
define('MOD_FLAG', 'space');
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

$space_menus = $_G['loader']->variable('menu_' . $MOD['space_menuid']);

if(!defined('IN_ADMIN')) {

    $acts = array('index','friends','member');
    if(in_array($_GET['act'], array('member','new'))) {
        include MOD_ROOT . $_GET['act'] . '.php';
        output();
    }

    //兼容旧版本URL
    if($_GET['act']=='index' && $_GET['username']) {
        location(U("space/{$_GET['username']}"), true);
    } elseif($_GET['act']=='index' && is_numeric($_GET['uid'])) {
        location(U("space/{$_GET['uid']}"), true);
    }

    if($_GET['act']=='detail' && is_numeric($_GET['id'])) {
        $_GET['act'] = $_GET['id'];
    }

    //个人空间操作组件
    $space = new mc_space;

    //获取UID
    if (is_numeric($_GET['act']) && $_GET['act'] > 0) {
        $uid = _get('act', 0, MF_INT_KEY);
        $space->set_user($uid);
    } elseif (is_string($_GET['act'])) {
        $username = _get('act', 0, MF_TEXT);
        $space->set_user($username, true);
    }

    //判断space是否获取了正确账号
    if ( ! $space->uid || ! $page = $space->routing()) {
        http_404();
    }

    //UC指定个人空间的URL
    if(defined('IN_UC') && S('ucenter:uc_uch') && S('ucenter:uc_uch_url')) {
        header("Location:".S('ucenter:uc_uch_url')."/?".$space->uid);
        exit();
    }

    //指引到全局
    $_G['space'] = $space;

    //加载必要的函数库
    $_G['loader']->helper('function', 'space');

    //加载页面
    include $page;
}

/** end **/