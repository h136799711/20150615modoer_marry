<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$ST =& $_G['loader']->model(MOD_FLAG.':theme');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];

switch($op) {
    case 'delete':
        $ST->delete($_GET['id']?$_GET['id']:$_POST['mids']);
        redirect('global_op_succeed_delete', cpurl($module,$act));
        break;
    case 'update':
        $LK->update($_POST['links']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'checkup':
        $LK->checkup($_POST['linkids']);
        redirect('global_op_succeed_checkup', cpurl($module,$act));
        break;
	case 'listorder':
	    $ST->listorder($_POST['ids']);
	    redirect('global_op_succeed', get_forward(cpurl($module,$act,'theme_lis')));
	    break;
    case 'edit':
    	 if(!$id = (int)$_GET['id']) redirect(lang('global_sql_keyid_invalid','id'));
    	 if(!$detail = $ST->read($id)) redirect('global_op_empty');
    case 'add':
   		 //获取分类
        $admin->tplname = cptpl('theme_save', MOD_FLAG);
        break;
    case 'save':
        if($_POST['do']=='edit') {
            if(!$id = (int)$_POST['id']) redirect(lang('global_sql_keyid_invalid','id'));
        } else {
            $id = null;
        }
        $post = $ST->get_post($_POST);
        $ST->save($post, $id);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
        break;
    case 'checklist':
        $where = array('ischeck'=>0);
        $start = get_start($_GET['page'], $offset = 20);
        list($total, $list) = $LK->find('*', $where, 'linkid', $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'checklist'));
        }
        $admin->tplname = cptpl('list', MOD_FLAG);
        break;
    case 'cate':
	    $where = array();
	    $list = $C->find($where);
    	$admin->tplname = cptpl('category_list', MOD_FLAG);
    	break;
    case 'cate_update':
		if($_POST['category']) {
		$C->update($_POST['category']);
		}
		if($_POST['newcategory']['name']) {
		    $C->save($_POST['newcategory']);
		} 
		redirect('global_op_succeed', get_forward(cpurl($module,$act,'cate')));
		break;
    case 'cate_delete':
	     $catids = isset($_POST['catids']) ? $_POST['catids'] : $_GET['catids'];
	    $C->delete($catids);
	    redirect('global_op_succeed', get_forward(cpurl($module,$act,'cate')));
	    break;
	case 'upatt':
	    $LK->upatt($_POST['linkids'],(int)$_POST['attr']);
	    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
    default:
   		 $op = 'theme_list';
        $where = array();
        !$_GET['offset'] && $_GET['offset'] = 20;
        $start = get_start($_GET['page'],$_GET['offset']);
       /* if($_GET['type']=='logo') $where['nq_logo'] = '1';
        if($_GET['type']=='char') $where['logo'] = '';*/
        !$_GET['orderby'] && $_GET['orderby'] = 'listorder';
        !$_GET['ordersc'] && $_GET['ordersc'] = 'ASC';
        list($total, $list) = $ST->find('*', $where, $_GET['orderby'].' '.$_GET['ordersc'], $start, $_GET['offset'], TRUE);
        if($total) {
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module, $act, 'theme_list', $_GET));
        }
        
        $admin->tplname = cptpl('theme_list', MOD_FLAG);
}
?>