<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$G =& $_G['loader']->model(':group');
$op = _input('op');

switch ($op) {
case 'change_owner':
    $gid = _post('gid', 0, MF_INT);
    $username = _T(decodeURIComponent($_POST['username']));
    $G->member->change_owner($gid, $username);
    echo 'OK';
    output();
case 'set_finer':
    $gid = _post('gid', 0, MF_INT);
    $detail = $G->read($gid);
    if(!$detail) redirect('group_empty');
    $value = $_POST['value'] ? '1' : '0';
    $G->db->from($G->table)->set('finer',$value)->where('gid',$gid)->update();
    echo 'OK';
    output();
case 'delete':
    $gid = _input('gid', null, MF_INT);
    $G->delete($gid,true);
    redirect('global_op_succeed',get_forward(cpurl($module,$act)));
    break;
case 'edit':
    $_G['loader']->helper('form','group');
    $gid = _get('gid',0,MF_INT_KEY);
    $detail = $G->read($gid);
    if(!$detail) redirect('group_empty');
    $tags = $G->c_tag->field_to_string($detail['tags']);
    if($detail['sid']) {
        $S =& $_G['loader']->model('item:subject');
        if(!$subject = $S->read($detail['sid'],'sid,pid,name,subname')) redirect('item_empty');
    }
    $admin->tplname = cptpl('group_save', MOD_FLAG);
    break;
case 'save':
    $gid = _post('gid',0,MF_INT_KEY);
    if(!$gid) redirect('未指定GID。');
    $G->edit();
    redirect('global_op_succeed', get_forward(cpurl($module,$act),1));	
	break;
case 'checklist':
	$where = array();
	$where['status'] = 0;
	$G->db->from($G->table);
	$G->db->where($where);
    if($total = $G->db->count()) {
        $G->db->sql_roll_back('from,where');
        !$_GET['orderby'] && $_GET['orderby'] = 'gid';
        !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
        $G->db->order_by($_GET['orderby'], $_GET['ordersc']);
        $G->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
        $G->db->select('*');
        $list = $G->db->get();
        $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'checklist',$_GET));
    }
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));
	$admin->tplname = cptpl('group_checklist', MOD_FLAG);
	break;
case 'nopass'://审核不通过
    $gid = _post('gid', 0, MF_INT_KEY);
    $message = _T(decodeURIComponent($_POST['message']));
    $G->check_nopass($gid, $message);
    echo 'OK';
    output();
    break;
case 'checkup':
	$G->check_pass($_POST['gids']);
	redirect('global_op_succeed', get_forward(cpurl($module,$act,$op)));
	break;
default:
    $_G['loader']->helper('form','group');

    $catid = _get('catid', null, MF_INT_KEY);
    $gid = _get('gid', null, MF_INT_KEY);
    $uid = _get('uid', null, MF_INT_KEY);
    $username = _get('username', null, MF_TEXT);

    $where = array();
    if($catid > 0) $where['catid'] = $catid;
    if($gid > 0) $where['gid'] = $sid;
    
    if($uid) $where['uid'] = $uid;
    if($username) $where['username'] = $username;
    $where['status'] = array('where_more',array(1));

    $G->db->from($G->table);
    $G->db->where($where);

    if($_GET['starttime']) $G->db->where_more('createtime', strtotime($_GET['starttime']));
    if($_GET['endtime']) $G->db->where_less('createtime', strtotime($_GET['endtime']));

    if($total = $G->db->count()) {
        $G->db->sql_roll_back('from,where');
        !$_GET['orderby'] && $_GET['orderby'] = 'gid';
        !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
        $G->db->order_by($_GET['orderby'], $_GET['ordersc']);
        $G->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
        $G->db->select('*');
        $list = $G->db->get();
        $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
    }
    //$multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));
    $admin->tplname = cptpl('group_list', MOD_FLAG);

    $_G['loader']->helper('form','item');
    if($sid>0) $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $sid, true);
 }