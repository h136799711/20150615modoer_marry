<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$SN =& $_G['loader']->model('space:new');

if(!check_submit('dosubmit')) {

    //自动判断和创建空间
    //$SPACE->create($user->uid, $user->username);
    $detail = $SN->read($user->uid);
    $_G['loader']->helper('form');
    $tplname = 'new';

} else {
	
    $post = $SN->get_post($_POST);
    $SN->save($user->uid,$post);

    redirect('global_op_succeed', url('space/member/ac/new'));

}
?>