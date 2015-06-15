<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$group_obj =& $_G['loader']->model(':group');
$TP =& $_G['loader']->model('group:topic');
$op = _input('op');

switch ($op) {
case 'delete':
    $TP->delete($_POST['tpids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
case 'edit':
    $tpid = _get('tpid',0,MF_INT_KEY);
    $detail = $TP->read($tpid);
    //分类选择
    $setting = $group_obj->setting->read_all($detail['gid']);
    $needtypeid = (int)$setting['needtypeid'];
    if($needtypeid > 0) {
        $typecount = $_G['loader']->model('group:type')->get_count($detail['gid']);
    }
    //小组信息
    $group = $group_obj->read($detail['gid']);
    
    $admin->tplname = cptpl('topic_save', MOD_FLAG);
    break;
case 'save':
    $tpid = _post('tpid',0,MF_INT_KEY);
    $post = $TP->get_post($_POST);
    $TP->save($post, $tpid);
    redirect('global_op_succeed', get_forward(cpurl($module,$act),1));	
	break;
case 'checklist':
	$where = array();
	$where['tp.status'] = 0;
	$TP->db->join($TP->table,'tp.gid','dbpre_group','g.gid', 'left join');
	$TP->db->where($where);
    if($total = $TP->db->count()) {
        $TP->db->sql_roll_back('from,where');
        !$_GET['orderby'] && $_GET['orderby'] = 'tpid';
        !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
        $TP->db->order_by($_GET['orderby'], $_GET['ordersc']);
        $TP->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
        $TP->db->select('tp.*,g.groupname');
        $list = $TP->db->get();
        $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'checklist',$_GET));
    }
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));
	$admin->tplname = cptpl('topic_checklist', MOD_FLAG);
	break;
case 'checkup':
	$TP->checkup($_POST['tpids']);
	redirect('global_op_succeed', get_forward(cpurl($module,$act,$op)));
	break;
default:

    $_G['loader']->helper('form','group');

    $catid = _get('catid', null, MF_INT_KEY);
    $gid = _get('gid', null, MF_INT_KEY);
    $uid = _get('uid', null, MF_INT_KEY);
    $username = _get('username', null, MF_TEXT);

    $where = array();
    if($catid > 0) $where['g.catid'] = $catid;
    if($gid > 0) $where['tp.gid'] = $gid;
    
    if($uid) $where['tp.uid'] = $uid;
    if($username) $where['tp.username'] = $username;
    $where['tp.status'] = 1;

    $TP->db->join($TP->table,'tp.gid','dbpre_group','g.gid', 'left join');
    $TP->db->where($where);

    if($_GET['starttime']) $DC->db->where_more('tp.dateline', strtotime($_GET['starttime']));
    if($_GET['endtime']) $DC->db->where_less('tp.dateline', strtotime($_GET['endtime']));

    if($total = $TP->db->count()) {
        $TP->db->sql_roll_back('from,where');
        !$_GET['orderby'] && $_GET['orderby'] = 'tpid';
        !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
        $TP->db->order_by($_GET['orderby'], $_GET['ordersc']);
        $TP->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
        $TP->db->select('tp.*,g.groupname');
        $list = $TP->db->get();
        $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
    }
    //$multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));
    $admin->tplname = cptpl('topic_list', MOD_FLAG);

    $_G['loader']->helper('form','item');
    if($sid>0) $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $sid, true);
 }