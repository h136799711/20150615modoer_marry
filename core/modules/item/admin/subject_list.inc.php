<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$I =& $_G['loader']->model(MOD_FLAG.':subject');
$op = _input('op');
$forward = get_forward(cpurl($module, $act,'',array('pid' => $_GET['pid'])));

switch ($op) {
    case 'delete':
        $I->delete($_POST['sids'], $_POST['delete_point']);
        redirect('global_op_succeed', $forward);
        break;
    case 'rebuild':
        $I->rebuild($_POST['sids']);
        redirect('global_op_succeed', $forward);
        break;
    case 'update':
        $I->update($_POST['subjects']);
        redirect('global_op_succeed', $forward);
        break;
    case 'move':
        $I->move($_POST['sids'], abs((int)$_POST['moveto_catid']));
        redirect('global_op_succeed', $forward);
        break;
    case 'recycle':
        if($_POST['dosubmit']){
            $I->recycle($_POST['sids']);
            redirect('global_op_succeed', $forward);
        } else {
            $where = array();
            //$where['pid'] = (int) $pid;
            if(!$admin->is_founder) $where['city_id'] = $admin->check_global() ? array(0,$_CITY['aid']) : $_CITY['aid'];
            $where['status'] = -1;
            $select = 'sid,city_id,pid,catid,name,subname,addtime,cuid,creator,status';
            $start = get_start($_GET['page'], $offset = 20);
            list($total, $list) = $I->find($select, $where, array('addtime'=>'DESC'), $start, $offset);
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'recycle'));
            //指定模板
            $admin->tplname = cptpl('subject_recycle', MOD_FLAG);
        }
        break;
    case 'restore':
        $I->restore($_POST['sids']);
        redirect('global_op_succeed', $forward);
        break;
    case 'forums':
        $_G['loader']->helper('modcenter');
        if($forums = modcenter::get_forums()) {
            foreach($forums as $fid => $name) {
                echo '<option value="'.$fid.'">'.$name.'</option>';
            }
        }
        exit;
        break;
    default:
        $where = $atts = array();
        $pid = 0;
        if(is_numeric($_GET['sid'])) {
            $where['s.sid']=(int)$_GET['sid'];
        } else {
            $catid = (int) $_GET['catid'];
            if($catid>0) {
                $I->get_category($catid);
                $pid = (int)$I->category['catid'];
                $atts['category'] = $I->get_attid($catid);
            }
            $area = $_G['loader']->model('area');
            $area_attid = array();
            if(is_numeric($_GET['aid']) && $_GET['aid'] > 0) {
                $city_id = $area->get_parent_aid($_GET['aid']);
                if(!$admin->is_founder) {
                    if(!in_array($city_id, $admin->mycitys)) redirect('对不起，您没有权限。');
                }
                $area_attid = $area->get_attid($_GET['aid']);
            } else {
                if(!$admin->is_founder) {
                    if($_GET['aid'] == -1) {
                        if(!$admin->check_global()) redirect('对不起，您没有权限。');
                    } else {
                        $area_attid = $area->get_attid($_CITY['aid']);
                    }
                }
            }
            if($area_attid) $atts['area'] = $area_attid;

            if($_GET['keyword']) $where['name']=array('where_like',array('%'._T($_GET['keyword']).'%'));
            if($_GET['creator']) $where['creator']=$_GET['creator'];
            if($_GET['owner']) $where['owner']=$_GET['owner'];
            if($_GET['starttime']) $where['addtime']=array('where_more',array(strtotime($_GET['starttime'])));
            if($_GET['endtime']) $where['addtime']==array('where_less',array(strtotime($_GET['endtime'])));
            $where['status'] = 1;
            if($atts) $where['attid'] = $atts;
        }
        
        $start = get_start($_GET['page'],$_GET['offset']);
        !$_GET['orderby'] && $_GET['orderby'] = 'sid';
        !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
        $orderby = array('s.'.$_GET['orderby']=>$_GET['ordersc']);

        list($total, $list) = $I->getlist($pid, (!$pid?'s.*':'s.*,sf.*'), $where, $orderby, $start, $_GET['offset']);
        if($total) {
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }

        $_G['loader']->helper('form', $I->model_flag);
        $admin->tplname = cptpl('subject_list', MOD_FLAG);
}

function p_order($order) {
    if($_GET['order'] == $order) {
        if($_GET['by'] == 'ASC') return '↑';
        if($_GET['by'] == 'DESC') return '↓';
    }
}
?>