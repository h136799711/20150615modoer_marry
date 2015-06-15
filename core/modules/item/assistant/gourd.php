<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$SG =& $_G['loader']->model('item:gourd');

switch($op) {
    case 'pick':
        $id = _input('id', null, MF_INT_KEY);
        $SG->pick($id);
        echo "OK";
        output();
        break;
    case 'buy':
        $sid = _input('sid', null, MF_INT_KEY);
        $SG->buy_seed($sid);
        echo "OK";
        output();
        break;
    default:
        redirect('global_op_unkown');
}
