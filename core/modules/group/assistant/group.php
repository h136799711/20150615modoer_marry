<?php
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$GP = $_G['loader']->model(':group');

$MOD['group_icon_size'] = (int)$MOD['group_icon_size'];
$MOD['group_icon_size'] < 100 && $MOD['group_icon_size'] = 100;

switch($op) {
    case 'add':
        $user->check_access('allow_create', $GP);
        $tplname = 'group_save';
        break;
    case 'edit':
        $gid = _input('gid', null, MF_INT_KEY);
        $detail = $GP->read($gid);
        if(empty($detail)) redirect('group_topic_empty');
        if($detail['uid']!=$user->uid) redirect('global_op_access');
        $tags = $GP->c_tag->field_to_string($detail['tags']);
        $tplname = 'group_save';
        break;
    case 'save':
        $edit = _post('edit') ? true : false;
        if($edit) {
            $GP->edit();
        } else {
            $GP->add();
        }
        redirect('global_op_succeed',url("group/member/ac/group"));
        break;
    case 'delete':
        $gid = _input('gid', null, MF_INT_KEY);
        $GP->delete($gid);
        redirect('global_op_succeed',url("group/member/ac/group"));
        break;
    case 'join':
        //需要审核
        $gid = _input('gid', null, MF_INT_KEY);
        if($GP->member->is_blacklist_user($gid)) {
            redirect('抱歉，小组管理员拒绝了您的加入请求。');
        }
        $check = $GP->setting->read($gid,'jointype');
        if($check) {
            if($_POST['dosubmit']) {
                $status = $GP->member->join(true);//需要审核
                if($status >=1) echo redirect('group_join_succeed');
                if($status <=0) echo redirect('group_join_check_succeed');
                output();
            } else {
                $tplname = 'group_applydes';
            }
        } else {
            $status = $GP->member->join();
            if($status >=1) echo 'OK';
            if($status <=0) echo 'CHECK';
            output();   
        }
        break;
    case 'quit':
        $gid = _input('gid', null, MF_INT_KEY);
        $GP->member->quit($gid);
        if($_G['in_ajax']) {
            echo 'OK';
            output();
        }
        location(url("group/member/ac/group/op/joined"));
        break;
    case 'joined':
        $list = $GP->joined();
        $tplname = 'group_joined';
        break;
    default:
        $list = $GP->mylist();
        $access_del  = $user->check_access('allow_delete', $GP, false);
        $tplname = 'group_list';
        break;
 }
?>