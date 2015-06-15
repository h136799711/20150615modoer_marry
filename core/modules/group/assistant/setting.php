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
    case 'post':
        $GP->setting->save_post($gid);
        redirect('global_op_succeed', url("group/member/ac/setting/gid/$gid"));
        break;
    default:
        $_G['loader']->helper('form');
        $setting = $GP->setting->read_all($gid);
        $tplname = 'group_setting';
        break;
 }
?>