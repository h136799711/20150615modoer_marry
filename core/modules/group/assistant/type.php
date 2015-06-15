<?php
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$GP = $_G['loader']->model(':group');
$GT = $_G['loader']->model('group:type');

$gid = _input('gid', null, MF_INT_KEY);
$group = $GP->read($gid);
if(!$group) redirect('group_empty');
$gmember = $GP->member->read($gid,$user->uid);
if($gmember['usertype']!='1') redirect('global_op_access');

$url = url("group/member/ac/$ac/gid/$gid");

switch($op) {
    case 'save':
    	$typeid = $GT->save($GT->get_post($_POST));
    	if(DEBUG) redirect('global_op_succeed',$url);
    	location($url);
        break;
    case 'edit':
    	$typeid = _input('typeid', '0', MF_INT_KEY);
    	if(!$type = $GT->read($typeid)) {
    		redirect('对不起，您编辑的话题分类不存在。');
    	}
    	if($type['gid'] != $gid) redirect('对不起，这不是您所属的小组话题分类。');
    	$post = array();
    	$post['name'] = _T(decodeURIComponent($_POST['name']));
    	$GT->save($post, $typeid);
		echo 'OK';
    	output();
    	break;
    case 'delete':
    	$typeid=_input('typeid','0',MF_INT_KEY);
		$GT->delete($gid, $typeid);
    	echo 'OK';
    	output();
        break;
    case 'listorder':
    	$GT->listorder($_POST['types']);
    	if(DEBUG) redirect('global_op_succeed',$url);
    	location($url);
        break;
    default:
    	$list = $GT->get_list($gid);
        $tplname = 'group_type';

 }
?>