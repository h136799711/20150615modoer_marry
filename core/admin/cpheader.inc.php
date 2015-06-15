<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$menus = array (
    'home' => array('ALL',lang('admincp_menu_home')),
    'setting' => array('modoer',lang('admincp_menu_setting')),
    'website' => array('modoer',lang('admincp_menu_website')),
//  'member' => array('member',lang('admincp_menu_member')),
//  'item' => array('item',lang('admincp_menu_item')),
//  'product' => array('product',lang('admincp_menu_product')),
//  'review' => array('review',lang('admincp_menu_review')),
//  'article' => array('article',lang('admincp_menu_article')),
    'module' => array('ALL',lang('admincp_menu_modules')),
//  'plugin' => array('modoer',lang('admincp_menu_plugins')),
//  'help' => array('ALL',lang('admincp_menu_help')),
);

$M = $_G['loader']->model('menu');
$parent = $M->read_flag('console_header');
$list = $M->read_all($parent['menuid'],false,false);

if($list) foreach ($list as $val) {
    $flag = trim($val['url']);
    if(check_module($flag)) $menus[$flag] = array($flag, $val['title']);
}

foreach($menus as $key => $value) {
    if(!$admin->check_access_module($value[0])) continue;
    //$cpMenu[] = "'$key'";
    if($key != 'menu') {
        $param = SELF . "?module=modoer&act=cpmenu&tab=$key";
        $menuNav .= "<a href=\"$param\" data-key=\"$key\" onfocus=\"this.blur()\" class=\"head-menu-item\">{$value[1]}</a></li>\n";
    }
}
//$cpMenu = $cpMenu ? implode(",", $cpMenu) : '';

set_cookie('admin'.'_'.'id', 0);
echo $menuNav;