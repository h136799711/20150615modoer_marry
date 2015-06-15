<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$C =& $_G['loader']->model('party:comment');

switch($op) {
case 'save':
    if($MOD['comment_seccode']) check_seccode($_POST['seccode']);
    if(!$partyid = _post('partyid', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    $post = $C->get_post($_POST);
    $C->save($post,null);
    echo fetch_iframe('OK');
    exit;
case 'reply':
    if(!$commentid = _post('commentid', null, 'intval')) redirect(lang('global_sql_keyid_invalid','partyid'));
    $C->reply($commentid, _post('reply'));
    echo fetch_iframe('OK');
    exit;
case 'delete':
    $C->delete($_POST['commentids']);
    redirect('global_op_succeed_delete', get_forward(url('party/member/ac/party')));
    break;
default:
    $offset = 10;
    list($total, $list) = $F->friend_ls($user->uid, $_GET['page'], $offset);
    $total && $multipage = multi($total, $offset, $_GET['page'], url('member/index/ac/'.$ac.'/pid/'.$pid.'/page/_PAGE_'));
    $tplname = 'friend_list';
    break;
}
?>