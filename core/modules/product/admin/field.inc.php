<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op');
$F = $_G['loader']->model(MOD_FLAG.':field');
$C = $_G['loader']->model(MOD_FLAG.':gcategory');

switch ($op) {

    case 'add':
        $subtitle = "添加字段";
        $catid = _get('catid', 0, MF_INT_KEY);
        $category = $C->read($catid);
        if(!$category || $category['level']!='3') redirect('对不起，您选择的分类无效或不是第三级分类');

        $admin->tplname = cptpl('fielde_save', MOD_FLAG);
        break;

    case 'edit':
        $subtitle = "编字段辑";
        $isedit = true;
        $fieldid = (int) $_GET['fieldid'];
        $t_field = $F->read($_GET['fieldid']);
        $disabled = ' disabled="disabled"';
        $catid = $t_field['id'];

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
            $_POST['t_field']['id'] = (int) $_POST['catid'];
            if(empty($_POST['t_field']['fieldname'])) cpmsg('未填写字段名称，请返回填写。');
            //$F->add($_POST['t_field']);
        }
        //redirect('global_op_succeed', cpurl($module, $act, 'list', array('catid' => $_POST['catid'])));
        break;

    case 'update':
        $catid = (int)_input('catid');
        $F->listorder($_POST['neworder'],$catid);
        redirect('global_op_succeed', cpurl($module, $act, 'list', array('catid'=>$catid)));
        break;

    case 'disable':
    case 'enable':
        $F->disable((int)$_GET['fieldid'], $op == 'disable' ? 1 : 0);
        redirect('global_op_succeed', get_forward(cpurl($module, $act,'list',array('catid'=>$_GET['catid']))));
        break;
        
    case 'delete':
        $F->delete((int)$_GET['fieldid']);
        redirect('global_op_succeed', get_forward(cpurl($module, $act,'list',array('catid'=>$_GET['catid']))));
        break;

    default:

        $catid = _get('catid', 0, MF_INT_KEY);
        $category = $C->read($catid);
        if(!$category || $category['level']!='3') redirect('对不起，您选择的分类无效或不是第三级分类');

        $result = $F->field_list($catid);
        $admin->tplname = cptpl('field_list', MOD_FLAG);
        break;
}
