<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$IV =& $_G['loader']->model('member:invite');
$op = _input('op');

switch($op) {
default:
    $op = 'popularize';
    $where = array();
    $select = '*';
    $orderby = array('id'=>'DESC');
    $start = get_start($_GET['page'], $offset=20);
    list($total, $list) = $IV->find($select, $where, $orderby, $start, $offset, TRUE);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'log',$_GET));
    }
    $admin->tplname = cptpl('popularize_log', MOD_FLAG);
}
?>