<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$M =& $_G['loader']->model(':mylist');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];

switch($op) {
    case 'delete':
        $ids = $_POST['ids'];
        foreach ($ids as $id) {
            $M->delete($id);
        }
        redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)));
        break;
    case 'edit':
        $id = _post('id',null,MF_INT_KEY);
        $detail = $M->read($id);
        if(!$detail) redirect('对不起，您编辑的榜单不存在。');
        $admin->tplname = cptpl('mylist_save', MOD_FLAG);
        break;
    case 'save':
        $id = _post('id',null,MF_INT_KEY);
        if(!$id) redirect('未指定榜单ID。');
        $post = $M->get_post($_POST);
        $M->save($post,$id);
        redirect('global_op_succeed', cpurl($module,$act));
        break;
    case 'checkup':

        break;
    case 'set_digest':
        $id = _post('id', 0, MF_INT);
        $value = $_POST['value'] ? true : false;
        $M->set_digest($id, $value);
        echo 'OK';
        output();
        break;
    default:
        $op = 'list';

        $catid = _get('catid', 0, MF_INT_KEY);
        $title = _get('title', '', MF_TEXT);
        $username = _get('username', '', MF_TEXT);

        if($username) {
            $member = $_G['loader']->model(':member')->read($username, 1);
            if(!$member) redirect('对不起，您填写的会员不存在。');
        }

        $where = array();
        if(!$admin->is_founder) {
            $M->db->where('city_id', $admin->check_global() ? array(0, $_CITY['aid']) : $_CITY['aid']);
        } elseif(is_numeric($_GET['city_id'])) {
            $M->db->where('city_id', $_GET['city_id']);
        }
        if($catid > 0) $M->db->where('catid', $catid);
        if($member) $M->db->where('uid',$member['uid']);
        if($title) $M->db->where_like('title', "%{$title}%");
        if($_GET['starttime']) $M->db->where_more('modifytime', strtotime($_GET['starttime']));
        if($_GET['endtime']) $M->db->where_more('modifytime', strtotime($_GET['endtime']));

        $M->db->from($M->table);
        if($total = $M->db->count()) {
            $M->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'id';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $M->db->order_by($_GET['orderby'], $_GET['ordersc']);
            $M->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $M->db->select('*');
            $list = $M->db->get();
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }

        $_G['loader']->helper('form', 'mylist');
        $admin->tplname = cptpl('list', MOD_FLAG);
}
?>