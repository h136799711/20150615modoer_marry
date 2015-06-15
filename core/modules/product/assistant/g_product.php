<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
if(!$_G['subject_owner']) redirect('item_manage_access');

$op = _input('op','',MF_TEXT);
$P =& $_G['loader']->model(':product');

$_G['loader']->helper('form');
$_G['loader']->helper('form','product');
$_G['loader']->helper('form','item');
$_G['loader']->helper('form','member');

//正在处理的主题ID
$sid = $_G['manage_subject']['sid'];

switch($op) {
    case 'sale_on':
    case 'sale_off':
        $forward = get_forward();
        $pids = _post('pids', 0, MF_INT_KEY);
        $where = array('sid' => $sid,'pid' => $pids, 'is_on_sale'=>$op=='sale_on'?0:1);
        $r = $P->find_all('pid,subject,p_style,stock,is_on_sale,status', $where);
        if(!$r) redirect('没有数据更新。', $forward);
        $_pids = array();
        while ($val = $r->fetch_array()) {
            if($val['status'] < 1 && $op == 'sale_on') redirect('产品'.$val['subject'].'未审核，不能上架。');
            if($val['stock'] < 1 && $op == 'sale_on') redirect('产品'.$val['subject'].'库存不足，不能上架。');
            $_pids[] = $val['pid'];
        }
        $r->free_result();
        $P->$op($_pids); //提交到数据库
        redirect('global_op_succeed', $forward);
        break;
    case 'add':
        if($_GET['sid']) $sid = _input('sid', $sid, MF_INT_KEY);
        $copy_pid = (int) $_GET['copy_pid'];
        //主题判断
        $S =& $_G['loader']->model('item:subject');
        if(!$subject = $S->read($sid,'sid,pid,catid,name,subname,status')) redirect('item_empty');
        //if(!$modelid = $P->get_modelid($subject['pid'])) redirect('product_model_empty');
        $gcatid = _input('catid', 0, MF_INT_KEY);
        if($gcatid) {
            if($copy_pid > 0 && $MOD['product_copy_enable']) {
                $cp_product = $P->read($copy_pid);
                if(!$cp_product) redirect('对不起，您准备复制的产品不存在。');
                if(!$S->is_mysubject($cp_product['sid'],$user->uid)) {
                    $PS = $_G['loader']->model('product:subjectsetting');
                    $copy_disable = $PS->read($cp_product['sid'],'copy_disable');
                    if(!$copy_disable) redirect('对不起，您选择复制的产品所属管理员不允许其他人复制。');
                }
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
            if(!$category['modelid']) redirect('对不起，新选择的分类未关联模型。');
            //属性
            $attcats = explode(',',trim($category['attcat'], ','));
            //主题
            $S =& $_G['loader']->model('item:subject');
            if(!$subject = $S->read($sid)) redirect('item_empty');
            //自定义字段
            $custom_form = $P->create_from($category['modelid'],$cp_product?$cp_product:null);

            $U =& $_G['loader']->model('member:usergroup');
            $usergroup = $U->read_all(array('member','special'));

            $tplname = 'product_save';
        } else {
            $tplname = 'product_add';
        }
        break;
    case 'edit':
        $pid = _get('pid',0,MF_INT_KEY);
        if(!$detail = $P->read($pid)) redirect('product_empty');

        $arruser = explode(',', trim($detail['usergroup']));
        $arruserprice = explode(',', trim($detail['user_price']));

        $sid = $detail['sid'];
        $S =& $_G['loader']->model('item:subject');
        if(!$subject = $S->read($sid)) redirect('item_empty');
        $sort = (int)$detail['p_style'];

        $gcatid = $detail['gcatid'];
        if(!$pgcatid = $P->get_pid($gcatid)) redirect('product_cat_empty');
        $category = $_G['loader']->model('product:gcategory')->read($gcatid);
        //判断子分类是否禁用
        if(!$category['enabled']) redirect('product_cat_disabled');
        if(!$category['modelid']) redirect('对不起，新选择的分类不是三级分类或者分类未关联模型。');

        //筛选属性
        $attcats = explode(',',trim($category['attcat'], ','));
        $productatts = array();
        $atts_q = $_G['db']->from('dbpre_productatt')->where('pid', $pid)->get();
        if($atts_q) while ($v = $atts_q->fetch_array()) {
            //att_catid_attid
            $productatts[$v['att_catid'].'_'.$v['attid']] = true;
        }

        //自定义字段
        $custom_form = $P->create_from($category['modelid'], $detail);
        $U =& $_G['loader']->model('member:usergroup');
        $usergroup = $U->read_all(array('member','special'));

        $sort = $detail['p_style'];

        $tplname = 'product_save';
        break;
    case 'delete':
        $pid = _post('pid', 0, MF_INT_KEY);
        $product = $P->read($pid);
        if(!$product) redirect('product_empty');
        $S =& $_G['loader']->model('item:subject');
        if(!$product['sid'] && !$S->is_mysubject($product['sid'], _G('user')->uid)) {
            redirect('global_op_access');
        }
        $P->delete($pid);
        if($_G['in_ajax']) {
            echo 'ok';
            output();
        }
        $forward = get_forward();
        redirect('global_op_succeed_delete', $forward);
    case 'post':
        if($_POST['do']=='add'&& $MOD['seccode_product']) {
            check_seccode($_POST['seccode']);
        }
        $prid = $_POST['do'] == 'edit' ? (int)$_POST['pid'] : null;
        $post = $P->get_post($_POST);
        is_array($post['user_price'])?$post['user_price'] = ','.implode(',',$post['user_price']).',':$post['user_price'] = '';
        is_array($post['usergroup'])?$post['usergroup'] = ','.implode(',',$post['usergroup']).',':$post['usergroup'] = '';
        $post['tag_keyword']?$post['tag_keyword'] = ','.trim($post['tag_keyword'],',').',':$post['tag_keyword'] = '';
        $post['field_data'] = $_POST['t_item'];
        $pid = $P->save($post, $prid);
        redirect(RETURN_EVENT_ID, url('product/member/ac/g_product/sid/'.$post['sid']));
        break;
    case 'get_att':
        $pid = _input('pid', null, MF_INT_KEY);
        $catid = _input('catid', null, MF_INT_KEY);
        if($pid > 0) {
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
            $tplname = 'product_save_att';
        } else {
            echo '';
            output();
        }
        break;
    default:
        $op = 'list';
        $S = $_G['loader']->model('item:subject');
        //if(!$subjects = $S->mysubject($user->uid)) redirect('product_mysubject_empty');
        //if($sid && !in_array($sid, $subjects)) redirect('product_mysubject_nonentity');
        $catid = (int) $_GET['catid'];

        $where = array();
        $where['p.sid'] = $sid;
        $catid > 0 && $where['p.catid'] = $catid;
        $offset = 20;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $P->find('p.*', $where, array('p.dateline'=>'DESC'), $start, $offset, TRUE, 's.name,s.subname');
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], url("product/member/ac/g_product/sid/$sid/catid/$catid/page/_PAGE_"));
            //内部分类
            $category = array();
            $C =& $_G['loader']->model('product:category');
            if($r = $C->get_list($sid)) {
                foreach($r as $k => $v) {
                    $category[$v['catid']] = $v['name'];
                }
            }
        }
        $tplname = 'product_list';
}
?>