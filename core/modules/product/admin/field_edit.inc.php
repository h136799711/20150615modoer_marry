<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$F =& $_G['loader']->model(MOD_FLAG.':field');
$op = _input('op');
$F->class_flag = 'product';

switch($op) {
case 'disable':
case 'enable':
    $F->disable((int)$_GET['fieldid'], $op == 'disable' ? 1 : 0);
    redirect('global_op_succeed', cpurl($module,'field_list', '', array('modelid' => (int)$_GET['modelid'])));
    break;
case 'delete':
    $F->delete((int)$_GET['fieldid']);
    redirect('global_op_succeed', cpurl($module,'field_list', '', array('modelid' => (int)$_GET['modelid'])));
    break;
case 'add':
    $subtitle = "添加字段";
    $modelid = _get('modelid', 0, MF_INT_KEY);
    $admin->tplname = cptpl('fielde_save', MOD_FLAG);
    break;

case 'edit':
    $subtitle = "编字段辑";
    $isedit = true;
    $fieldid = (int) $_GET['fieldid'];
    $t_field = $F->read($_GET['fieldid']);
    $disabled = ' disabled="disabled"';
    $modelid = $t_field['id'];

    $admin->tplname = cptpl('fielde_save', MOD_FLAG);
    break;

case 'save':
    if(empty($_POST['t_field']['title'])) {
        cpmsg('未填写字段标题，请返回填写。');
    }
    $_POST['t_field']['tablename'] = 'product_data';
    $_POST['t_field']['config'] = $_POST['t_cfg'];
    if($_POST['isedit']) {
        if(empty($_POST['fieldid'])) cpmsg('对不起，未选择字段，请返回选择。');
        $F->edit($_POST['fieldid'], $_POST['t_field']);
    } else {
        $_POST['t_field']['fieldname'] = 'c_' . $_POST['t_field']['fieldname'];
        $_POST['t_field']['idtype'] = 'product';
        $_POST['t_field']['id'] = (int) $_POST['modelid'];
        if(empty($_POST['t_field']['fieldname'])) cpmsg('未填写字段名称，请返回填写。');
        $F->add($_POST['t_field']);
    }
    redirect('global_op_succeed', cpurl($module, 'field_list', 'list', array('modelid' => $_POST['modelid'])));
    break;
}

?>