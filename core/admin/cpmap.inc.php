<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$cpmenu = new ms_cpmenu();
$content = '<div class="cpmap">';
if($_G['admin']->is_founder) {
    $content .= '<div class="cpmap_box"><h3 class="title">系统框架</h3><ul>';
    foreach ($cpmenu->load('modoer') as $key => $value) {
        $content .= "<li><a href='javascript:void(0);' onclick=\"admincp_click_menu_link('".$cpmenu->get_tab($key)."','".cpmap_get_cpurl($key)."');\">$value</a></li>";
    }
    $content .= '</ul><div class="clear"></div></div>';
    foreach ($_G['modules'] as $module) {
        $content .= '<div class="cpmap_box"><h3 class="title">'.$module['name'].'</h3><ul>';
        foreach ($cpmenu->load($module['flag']) as $key => $value) {
            $content .= "<li><a href='javascript:void(0);' onclick=\"admincp_click_menu_link('".$cpmenu->get_tab($key)."','".cpmap_get_cpurl($key)."');\">$value</a></li>";
        }
        $content .= '</ul><div class="clear"></div></div>';
    }
} else {
    $ckmodules = array();
    foreach($_G['admin']->get_mymodules() as $flag) {
        list($module,) = explode('|', $flag);
        $menus = $cpmenu->load($module);
        $ckmodules[$module][$flag] = $menus[$flag];
    }
    foreach ($ckmodules as $key => $menus) {
        $content .= '<div class="cpmap_box"><h3 class="title">'.($key=='modoer'?'系统框架':$_G['modules'][$key]['name']).'</h3><ul>';
        foreach ($menus as $hash => $value) {
            $content .= "<li><a href='javascript:void(0);' onclick=\"admincp_click_menu_link('".$cpmenu->get_tab($hash)."','".cpmap_get_cpurl($hash)."');\">$value</a></li>";
        }
        $content .= '</ul><div class="clear"></div></div>';
    }
}

$content .= '</div>';
echo $content;
output();

function cpmap_get_cpurl($hash) {
    list($module, $act, $op) = explode('|', $hash);
    return cpurl($module, $act, $op);
}
?>