<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('group:category');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
$GT = $_G['loader']->model('group:tag');

switch($op) {
    case 'delete':
        $C->delete(_get('catid',0,'intval'));
        redirect('global_op_succeed_delete', cpurl($module,$act));
        break;
    case 'update':
        $C->update(_post('category'));
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'add':
        $admin->tplname = cptpl('category_save', MOD_FLAG);
        break;
    case 'edit':
        $catid = _post('catid',null,MF_INT_KEY);
        $category = $C->read($catid);
        $admin->tplname = cptpl('category_save', MOD_FLAG);
        break;
    case 'save':
        $catid = _post('edit') ? _post('catid',null,MF_INT_KEY) : null;
        $post = $C->get_post($_POST['t_cat']);
        $C->save($post,$catid);
        redirect('global_op_succeed', cpurl($module,$act));
    default:
        $op = 'list';
        $offset = 40;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $C->find('*', $where, 'listorder', $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list'));
        }
        $admin->tplname = cptpl('category', MOD_FLAG);
}
?>