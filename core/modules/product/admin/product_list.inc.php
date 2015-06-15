<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$P =& $_G['loader']->model(':product');
$op = _input('op',null,'_T');
$_G['loader']->helper('form','product');
$_G['loader']->helper('form','member');
$usergroup =& $_G['loader']->variable('usergroup','member');
$forward = get_forward(cpurl($module, $act));
switch($op) {
case 'create_category':
	$C =& $_G['loader']->model('product:category');
    $catid = $C->create((int)$_POST['sid'], trim($_POST['catname']));
    if(defined('IN_AJAX')) {
        echo $catid;
        output();
    }
    break;
case 'rename_category':
    $C =& $_G['loader']->model('product:category');
    $catid = $C->rename((int)$_POST['catid'], trim($_POST['catname']));
    if(defined('IN_AJAX')) {
        echo 'OK';
        output();
    }
    break;
case 'delete_category':
    $C =& $_G['loader']->model('product:category');
    $catid = _input('catid', null, MF_INT_KEY);
    $C->delete($catid);
    if(defined('IN_AJAX')) {
        echo 'OK';
        output();
    }
    break;
case 'update':
    $P->update($_POST['products']);
    redirect('global_op_succeed', $forward);
    break;
case 'add':
    $sid = _get('sid',null,'intval');
    $gcatid = (int) $_GET['catid'];
    $copy_pid = (int) $_GET['copy_pid'];
    if(!$sid || !$gcatid) {
        if($sid) $subject = $_G['loader']->model('item:subject')->read($sid,'*',false);
        $admin->tplname = cptpl('product_add', MOD_FLAG);
    } else {
        if($copy_pid > 0) {
            $cp_product = $P->read($copy_pid);
            if(!$cp_product) redirect('对不起，您准备复制的产品不存在。');
            $detail = $cp_product;
            $productatts = array();
            $atts_q = $_G['db']->from('dbpre_productatt')->where('pid', $copy_pid)->get();
            if($atts_q) while ($v = $atts_q->fetch_array()) {
                //att_catid_attid
                $productatts[$v['att_catid'].'_'.$v['attid']] = true;
            }
            $sort = (int)$cp_product['p_style'];
        }
        if(isset($_GET['sort'])) $sort = _input('sort', 1, MF_INT);
        !isset($sort) && $sort=1;
        if(!$pgcatid = $P->get_pid($gcatid)) redirect('product_cat_empty');
        $category = $_G['loader']->model('product:gcategory')->read($gcatid);
        //判断子分类是否禁用
        if(!$category['enabled']) redirect('product_cat_disabled');
        if(!$category['modelid']) redirect('对不起，新选择的分类未关联产品模型。');
        //属性
        $attcats = explode(',',trim($category['attcat'], ','));
        //主题
        $S =& $_G['loader']->model('item:subject');
        if(!$subject = $S->read($sid)) redirect('item_empty');
        //自定义字段
        $custom_form = $P->create_from($category['modelid'],$cp_product?$cp_product:null);
        $admin->tplname = cptpl('product_save', MOD_FLAG);
    }
	break;
case 'edit':
    $pid = _get('pid',0,MF_INT_KEY);
    if(!$detail = $P->read($pid)) redirect('product_empty');
	$sort = $detail['p_style'];
    $arruser = explode(',', trim($detail['usergroup']));
    $arruserprice = explode(',', trim($detail['user_price']));
    $sid = $detail['sid'];
    $catid = $detail['catid'];
	$S =& $_G['loader']->model('item:subject');
	if(!$subject = $S->read($sid)) redirect('item_empty');
	$gcatid = $detail['gcatid'];
    if(!$pgcatid = $P->get_pid($gcatid)) redirect('product_cat_empty');
    $category = $_G['loader']->model('product:gcategory')->read($gcatid);
    //判断子分类是否禁用
    if(!$category['enabled']) redirect('product_cat_disabled');
    if(!$category['modelid']) redirect('对不起，新选择的分类不是三级分类或者分类未关联模型。');
    $attcats = explode(',',trim($category['attcat'], ','));
    //自定义字段
    $custom_form = $P->create_from($category['modelid'], $detail);
    $productatts = array();
    $atts_q = $_G['db']->from('dbpre_productatt')->where('pid', $pid)->get();
    if($atts_q) while ($v = $atts_q->fetch_array()) {
        //att_catid_attid
        $productatts[$v['att_catid'].'_'.$v['attid']] = true;
    }
    $admin->tplname = cptpl('product_save', MOD_FLAG);
    break;
case 'save':
	if(_post('do') == 'edit') {
		if(!$prid = $_POST['pid']) redirect(lang('global_sql_keyid_invalid','pid'));
	} else {
		$rpid = null;
	}
    $post = $P->get_post($_POST);
    is_array($post['user_price']) ? $post['user_price'] = ','.implode(',',$post['user_price']).',':$post['user_price'] = '';
    is_array($post['usergroup']) ? $post['usergroup'] = ','.implode(',',$post['usergroup']).',':$post['usergroup'] = '';
    $post['tag_keyword'] ? $post['tag_keyword'] = ','.trim($post['tag_keyword'],',').',':$post['tag_keyword'] = '';
    $post['field_data'] = $_POST['t_item'];
    $pid = $P->save($post, $prid);
	$navs = array(
        array('name'=>'product_redirect_productlist','url'=>cpurl($module,$act,'list')),
		array('name'=>'global_redirect_return','url'=>get_forward(cpurl($module,$act,'list'),1)),
		array('name'=>'product_redirect_newproduct','url'=>cpurl($module,$act,'add',array('sid'=>$post['sid']))),
		array('name'=>'product_redirect_subjectproduct','url'=>cpurl($module,$act,'add')),
	);
	redirect('global_op_succeed',$navs);
    break;
case 'get_att':
    $pid = _input('pid', null, MF_INT_KEY);
    $catid = _input('catid', null, MF_INT_KEY);
    if($pid>0) {
        $product = $P->read($pid);
        if($product && !$catid) $catid = $product['gcatid'];
    }
    $pgcatid = $P->get_pid($catid);
    if($pgcatid>0) {
        $category = $_G['loader']->model('product:gcategory')->read($catid);
        if(!$category['enabled']) redirect('product_cat_disabled');
        if($category) {
            //筛选属性
            $attcats = explode(',',trim($category['attcat'], ','));
            $attmulti = array();
            foreach ($attcats as $key => $value) {
                if(preg_match("/^[0-9]+\|[0-9]{1}$/", $value)) {
                    list($_catid,$is_multi) = explode('|', $value);
                    $attcats[$key]=$_catid;
                    $is_multi && $attmulti[]=$_catid;
                } elseif(is_numeric($value)) {
                    $attcats[$key]=$value;
                }
            }
            $productatts = array();
            if($product) {
                $atts_q = $_G['db']->from('dbpre_productatt')->where('pid', $pid)->get();
                if($atts_q) while ($v = $atts_q->fetch_array()) {
                    $productatts[$v['att_catid'].'_'.$v['attid']] = true;
                }
            }
        }
        $admin->tplname = cptpl('product_save_att', MOD_FLAG);
    } else {
        echo '';
        output();
    }
    break;
case 'succeed':
    $sid = _get('sid', 0, MF_INT_KEY);
    $navs = array (
        array('name'=>'product_redirect_productlist','url'=>cpurl($module,$act,'list')),
        array('name'=>'global_redirect_return','url'=>get_forward(cpurl($module,$act,'list'),1)),
        array('name'=>'product_redirect_newproduct','url'=>cpurl($module,$act,'add',array('sid'=>$sid))),
        array('name'=>'product_redirect_subjectproduct','url'=>cpurl($module,$act,'add')),
    );
    redirect('global_op_succeed',$navs);
    break;
case 'delete':
    $P->delete($_POST['pids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'checkup':
    $P->checkup($_POST['pids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'checklist':
    $where = array();
    if(!$admin->is_founder) $where['s.city_id'] = $admin->check_global() ? array(0,$_CITY['aid']) : $_CITY['aid'];
    if(is_numeric($_GET['city_id']) && $_GET['city_id'] >=0) $where['city_id'] = $_GET['city_id'];
    $where['p.status'] = 0;
    list($total,$list) = $P->find('p.pid,p.sid,p.subject,p.description,p.thumb,p.status,p.dateline',$where,array('p.dateline'=>'DESC'),$start,$offset,TRUE,'s.name,s.subname');
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'checklist'));
    }
    $admin->tplname = cptpl('product_check', MOD_FLAG);
    break;
default:
    $op = 'list';
        $catid = (int) $_GET['gcatid'];
        if($catid > 0) {
            $category = $_G['loader']->model('product:gcategory')->read($catid);
            if($category['level']=='1') {
                $P->db->where('p.pgcatid',$catid);
            } else {
                $catelist = $_G['loader']->variable('gcategory_'.$catid,'product');
                $ids = array();
                foreach($catelist as $key=>$val) {
                    $ids[] = $key;
                }
                $P->db->where('p.gcatid',array($ids));
            }

        }
        if(!$admin->is_founder) {
            $P->db->where('p.city_id', $admin->check_global() ? array(0,$_CITY['aid']) : $_CITY['aid']);
        }
        if(is_numeric($_GET['p.city_id']) && $_GET['p.city_id'] >= 0) {
            $P->db->where('city_id', $_GET['city_id']);
        }
        $_GET['pid'] && $P->db->where('p.pid',$_GET['pid']);
        $_GET['sid'] && $P->db->where('p.sid',$_GET['sid']);
        $_GET['subject'] && $P->db->where_like('p.subject',"%".trim($_GET['subject']."%"));
        $_GET['shape_code'] && $P->db->where('p.shape_code',trim($_GET['shape_code']));
        $_GET['starttime'] && $P->db->where_more('dateline', strtotime($_GET['starttime']));
        $_GET['endtime'] && $P->db->where_less('dateline', strtotime($_GET['endtime']));
        $_GET['promote'] && $P->db->where_more('promote', 0, false);
        $P->db->where('p.status',1);
        $P->db->join($P->table, 'p.sid', 'dbpre_subject', 's.sid');
        if($total = $P->db->count()) {
            $P->db->select('p.*,s.name,s.subname');
            $P->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'pid';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $P->db->order_by('p.'.$_GET['orderby'], $_GET['ordersc']);
            $P->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $list = $P->db->get();
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }
        if($_GET['sid']) {
            $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $_GET['sid'], true);
        }

    $_G['loader']->helper('form',MOD_FLAG);
    $admin->tplname = cptpl('product_list', MOD_FLAG);
}
?>