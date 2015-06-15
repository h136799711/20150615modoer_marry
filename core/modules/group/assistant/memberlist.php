<?php
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$GP = $_G['loader']->model(':group');

$gid = _input('gid', null, MF_INT_KEY);
$group = $GP->read($gid);
if(!$group) redirect('group_empty');
$gmember = $GP->member->read($gid,$user->uid);
if($gmember['usertype']!='1') redirect('global_op_access');

switch($op) {
    case 'get_applydes':
        $uid = _post('uid', null, MF_INT_KEY);
        $member = $GP->member->read($gid,$uid);
        if(!$member) redirect('不对起，审核信息不存在。');
        echo $member['applydes'];
        output();
    case 'checkup_join':
        $uid = _post('uid', null, MF_INT_KEY);
        if($uid == $user->uid) redirect('抱歉，您不能审核自己。');
        $status = _post('status', null, MF_INT);
        $GP->member->checkup($group, $uid, $status);
        echo 'OK';
        output();
    case 'ban':
        $uid = _input('uid', null, MF_INT_KEY);
        if($uid == $user->uid) redirect('抱歉，您不能禁言自己。');
        $member = $GP->member->read($gid, $uid);
        if(!$member) redirect('您处理的会员不是当前小组成员。');
        if($_POST['dosubmit']) {
            $bantime = _post('bantime', null, MF_TEXT);
            $message = _post('message', null, MF_TEXT);
            $GP->member->ban_post($gid,$uid,$bantime,$message);
            redirect('global_op_succeed');
        } else {
            $default_time = date('Y-m-d',$_G['timestamp']+3600*24*7);
            $tplname = 'group_banpost';
        }
        break;
    case 'unban':
        $uid = _input('uid', null, MF_INT_KEY);
        $GP->member->unban_post($gid, $uid);
        echo 'OK';
        output();
        break;
    case 'delete':
        $uid = _post('uid', null, MF_INT_KEY);
        if($uid == $user->uid) redirect('抱歉，您不能删除自己。');
        if(!$GP->is_owner($gid)) redirect('global_op_access');
        $GP->member->delete($gid, $uid);
        redirect('global_op_succeed',url("group/member/ac/group"));
        break;
    default:
        $status = _get('status', null, MF_INT);
        $where = array();
        $where['gid'] = $gid;
        is_numeric($status) && $where['status'] = $status;
        $orderby = array('jointime'=>'ASC');
        $offset = 40;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $GP->member->find('*', $where, $orderby, $start, $offset, TRUE);
        if($total > 0) {
            $multipage = multi($total, $offset, $_GET['page'], url("group/member/ac/memberlist/page/_PAGE_"));
        }
        $checkcount = $GP->member->count(array('gid'=>$gid,'status'=>0));
        $tplname = 'group_memberlist';
        break;
 }
?>