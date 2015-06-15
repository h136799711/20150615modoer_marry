<?php

if (!defined('IN_OKNJUYGF') || !defined('IN_EWTRYTU') || !defined('IN_LICCODE')) {
    show_error('File Error.');
    exit(0);
} elseif (cplicstatus()) {
    redirect('admincp_license_empty', cpurl('modoer', 'cphome'));
}
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$op = _input('op', null, MF_TEXT);
switch ($op) {
    case 'edit':
        $cmd_manage_obj = new mc_weixin_cmd_manage();
        $cmds = $cmd_manage_obj->get_installed_cmds();
        $admin->tplname = cptpl('menu_save', MOD_FLAG);
        break;

    case 'get_menus':
        $menu_obj = new mc_weixin_menu();
        $menus = $menu_obj->get_menus();
        if (!$menus) {
            echo $menu_obj->error_json();
            output();
        }
        echo json_encode($menus);
        output();
        break;

    case 'post':
        $post = $_POST['button'];
        $menu_obj = new mc_weixin_menu();
        $result = $menu_obj->create_menus($post);
        if (!$result) {
            echo $menu_obj->error_json();
            output();
        } else {
            echo json_encode(array(
                'code' => 200
            ));
            output();
        }
        break;

    default:
        $admin->tplname = cptpl('menu', MOD_FLAG);
        break;
}
?>
