<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$F =& $_G['loader']->model(MOD_FLAG.':field');

if(!$_POST['dosubmit']) {

    $modelid = (int) $_GET['modelid'];
    $result = $F->field_list($modelid);
    $admin->tplname = cptpl('field_list', MOD_FLAG);

} else {

    $modelid = (int)$_POST['modelid'];
    $F->listorder($_POST['neworder'], $modelid);
    redirect('global_op_succeed', cpurl($module, $act, '', array('modelid'=>$modelid)));

}
?>