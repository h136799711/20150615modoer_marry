<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'comment');

$op = _get('op','',MF_TEXT);
$CM =& $_G['loader']->model(':comment');

if($user->isLogin && $MOD['member_seccode'] || !$user->isLogin && $MOD['guest_seccode']) {
    check_seccode($_POST['seccode']);
}
$post = $CM->get_post($_POST);
if(_G('chaset')!='utf-8') {
    foreach ($post as $key => $value) {
        if(!is_numeric($value)) {
            $post[$key] = charset_convert($value, 'utf-8', _G('charset'));
        }
    }
}

if($op == 'comment') {

    $cid = $CM->save($post);

} elseif($op== 'reply') {

    $reply_cid = _post('reply_cid', 0, MF_INT_KEY);
    $cid = $CM->reply($reply_cid, $post);

} else {
    redirect('global_op_unkown');
}

//读取刚刚保存的记录
if($cid > 0) {
    $comment = $CM->read($cid);
    if(!$comment) {
        redirect('数据读取失败！');
    }
    if($comment['reply_cid'] > 0) {
        $reply = $CM->read($comment['reply_cid']);
    }
    if(_G('chaset') != 'utf-8') {
        foreach ($comment as $key => $value) {
            if(!is_numeric($value)) {
                $comment[$key] = charset_convert($value, _G('charset'), 'utf-8');
            }
        }
        if($reply) foreach ($reply as $key => $value) {
            if(!is_numeric($value)) {
                $reply[$key] = charset_convert($value, _G('charset'), 'utf-8');
            }
        }
    }
    $comment['face'] = get_face($comment['uid']);
    if($reply) {
        $reply['face'] = get_face($reply['uid']);
        $comment['reply_data'] = $reply;
    }
    $ret = array (
        'code' => 200,
        'data' => $comment,
    );
    echo json_encode($ret);
    output();
} else {
    redirect('提交失败！');
}
