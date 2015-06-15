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
}
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$task_model = $_G['loader']->model('plan_task');
$op = _input('op');
switch ($op) {
    case 'edit':
        $id = _input('id', null, MF_FILENAME);
        if (check_submit('dosubmit')) {
            $time_exp_str = "weekday={$_POST['weekday']}|day={$_POST['day']}|hour={$_POST['hour']}|minute={$_POST['minute']}";
            $time_exp = $task_model->parse_time_exp($time_exp_str);
            if (!$time_exp) redirect($task_model->error());
            $post = array();
            $post['time_exp'] = $task_model->to_time_exp_str($time_exp);
            $post['nexttime'] = $task_model->get_nexttime($time_exp);
            $task_model->save($post, $id);
            redirect('global_op_succeed', cpurl($module, $act));
        } else {
            $detail = $task_model->read($id);
            if (!$detail) {
                echo lang('admincp_plan_task_not_installed');
                output();
            }
            $time_exp = $task_model->parse_time_exp($detail['time_exp']);
            $task_obj = $task_model->factory($detail['filename']);
            if (!$task_obj) {
                echo $task_model->error();
                output();
            }
        }
        $admin->tplname = cptpl('plan_task_edit');
        break;

    case 'run':
        $filename = _get('filename', null, MF_FILENAME);
        if ($task_model->run($filename)) {
            redirect('admincp_plan_task_run_succeed', cpurl($module, $act));
        } else {
            redirect($task_model->error());
        }
    case 'install':
        $filename = _get('filename', null, MF_FILENAME);
        if ($task_model->install($filename)) {
            location(cpurl($module, $act));
        }
        redirect($task_model->error());
        break;

    case 'uninstall':
        $filename = _get('filename', null, MF_FILENAME);
        if ($task_model->uninstall($filename)) {
            location(cpurl($module, $act));
        }
        break;

    case 'files':
        $files = $task_model->load_files();
        if ($files) {
            $installed = $task_model->get_list();
        }
        $admin->tplname = cptpl('plan_task_list');
        break;

    default:
        $list = $task_model->get_list();
        $tasks = array();
        if ($list) foreach ($list as $k => $data) {
            $data['task'] = $task_model->factory($data['filename']);
            $tasks[] = $data;
        }
        $admin->tplname = cptpl('plan_task_list');
        break;
    }
    
