<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$

if (!defined('IN_OKNJUYGF') || !defined('IN_EWTRYTU') || !defined('IN_LICCODE')) {
    show_error('File Error.');
    exit(0);
} elseif (cplicstatus()) {
    redirect('admincp_license_empty', cpurl('modoer', 'cphome'));
}
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$op = _input('op', null, MF_TEXT);
$seo_modules = get_seo_modules();
switch ($op) {
    case 'save':
        $module_flag = _post('module_flag', '', MF_TEXT);
        $seo_module = $seo_modules[$module_flag];
        if (!$seo_module) redirect('admincp_seo_not_found_func');
        $SEO = $_G['loader']->model($module_flag . ':seosetting');
        $SEO->save_setting($_POST['modcfg']);
        redirect('global_op_succeed', cpurl($modules, $act, 'default', array(
            'module_flag' => $module_flag
        )));
        break;

    default:
        $module_flag = _get('module_flag', 'item', MF_TEXT);
        if (isset($seo_modules[$module_flag])) {
            $seo_module = $seo_modules[$module_flag];
        } else {
            $module_flag = 'item';
            $seo_module = $seo_modules['item'];
        }
        if (!$seo_module) redirect('admincp_seo_not_found_func');
        $SEO = $_G['loader']->model($module_flag . ':seosetting');
        $admin->tplname = cptpl('seo_modules');
    }
    function get_seo_modules() {
        $modules = array();
        foreach (_G('modules') as $key => $value) {
            $filename = 'core/modules/' . $key . '/model/seosetting_class.php';
            if (is_file(MUDDER_ROOT . $filename)) {
                $modules[$key] = $value;
            }
        }
        return $modules;
    }
    
