<?php
if (!defined('IN_OKNJUYGF') || !defined('IN_EWTRYTU') || !defined('IN_LICCODE')) {
    show_error('File Error.');
    exit(0);
} elseif (cplicstatus()) {
    echo 'liccode error.';
    exit(0);
}
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
lang('admincp_menu_home');
$menu_setting = $_G['lng']['admincp']['admincp_menu_arrry_setting'];
$menu_website = $_G['lng']['admincp']['admincp_menu_arrry_website'];
$tab_arr = array(
    'home' => 'modoer',
    'setting' => 'modoer',
    'website' => 'modoer',
    'module' => 'modoer',
    'help' => 'modoer',
);
$M = $_G['loader']->model('menu');
$parent = $M->read_flag('console_header');
$menus = $M->read_all($parent['menuid'], false, false);
if ($menus) foreach ($menus as $val) {
    $flag = trim($val['url']);
    if (check_module($flag)) $tab_arr[$flag] = $flag;
}
$tab = _T($_GET['tab']);
$tabmenu = empty($tab) ? 'menu_home' : 'menu_' . $tab;
if ($flag = $tab_arr[$tab]) {
    if ($flag == 'modoer') {
        if ($tab == 'home') {
            $$tabmenu = load_home_menu();
        } elseif ($tab == 'module') {
            $$tabmenu = load_modules_menu();
        }
    } else {
        $$tabmenu = load_module_menu($flag, false);
    }
} else {
    show_error('admincp_unkown_menu');
}
$showmenu = '';
if ($tab != 'home') $$tabmenu = filter_check_modules($$tabmenu);
if (count($$tabmenu) > 0) {
    $tabmenus = array_values($$tabmenu);
    foreach ($tabmenus as $menuvalue) {
        $showmenu.= assembly_menu($menuvalue);
    }
} else {
    $showmenu = '<div class="left-menu-folder"><div class="left-menu-heading">' . lang('admincp_cpmenu_title') . '</div><div class="left-menu-item">' . lang('admincp_cpmenu_empty') . '</div></div>';
}
echo $showmenu;
$_smy = 'ad' . 'min_id';
$_fun = 'set_' . 'cookie';
if ($_C[$_smy]) $_fun($_smy, '');
function assembly_menu($menus) {
    static $items = 0;
    if (!$items) $items = 1;
    $showmenu.= "<div class=\"left-menu-folder\">\n";
    foreach ($menus as $key => $menu_str) {
        $m_action = $m_caption = $m_file = $m_op = '';
        if ($key === 'title') {
            $showmenu.= "<div class=\"left-menu-heading\"><h3>$menu_str</h3></div>\n";
        } else {
            if (is_array($menu_str)) {
                $url = $menu_str['url'];
                $title = $menu_str['title'];
                $module_flag = 'modoer';
            } else {
                list($module_flag, $title, $op, $do) = explode('|', $menu_str);
                $url = cpurl($module_flag, $op, $do);
            }
            $showmenu.= "\t<a href=\"$url\" data-module=\"$module_flag\" class=\"left-menu-item\" onfocus=\"this.blur()\">$title</a>\n";
        }
        $items++;
    }
    $showmenu.= "</div>\n";
    return $showmenu;
}
function load_home_menu() {
    global $_G;
    $result = array();
    if (!$_G['admin']->is_founder) {
        $result[] = lang('admincp_menu_quick_links');
        return $result;
    }
    if (!$console_menuid = $_G['cfg']['console_menuid']) {
        $console_menuid = 3;
    }
    $menu = $_G['loader']->variable('menu_' . $console_menuid);
    if (!$menu) return array();
    $result = array(
        'title' => 'Quick Links'
    );
    foreach ($menu as $val) {
        $result[] = array(
            'title' => $val['title'],
            'url' => $val['url']
        );
    }
    return array(
        $result
    );
}
function load_modules_menu() {
    global $_G, $tab_arr;
    $result = array();
    if ($_G['admin']->check_access_module('modoer')) {
        $result[] = $_G['lng']['admincp']['admincp_menu_arrry_module'];
    }
    $c_flags = array(
        'space'
    );
    foreach ($tab_arr as $key => $value) {
        $c_flags[] = $key;
    }
    foreach ($_G['modules'] as $key => $val) {
        if (!$_G['admin']->check_access_module($key)) continue;
        if (in_array($key, $c_flags)) continue;
        if ($r = load_module_menu($key, true)) {
            $tmp = array(
                'title' => $_G['modules'][$key]['name']
            );
            foreach ($r as $menu) {
                $tmp[] = $menu;
            }
            $result[] = $tmp;
        }
    }
    return $result;
}
function load_module_menu($flag, $one_dimensional = FALSE) {
    global $_G;
    if (!check_module($flag)) {
        return;
    }
    $path = 'modules' . DS . $flag;
    $file = $path . DS . 'admin' . DS . 'menus.inc.php';
    if (!is_file(MUDDER_CORE . $file)) {
        return;
    }
    $modmenus = array();
    include MUDDER_CORE . $file;
    if (empty($modmenus) || !is_array($modmenus)) show_error('admincp_module_menu_empty');
    if ($one_dimensional && is_array($modmenus[0])) {
        foreach ($modmenus as $key => $menus) {
            foreach ($menus as $key => $menu_str) {
                if ($key === 'title') {
                    continue;
                }
                $result[] = $menu_str;
            }
        }
        return $result;
    }
    if (!$one_dimensional && !is_array($modmenus[0])) {
        $result = array(
            'title' => $_G['modules'][$flag]['name']
        );
        foreach ($modmenus as $key => $value) {
            $result[] = $value;
        }
        return array(
            $result
        );
    }
    return $modmenus;
}
function filter_check_modules($result) {
    global $_G;
    if ($result && !$_G['admin']->is_founder) foreach ($result as $key => $value) {
        foreach ($value as $_key => $_value) {
            if ("title" === $_key) continue;
            list(, $title,) = explode('|', $_value);
            $hash = str_replace("|$title", '', $_value);
            if (!$_G['admin']->check_access_menu($hash)) {
                unset($value[$_key]);
            }
        }
        if (count($value) == 1 && isset($value['title'])) {
            unset($result[$key]);
        } else {
            $result[$key] = $value;
        }
    }
    return $result;
}
?>