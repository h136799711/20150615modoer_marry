<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
$op = _input('op');

$groups = array('point1','point2','point3','point4','point5','point6');

switch($op) {
case 'group':
    if(check_submit('dosubmit')) {
        if(!is_array($_POST['point_group'])) redirect('global_op_unselect');
        $post = array();
        $post['point_group'] = serialize($_POST['point_group']);
        $C->save($post, MOD_FLAG);
        redirect('global_op_succeed', cpurl($module,$act,'group'));
    } else {
        $point_group = $C->read('point_group', MOD_FLAG);
        $point_group = unserialize($point_group['value']);
        $admin->tplname = cptpl('point_group', MOD_FLAG);
    }
    break;
case 'post':
    if(!is_array($_POST['point'])) redirect('global_op_unselect');
    $post = array();
    $post['point'] = serialize(new_intval($_POST['point']));
    $C->save($post, MOD_FLAG);
    redirect('global_op_succeed', cpurl($module,$act,'setting'));
    break;
case 'log':
    $PT =& $_G['loader']->model('member:point_log');
    $PT->db->from($PT->table);

    if(in_array($_GET['point_flow'],array('in','out'))) $PT->db->where('point_flow', $_GET['point_flow']);
    if($_GET['point_type']) $PT->db->where('point_type', $_GET['point_type']);

    if($_GET['username']) $PT->db->where('username', $_GET['username']);

    if($_GET['starttime']) $PT->db->where_more('dateline', strtotime($_GET['starttime']));
    if($_GET['endtime']) $PT->db->where_less('dateline', strtotime($_GET['endtime']));

    if($_GET['point_min']) $PT->db->where_more('point_value', $_GET['point_min']);
    if($_GET['point_max']) $PT->db->where_less('point_value', $_GET['point_max']);

    if($total = $PT->db->count()) {
        $PT->db->sql_roll_back('from,where');
        !$_GET['orderby'] && $_GET['orderby'] = 'id';
        !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
        $PT->db->order_by($_GET['orderby'], $_GET['ordersc']);
        $PT->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
        $PT->db->select('*');
        $list = $PT->db->get();
        $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'log',$_GET));
    }
    $_G['loader']->helper('form', 'member');
    $admin->tplname = cptpl('point_log', MOD_FLAG);
    break;
default:
    $op = 'setting';
    $point = $C->read('point', MOD_FLAG);
    $point = unserialize($point['value']);
    $point_group = $C->read('point_group', MOD_FLAG);
    $point_group = unserialize($point_group['value']);
    $list = read_point_rule();
    $admin->tplname = cptpl('point', MOD_FLAG);
}

function & read_point_rule() {
    global $_G;
    $result = array();
    $modules =& $_G['modules'];
    foreach($modules as $key => $val) {
        $file = MUDDER_MODULE . $val['flag'] . DS .'inc' . DS . 'point_rule.php';
        if(!$rules = read_cache($file)) continue;
        if(!is_array($rules)) continue;
        $result[$val['flag']] = $rules;
    }
    return $result;
}
?>