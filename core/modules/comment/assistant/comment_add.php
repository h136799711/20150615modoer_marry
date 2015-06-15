<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$CM =& $_G['loader']->model(':comment');
//判断是否允许游客点评
if(!$user->isLogin && !$user->check_access('comment_disable', $CM, 0)) {
    $forward = url("member/index");
    if(defined('IN_AJAX')) {
        dialog(lang('global_op_title'), '', 'login');
    } else {
        header('Location:' . get_url('member') . 'login.php?forward=' . base64_encode($forward));
        exit;
    }
}

if(!$_POST['dosubmit']) {

    redirect('global_op_unkown');

} else {

    if($user->isLogin && $MOD['member_seccode'] || !$user->isLogin && $MOD['guest_seccode']) {
        check_seccode($_POST['seccode']);
    }
    
    $post = $CM->get_post($_POST);
    $op = _input('op', null, MF_TEXT);
    if($op=='reply') {
        $cid = _post('reply_cid',0,MF_INT_KEY);
        $cid = $CM->reply($cid, $post);
    } else {
        $cid = $CM->save($post);
    }

    $url = $CM->get_url($_POST['idtype'], $_POST['id']);
    redirect(RETURN_EVENT_ID, '');
}
?>