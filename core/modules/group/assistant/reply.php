<?php
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op', null, MF_TEXT);
$RP = $_G['loader']->model('group:reply');

switch($op) {
    case 'post':
    	$post = $RP->get_post($_POST);
    	$rpid = _post('rpid', null, MF_INT_KEY);
    	if(!$rpid) $rpid = null;
        if($MOD['reply_seccode'] && $rpid == null) check_seccode($_POST['seccode']);
    	$rpid = $RP->save($post, $rpid);
        $reply = $RP->read($rpid);
    	if(RETURN_EVENT_ID == 'CHECK') {
    		redirect('global_op_succeed_check', $_G['in_ajax']?'stop':url("group/topic/id/$reply[tpid]"));
    	} else {
    		if(DEBUG||defined('IN_AJAX')) redirect('global_op_succeed', $_G['in_ajax']?'reload':url("group/topic/id/$reply[tpid]"));
            location(url("group/topic/id/$reply[tpid]"));
    	}
    	break;
    case 'edit':
        $rpid = _input('rpid', null, MF_INT_KEY);
        $detail = $RP->read($rpid);
        if(empty($detail)) redirect('group_reply_empty');
        if($detail['uid'] != $user->uid) redirect('group_reply_post_access');
        //表情
        $smilies = array();
        for ($i=1; $i <= 30; $i++) $smilies[$i] = "$i";

        $tplname = 'reply_save';
        break;
    case 'delete':
        $rpid = _input('rpid', null, MF_INT_KEY);
        $detail = $RP->read($rpid);
        if($detail['uid'] != $user->uid) redirect('group_reply_post_access');
        $RP->delete($rpid);
        location(url("group/topic/id/$detail[tpid]"));
        break;
    default:

        redirect('global_op_unknow');
 }
?>