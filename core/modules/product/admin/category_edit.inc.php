<?php
/**
* @author 风格店铺
* @copyright (c)2009-2011 风格店铺
* @website www.cmsky.org
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model(MOD_FLAG.':gcategory');
$_G['loader']->helper('form', MOD_FLAG);

$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];
switch($op) {
case 'subcat':
    if(!$_POST['dosubmit']) {
        $catid = $_GET['catid'] = (int)$_GET['catid'];
        $t_cat = $C->read($catid);
        if($t_cat['level'] > 2) {
            redirect('productcp_cat_mainclass_invalid');
        }
        $result = $C->getlist($catid);
        $admin->tplname = cptpl('catedit_subcat', MOD_FLAG);
    } else {
        $_POST['catid'] = (int) $_POST['catid'];
        empty($_POST['catid']) && redirect(sprintf(lang('global_sql_keyid_invalid'), 'catid'));
        empty($_POST['t_cat']) && readrect(lang('global_op_nothing'));
        $C->update_subcats($_POST['t_cat'], $_POST['catid']);
        redirect('global_op_succeed', cpurl($module,$act,$op,array('catid'=>$_POST['catid'])));
    }
    break;
case 'add':
    $newcat = $_POST['newcat'];
    //vp($newcat);
    empty($newcat['name']) && redirect('productcp_cat_add_subcat_empty_name');
    empty($newcat['pid']) && redirect('productcp_cat_add_subcat_empty_pcatid');
    //if(isset($newcat['modelid'])&&!$newcat['modelid']) redirect('未选择关联模型ID');
    $C->add($newcat, true);
    redirect('global_op_succeed', cpurl($module,$act,'subcat',array('catid'=>$newcat['pid'])));
    break;
case 'edit':
	$catid = _get('catid',null,'intval');
	if(!$detail = $C->read($catid)) redirect('productcp_cat_empty');
	$admin->tplname = cptpl('catedit_att_save', MOD_FLAG);
	break;
case 'save':
	$catid = _post('catid',null,'intval');
	$post = $C->get_post($_POST);
	$C->save($post, $catid);
	redirect('global_op_succeed', cpurl($module,$act,'edit',array('catid'=>$catid)));
	break;
case 'delete':
    $_GET['catid'] = (int) $_GET['catid'];
    $pid = $C->delete($_GET['catid']);
    $url = empty($pid) ? cpurl($module,'category_list') : cpurl($module, $act, 'subcat', array('catid'=>$pid));
    redirect('global_op_succeed', $url);
    break;
default:
    redirect('global_op_unkown');
}

?>